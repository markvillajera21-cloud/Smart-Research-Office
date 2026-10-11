<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\CalendarActivityModel;
use App\Models\SchoolYearModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearcherModel;
use App\Models\ExaminerModel;
use App\Models\ResearchScheduleExaminerModel;

class CalendarActivities extends BaseController
{
    protected CalendarActivityModel $activityModel;
    protected SchoolYearModel $schoolYearModel;
    protected ResearchScheduleModel $scheduleModel;
    protected ResearcherModel $researcherModel;
    protected ExaminerModel $examinerModel;
    protected ResearchScheduleExaminerModel $scheduleExaminerModel;

    public function __construct()
    {
        $this->activityModel         = new CalendarActivityModel();
        $this->schoolYearModel        = new SchoolYearModel();
        $this->scheduleModel         = new ResearchScheduleModel();
        $this->researcherModel       = new ResearcherModel();
        $this->examinerModel         = new ExaminerModel();
        $this->scheduleExaminerModel = new ResearchScheduleExaminerModel();
    }

    public function index()
    {
        $type   = (string) $this->request->getGet('type');
        $status = (string) $this->request->getGet('status');
        $search = trim((string) $this->request->getGet('search'));

        if (!$this->activityModel->isReady()) {
            return view('admin/activities/index', [
                'title'         => 'Calendar of Activities',
                'page_title'    => 'Calendar of Activities',
                'activities'    => [],
                'typeFilter'    => $type,
                'statusFilter'  => $status,
                'search'        => $search,
                'tableMissing'  => true,
                'scheduleCountMap' => [],
            ]);
        }

        $query = $this->activityModel->orderBy('start_date', 'DESC');

        if (array_key_exists($type, CalendarActivityModel::typeLabels())) {
            $query->where('activity_type', $type);
        }

        if (array_key_exists($status, CalendarActivityModel::statusLabels())) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('academic_year', $search)
                ->groupEnd();
        }

        $activities = $query->findAll();

        // Count scheduled defenses within each activity window's date range
        $scheduleCountMap = [];
        foreach ($activities as $act) {
            $count = $this->scheduleModel
                ->where('schedule_date >=', $act['start_date'])
                ->where('schedule_date <=', $act['end_date'])
                ->where('schedule_type', $act['activity_type'])
                ->countAllResults();
            $scheduleCountMap[$act['id']] = (int) $count;
        }

