<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearchProgressModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchEvaluationModel;
use App\Models\ResearchTitleModel;
use App\Models\User;
use App\Models\ResearcherModel;
use App\Models\StrandModel;
use App\Models\ResearchCategoryModel;
use App\Models\AuditLogModel;

class Monitoring extends BaseController
{
    protected $progressModel;
    protected $scheduleModel;
    protected $evaluationModel;
    protected $titleModel;
    protected $userModel;
    protected $researcherModel;

    public function __construct()
    {
        $this->progressModel = new ResearchProgressModel();
        $this->scheduleModel = new ResearchScheduleModel();
        $this->evaluationModel = new ResearchEvaluationModel();
        $this->titleModel = new ResearchTitleModel();
        $this->userModel = new User();
        $this->researcherModel = new ResearcherModel();
    }

    public function index()
    {
        $view = $this->request->getGet('view') ?? 'board';

        $completionRate = $this->progressModel->getCompletionRate();
        $stuckCount = count($this->progressModel->getStuck(60));
        $completedCount = $this->progressModel->where('stage', 'completed')->countAllResults(false);
        $incompleteCount = $this->progressModel->where('stage !=', 'completed')->countAllResults(false);
        $totalCount = $completedCount + $incompleteCount;

        $nextMonth = date('Y-m-d', strtotime('+30 days'));
        $today = date('Y-m-d');
        $upcomingPreOrals = (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')
                                                        ->where('research_schedules.schedule_date >=', $today)
                                                        ->where('research_schedules.schedule_date <=', $nextMonth)
                                                        ->where('research_schedules.status', 'scheduled')
                                                        ->countAllResults();

        $evalStats = $this->evaluationModel->getSummaryStats();

        $progressData = $this->progressModel->getAllWithDetails();
        $stuckData = $this->progressModel->getStuck(60);
        $completedData = $this->progressModel->getCompleted();
        $incompleteData = $this->progressModel->getIncomplete();

        $preOralSummary = [
            'total_scheduled' => (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->countAllResults(),
            'total_completed' => (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->where('research_schedules.status', 'completed')->countAllResults(),
            'passed_count' => $evalStats['passed'] ?? 0,
            'failed_count' => $evalStats['failed'] ?? 0,
            'for_compliance_count' => $evalStats['for_compliance'] ?? 0,
        ];

        $data = [
            'title' => 'Research Monitoring',
            'page_title' => 'Research Monitoring Dashboard',
            'view' => $view,
            'completionRate' => $completionRate,
            'stuckCount' => $stuckCount,
            'completedCount' => $completedCount,
            'incompleteCount' => $incompleteCount,
            'totalCount' => $totalCount,
            'upcomingPreOrals' => $upcomingPreOrals,
            'evalStats' => $evalStats,
            'preOralSummary' => $preOralSummary,
            'progressData' => $progressData,
            'stuckData' => $stuckData,
            'completedData' => $completedData,
            'incompleteData' => $incompleteData,
            'stageLabels' => ResearchProgressModel::$stageLabels,
            'researchers' => $this->researcherModel->findAll(),
            'strands' => (new StrandModel())->findAll(),
            'categories' => (new ResearchCategoryModel())->findAll(),
        ];

        return view('admin/monitoring/index', $data);
    }

    public function updateStage()
    {
        $researcherId = $this->request->getPost('researcher_id');
        $stage = $this->request->getPost('stage');
        $notes = $this->request->getPost('stage_notes');
        $validStages = array_keys(ResearchProgressModel::$stagePercentages);
        if (!in_array($stage, $validStages)) {
            return redirect()->back()->with('error', 'Invalid stage');
        }

        // Abstract Requirement: Prevent marking stage as completed without submitted abstract
        if ($stage === 'completed' && !empty($researcherId)) {
            $researcher = $this->researcherModel->find($researcherId);
            if (empty(trim((string)($researcher['abstract'] ?? '')))) {
                return redirect()->back()->with('error', 'Cannot complete research. Researchers are required to submit an approved abstract after passing the Final Defense before the project can be marked as completed.');
            }
        }

        $currentUserId = session()->get('user_id') ?: (session()->get('userId') ?: 1);
        if ($this->progressModel->updateStage($researcherId, $stage, $notes, $currentUserId)) {
            AuditLogModel::log('PROGRESS_STAGE_UPDATE', 'research_progress', null, [
                'researcher_id' => $researcherId,
                'stage' => $stage,
                'stage_notes' => $notes,
            ]);
            return redirect()->to('/admin/monitoring')->with('success', 'Progress updated');
        }
        return redirect()->back()->with('error', 'Failed to update');
    }

    public function completed()
    {
        $search = $this->request->getGet('search');
        $strandId = $this->request->getGet('strand_id');
        $completedData = $this->progressModel->getCompleted();
        if ($search) {
            $completedData = array_filter($completedData, fn($r) => stripos($r['researcher_name'] ?? '', $search) !== false || stripos($r['student_name'] ?? '', $search) !== false);
        }
        $data = [
            'title' => 'Completed Researches',
            'page_title' => 'Completed Research List',
            'view' => 'completed',
            'completedData' => $completedData,
            'strands' => (new StrandModel())->findAll(),
            'categories' => (new ResearchCategoryModel())->findAll(),
        ];
        return view('admin/monitoring/completed_list', $data);
    }

    public function incomplete()
    {
        $search = $this->request->getGet('search');
        $incompleteData = $this->progressModel->getIncomplete();
        if ($search) {
            $incompleteData = array_filter($incompleteData, fn($r) => stripos($r['researcher_name'] ?? '', $search) !== false || stripos($r['student_name'] ?? '', $search) !== false);
        }
        $stuckData = $this->progressModel->getStuck(60);
        $data = [
            'title' => 'Incomplete Researches',
            'page_title' => 'Incomplete Research List',
            'view' => 'incomplete',
            'incompleteData' => $incompleteData,
            'stuckData' => $stuckData,
            'stageLabels' => ResearchProgressModel::$stageLabels,
            'strands' => (new StrandModel())->findAll(),
            'categories' => (new ResearchCategoryModel())->findAll(),
        ];
        return view('admin/monitoring/incomplete_list', $data);
    }

    public function preOralSummary()
    {
        $evalStats = $this->evaluationModel->getSummaryStats();
        $pendingCompliance = $this->evaluationModel->getPendingCompliance();
        $overdueCompliance = $this->evaluationModel->getOverdueCompliance();

        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $monthlyPreOrals = (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')
                                                       ->where('research_schedules.schedule_date >=', $monthStart)
                                                       ->where('research_schedules.schedule_date <=', $monthEnd)
                                                       ->orderBy('research_schedules.schedule_date', 'DESC')
                                                       ->findAll();

        $preOralSummary = [
            'total_scheduled' => (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->countAllResults(),
            'total_completed' => (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->where('research_schedules.status', 'completed')->countAllResults(),
            'passed_count' => $evalStats['passed'] ?? 0,
            'failed_count' => $evalStats['failed'] ?? 0,
            'for_compliance_count' => $evalStats['for_compliance'] ?? 0,
        ];

        // Retrieve the full list of research titles scheduled for Pre-Oral Defense
        $preOralTitles = (new ResearchScheduleModel())->getPreOralList();

        $data = [
            'title' => 'Pre-Oral Summary',
            'page_title' => 'Summary Report of Pre-Oral Schedules',
            'view' => 'pre_oral',
            'evalStats' => $evalStats,
            'pendingCompliance' => $pendingCompliance,
            'overdueCompliance' => $overdueCompliance,
            'monthlyPreOrals' => $monthlyPreOrals,
            'preOralSummary' => $preOralSummary,
            'preOralTitles' => $preOralTitles,
        ];
        return view('admin/monitoring/pre_oral_summary', $data);
    }

    public function preOralList()
    {
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? '';

        $preOralTitles = (new ResearchScheduleModel())->getPreOralList(
            $search ?: null,
            $status ?: null
        );

        // Count by status for summary badges
        $totalAll       = (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->countAllResults();
        $totalScheduled = (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->where('research_schedules.status', 'scheduled')->countAllResults();
        $totalCompleted = (new ResearchScheduleModel())->where('research_schedules.schedule_type', 'pre_oral')->where('research_schedules.status', 'completed')->countAllResults();

        $data = [
            'title'          => 'Pre-Oral Research Titles',
            'page_title'     => 'Pre-Oral Defense Research List',
            'view'           => 'pre_oral_list',
            'preOralTitles'  => $preOralTitles,
            'search'         => $search,
            'statusFilter'   => $status,
            'totalAll'       => $totalAll,
            'totalScheduled' => $totalScheduled,
            'totalCompleted' => $totalCompleted,
        ];

        return view('admin/monitoring/pre_oral_list', $data);
    }
}
