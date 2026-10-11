<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearchEvaluationModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchProgressModel;
use App\Models\ResearchTitleModel;
use App\Models\User;
use App\Models\AuditLogModel;

class Evaluations extends BaseController
{
    protected $evaluationModel;
    protected $scheduleModel;
    protected $progressModel;
    protected $titleModel;
    protected $userModel;

    public function __construct()
    {
        $this->evaluationModel = new ResearchEvaluationModel();
        $this->scheduleModel = new ResearchScheduleModel();
        $this->progressModel = new ResearchProgressModel();
        $this->titleModel = new ResearchTitleModel();
        $this->userModel = new User();
    }

    public function index()
    {
        if (! is_research_office()) {
            return unauthorized_redirect('Access Denied: You do not have permission to view evaluations.');
        }

        $view = $this->request->getGet('view') ?? 'all';
        $stats = $this->evaluationModel->getSummaryStats();
        $pendingCount = $this->scheduleModel
                             ->join('research_evaluations re', 're.schedule_id = research_schedules.id', 'left')
                             ->where('re.id IS NULL')
                             ->countAllResults(false);
        $stats['pending_eval'] = $pendingCount;

        $db = \Config\Database::connect();
        $builder = $db->table('research_schedules rs')
            ->select('
                rs.id as schedule_id,
                rs.schedule_type,
                rs.schedule_title,
                rs.schedule_date,
                rs.schedule_time,
                rs.venue,
                rs.status as schedule_status,
                rs.researcher_id,
                rs.student_id,
                COALESCE(r.fullname, r.author, u_student.username, "N/A") as researcher_name,
                COALESCE(rs.schedule_title, r.approved_research_title, "Research Study") as display_title,
                u_instructor.username as instructor_name,
                re.id as evaluation_id,
                re.result as remark_status,
                re.overall_score,
                re.remarks,
                re.compliance_deadline,
                re.compliance_submitted,
                re.evaluated_at,
                re.evaluated_by,
                u_evaluator.username as evaluator_name
            ')
            ->join('researchers r', 'r.id = rs.researcher_id', 'left')
            ->join('users u_student', 'u_student.id = rs.student_id', 'left')
            ->join('users u_instructor', 'u_instructor.id = rs.instructor_id', 'left')
            ->join('research_evaluations re', 're.schedule_id = rs.id', 'left')
            ->join('users u_evaluator', 'u_evaluator.id = re.evaluated_by', 'left');

        if ($view === 'passed') {
            $builder->where('re.result', 'passed');
        } elseif ($view === 'failed') {
            $builder->where('re.result', 'failed');
        } elseif ($view === 'for_compliance') {
            $builder->where('re.result', 'for_compliance');
        } elseif ($view === 'pending') {
            $builder->groupStart()
                    ->where('re.id IS NULL')
                    ->orGroupStart()
                        ->where('re.result', 'for_compliance')
                        ->where('re.compliance_submitted', 0)
                    ->groupEnd()
                ->groupEnd();
        }

        $records = $builder->orderBy('rs.schedule_date', 'DESC')->get()->getResultArray();

        $allSchedules = $db->table('research_schedules rs')
            ->select('rs.id, rs.schedule_type, rs.schedule_date, COALESCE(rs.schedule_title, r.approved_research_title, "Defense") as schedule_title, COALESCE(r.fullname, r.author, u.username, "Researcher") as student_name, rs.researcher_id, rs.student_id')
            ->join('researchers r', 'r.id = rs.researcher_id', 'left')
            ->join('users u', 'u.id = rs.student_id', 'left')
            ->orderBy('rs.schedule_date', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Evaluations & Remarks',
            'page_title'   => 'Research Evaluation and Remarks',
            'view'         => $view,
            'stats'        => $stats,
            'records'      => $records,
            'allSchedules' => $allSchedules,
        ];
        return view('admin/evaluations/index', $data);
    }

    public function store()
    {
        if (! is_research_office()) {
            return unauthorized_redirect('Access Denied: You do not have permission to evaluate research records.');
        }

        $scheduleId = $this->request->getPost('schedule_id');
        $schedule = $this->scheduleModel->find($scheduleId);
        if (!$schedule) {
            return redirect()->back()->with('error', 'Research schedule not found.');
        }

        $result = trim((string) $this->request->getPost('result'));
        $allowedResults = ['passed', 'failed', 'for_compliance'];
        if (!in_array($result, $allowedResults, true)) {
            return redirect()->back()->withInput()->with('error', 'Please select a valid Remark Status (Passed, Failed, or For Compliance).');
        }

        $currentUserId = session()->get('user_id') ?: (session()->get('userId') ?: 1);

        $data = [
            'schedule_id'          => (int) $scheduleId,
            'researcher_id'        => !empty($schedule['researcher_id']) ? (int) $schedule['researcher_id'] : ($this->request->getPost('researcher_id') ?: null),
            'student_id'           => !empty($schedule['student_id']) ? (int) $schedule['student_id'] : ($this->request->getPost('student_id') ?: null),
            'evaluated_by'         => (int) $currentUserId,
            'result'               => $result,
            'overall_score'        => $this->request->getPost('overall_score') !== '' ? $this->request->getPost('overall_score') : null,
            'remarks'              => trim((string) $this->request->getPost('remarks')),
            'compliance_deadline'  => ($result === 'for_compliance' && $this->request->getPost('compliance_deadline')) ? $this->request->getPost('compliance_deadline') : null,
            'compliance_submitted' => $this->request->getPost('compliance_submitted') ? 1 : 0,
            'evaluated_at'         => date('Y-m-d H:i:s'),
        ];

        // Check if an evaluation already exists for this schedule (prevents duplicates)
        $existing = $this->evaluationModel->getBySchedule($scheduleId);
        if ($existing) {
            $saved = $this->evaluationModel->update($existing['id'], $data);
            $evalId = $existing['id'];
            $logAction = 'EVALUATION_UPDATE';
        } else {
            $evalId = $this->evaluationModel->insert($data);
            $saved = (bool) $evalId;
            $logAction = 'EVALUATION_CREATE';
        }

        if ($saved) {
            AuditLogModel::log($logAction, 'research_evaluations', $evalId, [
                'schedule_id'         => $scheduleId,
                'researcher_id'       => $data['researcher_id'],
                'student_id'          => $data['student_id'],
                'remark_status'       => $result,
                'overall_score'       => $data['overall_score'],
                'compliance_deadline' => $data['compliance_deadline'],
            ]);

            // Update schedule status to completed
            $this->scheduleModel->update($scheduleId, ['status' => 'completed']);

            // Synchronize research progress
            if (!empty($data['researcher_id'])) {
                if ($result === 'passed') {
                    if ($schedule['schedule_type'] === 'pre_oral') {
                        $this->progressModel->updateStage($data['researcher_id'], 'pre_oral_passed', 'Pre-oral defense passed - Score: ' . ($data['overall_score'] ?? 'Passed'), $currentUserId);
                    } elseif ($schedule['schedule_type'] === 'final_defense') {
                        $this->progressModel->updateStage($data['researcher_id'], 'final_defense_passed', 'Final defense passed - Score: ' . ($data['overall_score'] ?? 'Passed'), $currentUserId);
                        $title = $this->titleModel->where('researcher_id', $data['researcher_id'])->orderBy('id', 'DESC')->first();
                        if ($title) {
                            $this->titleModel->update($title['id'], ['status' => 'completed', 'approved_at' => $title['approved_at'] ?? date('Y-m-d H:i:s')]);
                        }
                    }
                }
            }

            $statusLabels = ['passed' => 'Passed', 'failed' => 'Failed', 'for_compliance' => 'For Compliance'];
            return redirect()->to('/admin/evaluations')->with('success', 'Evaluation and Remark Status (' . $statusLabels[$result] . ') saved successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save evaluation.');
    }

    public function markComplianceSubmitted($id)
    {
        if (! is_research_office()) {
            return unauthorized_redirect('Access Denied.');
        }

        $eval = $this->evaluationModel->find($id);
        if (!$eval) return redirect()->back()->with('error', 'Evaluation not found');
        if ($this->evaluationModel->update($id, ['compliance_submitted' => 1])) {
            AuditLogModel::log('EVALUATION_COMPLIANCE_SUBMITTED', 'research_evaluations', $id, $eval);
            return redirect()->to('/admin/evaluations')->with('success', 'Compliance marked as submitted');
        }
        return redirect()->back()->with('error', 'Failed to update compliance status.');
    }

    public function delete($id)
    {
        if (! is_research_office()) {
            return unauthorized_redirect('Access Denied.');
        }

        $existing = $this->evaluationModel->find($id);
        if ($this->evaluationModel->delete($id)) {
            AuditLogModel::log('EVALUATION_DELETE', 'research_evaluations', $id, $existing ?? ['id' => $id]);
            return redirect()->to('/admin/evaluations')->with('success', 'Evaluation deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete evaluation.');
    }
}
