<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearchScheduleModel;
use App\Models\User;
use App\Models\ResearcherModel;
use App\Models\DesignationModel;
use App\Models\StrandModel;
use App\Models\AuditLogModel;
use App\Models\ExaminerModel;
use App\Models\ResearchScheduleExaminerModel;
use App\Models\CalendarActivityModel;

class Scheduling extends BaseController
{
    protected $scheduleModel;
    protected $userModel;
    protected $researcherModel;
    protected $examinerModel;
    protected $scheduleExaminerModel;
    protected $activityModel;

    public function __construct()
    {
        $this->scheduleModel         = new ResearchScheduleModel();
        $this->userModel             = new User();
        $this->researcherModel       = new ResearcherModel();
        $this->examinerModel         = new ExaminerModel();
        $this->scheduleExaminerModel = new ResearchScheduleExaminerModel();
        $this->activityModel         = new CalendarActivityModel();
    }

    protected function validateScheduleDate(?string $date, ?string $type): ?string
    {
        if (empty($date) || empty($type)) {
            return 'Schedule date and type are required.';
        }

        if (!$this->activityModel->isReady()) {
            return null;
        }

        if (!$this->activityModel->isDateAllowed($date, $type)) {
            $typeLabel = CalendarActivityModel::typeLabel($type);
            $ranges    = $this->activityModel->getAllowedDateRanges($type);
            if (empty($ranges)) {
                return "No approved defense date is available for {$typeLabel}.";
            }

            $rangeStrings = array_map(function($r) {
                return date('M d, Y', strtotime($r['start_date'])) . ' to ' . date('M d, Y', strtotime($r['end_date']));
            }, $ranges);
            $allowedList = implode('; ', $rangeStrings);

            return "The selected date (" . date('M d, Y', strtotime($date)) . ") is not included in the approved Calendar of Activities schedule for {$typeLabel}. Approved dates: {$allowedList}.";
        }

        return null;
    }

    protected function examinerAssignData(int $scheduleId = 0): array
    {
        $assigned = $this->scheduleExaminerModel->getAssignedMap($scheduleId);
        return array_merge($this->examinerModel->assignmentLists(), [
            'selectedAdviserId' => $assigned['adviser'],
            'selectedCriticId'  => $assigned['critic'],
            'selectedPanelIds'  => $assigned['panel_member'],
        ]);
    }

    protected function applyExaminerPool(int $scheduleId, array $data): array
    {
        $formatted = $this->scheduleExaminerModel->applyFromRequest($scheduleId, $this->request);
        if ($formatted !== null) {
            $data['panelists'] = $formatted;
            $this->scheduleModel->update($scheduleId, ['panelists' => $formatted]);
        }
        return $data;
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
        $type = $this->request->getGet('type');
        $studentSearch = $this->request->getGet('student_search');

        $schedules = $this->scheduleModel->getByDateRange($startDate, $endDate, $type);

        if ($studentSearch) {
            $schedules = array_filter($schedules, function($s) use ($studentSearch) {
                return stripos($s['student_name'] ?? '', $studentSearch) !== false
                    || stripos($s['researcher_name'] ?? '', $studentSearch) !== false;
            });
        }

        $data = array_merge($this->examinerAssignData(0), [
            'title'                      => 'Research Scheduling',
            'page_title'                 => 'Research Scheduling Module',
            'schedules'                  => $schedules,
            'startDate'                  => $startDate,
            'endDate'                    => $endDate,
            'type'                       => $type,
            'studentSearch'              => $studentSearch,
            'students'                   => $this->userModel->getStudents(),
            'instructors'                => $this->userModel->getInstructors(),
            'researchers'                => $this->researcherModel->findAll(),
            'allowedDateRangesMap'       => $this->activityModel->getAllAllowedDateRangesMap(),
            'allowedIndividualDatesMap'  => $this->activityModel->getAllowedIndividualDatesMap(),
            'approvedActivities'         => $this->activityModel->getApprovedActivities(),
        ]);

        return view('admin/scheduling/index', $data);
    }

    public function calendar()
    {
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        $startDate = date("$year-$month-01");
        $endDate = date("$year-$month-t");
        $schedules = $this->scheduleModel->getByDateRange($startDate, $endDate);

        $calendarEvents = [];
        foreach ($schedules as $s) {
            $day = date('j', strtotime($s['schedule_date']));
            if (!isset($calendarEvents[$day])) $calendarEvents[$day] = [];
            $calendarEvents[$day][] = $s;
        }

        $data = array_merge($this->examinerAssignData(0), [
            'title'                      => 'Calendar View',
            'page_title'                 => 'Research Activities Calendar',
            'schedules'                  => $schedules,
            'calendarEvents'             => $calendarEvents,
            'month'                      => (int)$month,
            'year'                       => (int)$year,
            'researchers'                => $this->researcherModel->findAll(),
            'allowedDateRangesMap'       => $this->activityModel->getAllAllowedDateRangesMap(),
            'allowedIndividualDatesMap'  => $this->activityModel->getAllowedIndividualDatesMap(),
            'approvedActivities'         => $this->activityModel->getByMonth((int)$month, (int)$year),
        ]);

        return view('admin/scheduling/calendar', $data);
    }

