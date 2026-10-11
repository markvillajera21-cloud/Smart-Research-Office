<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\ResearchEvaluationModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchTitleModel;
use App\Models\ResearchProgressModel;
use App\Models\AuditLogModel;
use App\Models\User;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $scheduleModel;
    protected $titleModel;
    protected $evaluationModel;
    protected $progressModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->scheduleModel = new ResearchScheduleModel();
        $this->titleModel = new ResearchTitleModel();
        $this->evaluationModel = new ResearchEvaluationModel();
        $this->progressModel = new ResearchProgressModel();
    }

    protected function teacherId()
    {
        return session()->get('user_id');
    }

    public function index()
    {
        $teacherId = $this->teacherId();
        $students = $this->userModel->getAssignedStudents($teacherId);
        $schedules = $this->scheduleModel->getInstructorSchedules($teacherId);
        $titles = $this->titleModel->getInstructorTitles($teacherId);
        $evaluations = $this->evaluationModel->getInstructorEvaluations($teacherId);

        $upcoming = array_filter($schedules, function ($schedule) {
            return ($schedule['status'] ?? '') === 'scheduled'
                && strtotime(($schedule['schedule_date'] ?? '') . ' ' . ($schedule['schedule_time'] ?? '00:00:00')) >= time();
        });

        return view('teacher/dashboard', [
            'title' => 'Teacher Dashboard',
            'page_title' => 'Teacher Dashboard',
            'students' => $students,
            'schedules' => $schedules,
            'upcomingSchedules' => $upcoming,
            'titles' => $titles,
            'evaluations' => $evaluations,
        ]);
    }

    public function students()
    {
        return view('teacher/students', [
            'title' => 'My Students',
            'page_title' => 'Assigned Students',
            'students' => $this->userModel->getAssignedStudents($this->teacherId()),
        ]);
    }

    public function schedules()
    {
        return view('teacher/schedules', [
            'title' => 'My Schedules',
            'page_title' => 'My Research Schedules',
            'schedules' => $this->scheduleModel->getInstructorSchedules($this->teacherId()),
        ]);
    }

    public function titles()
    {
        return view('teacher/titles', [
            'title' => 'Student Titles',
            'page_title' => 'Assigned Research Titles',
            'titles' => $this->titleModel->getInstructorTitles($this->teacherId()),
        ]);
    }

    public function updateTitleStatus($id)
    {
        $teacherId = $this->teacherId();

        // Verify title exists and is assigned to this teacher
        $title = $this->titleModel->select('research_titles.*, users.assigned_instructor_id')
            ->join('users', 'users.id = research_titles.student_id', 'left')
            ->where('research_titles.id', $id)
            ->groupStart()
                ->where('research_titles.instructor_id', $teacherId)
                ->orWhere('users.assigned_instructor_id', $teacherId)
            ->groupEnd()
            ->first();

        if (!$title) {
            return redirect()->back()->with('error', 'Research title not found or not assigned to you.');
        }

        $newStatus = trim((string) $this->request->getPost('status'));
        $notes = trim((string) $this->request->getPost('notes'));

        $allowedStatuses = ['pending', 'approved', 'for_revision', 'rejected', 'completed'];
        if (!in_array($newStatus, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'Invalid status selected.');
        }

        $oldStatus = $title['status'] ?? 'pending';
        $updateData = [
            'status' => $newStatus,
        ];

        if ($newStatus === 'approved') {
            $updateData['approved_at'] = date('Y-m-d H:i:s');
            if (!empty($notes)) {
                $updateData['revision_notes'] = $notes;
            }
        } elseif ($newStatus === 'for_revision') {
            $updateData['revision_notes'] = $notes;
        } elseif ($newStatus === 'rejected') {
            $updateData['rejection_reason'] = $notes;
        } elseif ($newStatus === 'completed') {
            if (!empty($notes)) {
                $updateData['revision_notes'] = $notes;
            }
        }

        if ($this->titleModel->update($id, $updateData)) {
            // Update research progress if linked to researcher record
            if (!empty($title['researcher_id'])) {
                if ($newStatus === 'approved') {
                    $this->progressModel->updateStage($title['researcher_id'], 'title_approved', 'Title approved by Research Instructor', $teacherId);
                } elseif ($newStatus === 'completed') {
                    $this->progressModel->updateStage($title['researcher_id'], 'completed', 'Completed by Research Instructor', $teacherId);
                }
            }

            // Log status change in Audit Trail
            AuditLogModel::log('TEACHER_TITLE_STATUS_UPDATE', 'research_titles', $id, [
                'title'       => $title['title'],
                'student_id'  => $title['student_id'],
                'teacher_id'  => $teacherId,
                'old_status'  => $oldStatus,
                'new_status'  => $newStatus,
                'notes'       => $notes,
            ]);

            return redirect()->to('/teacher/titles')->with('success', 'Research title status updated successfully to ' . ucwords(str_replace('_', ' ', $newStatus)) . '.');
        }

        return redirect()->back()->with('error', 'Failed to update research title status.');
    }

    public function evaluations()
    {
        return view('teacher/evaluations', [
            'title' => 'Student Evaluations',
            'page_title' => 'Assigned Evaluations',
            'evaluations' => $this->evaluationModel->getInstructorEvaluations($this->teacherId()),
        ]);
    }
}