        return view('admin/activities/index', [
            'title'            => 'Calendar of Activities',
            'page_title'       => 'Calendar of Activities',
            'activities'       => $activities,
            'typeFilter'       => $type,
            'statusFilter'     => $status,
            'search'           => $search,
            'tableMissing'     => false,
            'scheduleCountMap' => $scheduleCountMap,
        ]);
    }

    public function calendar()
    {
        $month = (int) ($this->request->getGet('month') ?? date('m'));
        $year  = (int) ($this->request->getGet('year') ?? date('Y'));
        $type  = (string) $this->request->getGet('type');

        $activities = $this->activityModel->getByMonth($month, $year, $type ?: null);

        // Fetch actual defense schedules for this month
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate   = date('Y-m-t', strtotime($startDate));
        $schedules = $this->scheduleModel->getByDateRange($startDate, $endDate, $type ?: null);

        // Group activity events and defense schedules by date in month
        $daysInMonth   = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $monthEvents   = [];
        $monthDefenses = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $monthEvents[$d]   = [];
            $monthDefenses[$d] = [];

            foreach ($activities as $act) {
                if ($act['start_date'] <= $dateStr && $act['end_date'] >= $dateStr) {
                    $monthEvents[$d][] = $act;
                }
            }

            foreach ($schedules as $sch) {
                if ($sch['schedule_date'] === $dateStr) {
                    $monthDefenses[$d][] = $sch;
                }
            }
        }

        $researchers = $this->researcherModel->orderBy('fullname', 'ASC')->findAll();
        $examiners   = $this->examinerModel->assignmentLists();

        return view('admin/activities/calendar', [
            'title'                      => 'Calendar of Activities & Defense Schedules',
            'page_title'                 => 'Calendar of Activities & Defense Schedules',
            'month'                      => $month,
            'year'                       => $year,
            'type'                       => $type,
            'activities'                 => $activities,
            'schedules'                  => $schedules,
            'monthEvents'                => $monthEvents,
            'monthDefenses'              => $monthDefenses,
            'researchers'                => $researchers,
            'adviserExaminers'           => $examiners['adviserExaminers'] ?? [],
            'criticExaminers'            => $examiners['criticExaminers'] ?? [],
            'panelExaminers'             => $examiners['panelExaminers'] ?? [],
            'allowedDateRangesMap'       => $this->activityModel->getAllAllowedDateRangesMap(),
            'allowedIndividualDatesMap'  => $this->activityModel->getAllowedIndividualDatesMap(),
        ]);
    }

    public function create()
    {
        $schoolYears = [];
        if (class_exists(SchoolYearModel::class)) {
            $schoolYears = $this->schoolYearModel->orderBy('name', 'DESC')->findAll();
        }

        return view('admin/activities/create', [
            'title'       => 'Add Activity Schedule',
            'page_title'  => 'Add Calendar of Activity Schedule',
            'schoolYears' => $schoolYears,
        ]);
    }

    public function store()
    {
        $startDate = (string) $this->request->getPost('start_date');
        $endDate   = (string) $this->request->getPost('end_date');

        if ($startDate > $endDate) {
            return redirect()->back()->withInput()->with('error', 'End date cannot be earlier than start date.');
        }

        $data = [
            'activity_type' => (string) $this->request->getPost('activity_type'),
            'title'         => trim((string) $this->request->getPost('title')),
            'description'   => trim((string) $this->request->getPost('description')) ?: null,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'status'        => (string) ($this->request->getPost('status') ?? CalendarActivityModel::STATUS_APPROVED),
            'academic_year' => trim((string) $this->request->getPost('academic_year')) ?: null,
            'created_by'    => (int) (session()->get('userId') ?? session()->get('user_id') ?? 1),
        ];

        if ($this->activityModel->save($data)) {
            $newId = $this->activityModel->getInsertID();
            AuditLogModel::log('CALENDAR_ACTIVITY_CREATE', 'calendar_activities', $newId, $data);
            return redirect()->to('/admin/activities')->with('success', 'Activity schedule created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->activityModel->errors());
    }

    public function edit($id)
    {
        $activity = $this->activityModel->find($id);
        if (!$activity) {
            return redirect()->to('/admin/activities')->with('error', 'Activity schedule not found.');
        }

        $schoolYears = [];
        if (class_exists(SchoolYearModel::class)) {
            $schoolYears = $this->schoolYearModel->orderBy('name', 'DESC')->findAll();
        }

        return view('admin/activities/edit', [
            'title'       => 'Edit Activity Schedule',
            'page_title'  => 'Edit Calendar of Activity Schedule',
            'activity'    => $activity,
            'schoolYears' => $schoolYears,
        ]);
    }

    public function update($id)
    {
        $activity = $this->activityModel->find($id);
        if (!$activity) {
            return redirect()->to('/admin/activities')->with('error', 'Activity schedule not found.');
        }

        $startDate = (string) $this->request->getPost('start_date');
        $endDate   = (string) $this->request->getPost('end_date');

        if ($startDate > $endDate) {
            return redirect()->back()->withInput()->with('error', 'End date cannot be earlier than start date.');
        }

        $data = [
            'id'            => (int) $id,
            'activity_type' => (string) $this->request->getPost('activity_type'),
            'title'         => trim((string) $this->request->getPost('title')),
            'description'   => trim((string) $this->request->getPost('description')) ?: null,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'status'        => (string) ($this->request->getPost('status') ?? CalendarActivityModel::STATUS_APPROVED),
            'academic_year' => trim((string) $this->request->getPost('academic_year')) ?: null,
        ];

        if ($this->activityModel->save($data)) {
            AuditLogModel::log('CALENDAR_ACTIVITY_UPDATE', 'calendar_activities', (int) $id, $data);
            return redirect()->to('/admin/activities')->with('success', 'Activity schedule updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->activityModel->errors());
    }

    public function delete($id)
    {
        $activity = $this->activityModel->find($id);
        if (!$activity) {
            return redirect()->to('/admin/activities')->with('error', 'Activity schedule not found.');
        }

        if ($this->activityModel->delete($id)) {
            AuditLogModel::log('CALENDAR_ACTIVITY_DELETE', 'calendar_activities', (int) $id, $activity);
            return redirect()->to('/admin/activities')->with('success', 'Activity schedule removed successfully.');
        }

        return redirect()->to('/admin/activities')->with('error', 'Failed to remove activity schedule.');
    }

    public function ajaxGetAllowedDates()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $map = $this->activityModel->getAllAllowedDateRangesMap();
        return $this->response->setJSON([
            'success' => true,
            'ranges'  => $map,
        ]);
    }
}