    public function store()
    {
        $scheduleDate = (string) $this->request->getPost('schedule_date');
        $scheduleType = (string) $this->request->getPost('schedule_type');

        // Enforce approved Calendar of Activities window
        $dateError = $this->validateScheduleDate($scheduleDate, $scheduleType);
        if ($dateError !== null) {
            return redirect()->back()->withInput()->with('error', $dateError);
        }

        $data = [
            'researcher_id'   => $this->request->getPost('researcher_id') ?: null,
            'student_id'      => $this->request->getPost('student_id') ?: null,
            'instructor_id'   => $this->request->getPost('instructor_id') ?: null,
            'schedule_type'   => $scheduleType,
            'schedule_title'  => $this->request->getPost('schedule_title'),
            'schedule_date'   => $scheduleDate,
            'schedule_time'   => $this->request->getPost('schedule_time'),
            'venue'           => $this->request->getPost('venue'),
            'panelists'       => $this->request->getPost('panelists'),
            'status'          => $this->request->getPost('status') ?? 'scheduled',
            'created_by'      => session()->get('user_id'),
        ];

        if ($this->scheduleModel->insert($data)) {
            $newId = $this->scheduleModel->getInsertID();
            $data = $this->applyExaminerPool((int) $newId, $data);
            AuditLogModel::log('SCHEDULE_CREATE', 'research_schedules', $newId, $data);
            return redirect()->to('/admin/scheduling')->with('success', 'Schedule created successfully within approved activity window.');
        }
        return redirect()->back()->withInput()->with('error', 'Failed to create schedule');
    }

    public function edit($id)
    {
        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            return redirect()->to('/admin/scheduling')->with('error', 'Schedule not found');
        }

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $schedules = $this->scheduleModel->getByDateRange($startDate, $endDate);

        $data = array_merge($this->examinerAssignData((int) $id), [
            'title'                      => 'Edit Schedule',
            'page_title'                 => 'Edit Research Schedule',
            'schedule'                   => $schedule,
            'schedules'                  => $schedules,
            'startDate'                  => $startDate,
            'endDate'                    => $endDate,
            'type'                       => null,
            'studentSearch'              => null,
            'students'                   => $this->userModel->getStudents(),
            'instructors'                => $this->userModel->getInstructors(),
            'researchers'                => $this->researcherModel->findAll(),
            'allowedDateRangesMap'       => $this->activityModel->getAllAllowedDateRangesMap(),
            'allowedIndividualDatesMap'  => $this->activityModel->getAllowedIndividualDatesMap(),
            'approvedActivities'         => $this->activityModel->getApprovedActivities(),
        ]);

        return view('admin/scheduling/index', $data);
    }

    public function update($id)
    {
        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            return redirect()->to('/admin/scheduling')->with('error', 'Schedule not found');
        }

        $scheduleDate = (string) $this->request->getPost('schedule_date');
        $scheduleType = (string) $this->request->getPost('schedule_type');

        // Enforce approved Calendar of Activities window
        $dateError = $this->validateScheduleDate($scheduleDate, $scheduleType);
        if ($dateError !== null) {
            return redirect()->back()->withInput()->with('error', $dateError);
        }

        $data = [
            'researcher_id'   => $this->request->getPost('researcher_id') ?: null,
            'student_id'      => $this->request->getPost('student_id') ?: null,
            'instructor_id'   => $this->request->getPost('instructor_id') ?: null,
            'schedule_type'   => $scheduleType,
            'schedule_title'  => $this->request->getPost('schedule_title'),
            'schedule_date'   => $scheduleDate,
            'schedule_time'   => $this->request->getPost('schedule_time'),
            'venue'           => $this->request->getPost('venue'),
            'panelists'       => $this->request->getPost('panelists'),
            'status'          => $this->request->getPost('status') ?? 'scheduled',
        ];

        if ($this->scheduleModel->update($id, $data)) {
            $data = $this->applyExaminerPool((int) $id, $data);
            AuditLogModel::log('SCHEDULE_UPDATE', 'research_schedules', $id, $data);
            return redirect()->to('/admin/scheduling')->with('success', 'Schedule updated successfully within approved activity window.');
        }
        return redirect()->back()->withInput()->with('error', 'Failed to update schedule');
    }

    public function changeCommittee($id)
    {
        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            return redirect()->to('/admin/scheduling')->with('error', 'Schedule not found');
        }

        $formatted = $this->scheduleExaminerModel->applyFromRequest((int) $id, $this->request);
        $panelists = $formatted !== null ? $formatted : trim((string) $this->request->getPost('panelists'));
        if ($panelists === '') {
            return redirect()->back()->withInput()->with('error', 'Guidance committee is required');
        }

        if ($this->scheduleModel->update($id, ['panelists' => $panelists])) {
            AuditLogModel::log('SCHEDULE_COMMITTEE_CHANGE', 'research_schedules', $id, [
                'previous_panelists' => $schedule['panelists'] ?? null,
                'panelists' => $panelists,
            ]);
            return redirect()->to('/admin/scheduling')->with('success', 'Guidance committee changed successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to change guidance committee');
    }

    public function delete($id)
    {
        $existing = $this->scheduleModel->find($id);
        if ($this->scheduleModel->delete($id)) {
            AuditLogModel::log('SCHEDULE_DELETE', 'research_schedules', $id, $existing ?? ['id' => $id]);
            return redirect()->to('/admin/scheduling')->with('success', 'Schedule deleted successfully');
        }
        return redirect()->to('/admin/scheduling')->with('error', 'Failed to delete schedule');
    }

    public function ajaxGetEvent($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $schedule = $this->scheduleModel->select('research_schedules.*, users.username as student_name, u2.username as instructor_name, researchers.fullname as researcher_name')
                                        ->join('users', 'users.id = research_schedules.student_id', 'left')
                                        ->join('users u2', 'u2.id = research_schedules.instructor_id', 'left')
                                        ->join('researchers', 'researchers.id = research_schedules.researcher_id', 'left')
                                        ->find($id);
        return $this->response->setJSON($schedule);
    }
}
