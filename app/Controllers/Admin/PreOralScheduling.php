<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchTitleModel;
use App\Models\ResearcherModel;
use App\Models\User;
use App\Models\StrandModel;
use App\Models\CourseModel;
use App\Models\ResearchCategoryModel;
use App\Models\CalendarActivityModel;
use App\Models\ExaminerModel;
use App\Models\ResearchScheduleExaminerModel;
use App\Models\AuditLogModel;
use App\Models\ResearchProgressModel;

class PreOralScheduling extends BaseController
{
    protected $scheduleModel;
    protected $titleModel;
    protected $researcherModel;
    protected $userModel;
    protected $strandModel;
    protected $categoryModel;
    protected $activityModel;
    protected $examinerModel;
    protected $scheduleExaminerModel;
    protected $progressModel;

    public function __construct()
    {
        $this->scheduleModel         = new ResearchScheduleModel();
        $this->titleModel            = new ResearchTitleModel();
        $this->researcherModel       = new ResearcherModel();
        $this->userModel             = new User();
        $this->strandModel           = new StrandModel();
        $this->categoryModel         = new ResearchCategoryModel();
        $this->activityModel         = new CalendarActivityModel();
        $this->examinerModel         = new ExaminerModel();
        $this->scheduleExaminerModel = new ResearchScheduleExaminerModel();
        $this->progressModel         = new ResearchProgressModel();
    }

    /**
     * Validate that the given schedule date falls inside an approved Calendar of Activities window for Pre-Oral Defense.
     */
    protected function validateScheduleDate(?string $date): ?string
    {
        if (empty($date)) {
            return 'Pre-Oral schedule date is required.';
        }

        if (!$this->activityModel->isReady()) {
            return null;
        }

        if (!$this->activityModel->isDateAllowed($date, CalendarActivityModel::TYPE_PRE_ORAL)) {
            $typeLabel = CalendarActivityModel::typeLabel(CalendarActivityModel::TYPE_PRE_ORAL);
            $ranges    = $this->activityModel->getAllowedDateRanges(CalendarActivityModel::TYPE_PRE_ORAL);
            if (empty($ranges)) {
                return "No approved defense dates are currently configured in the Calendar of Activities for {$typeLabel}. Please add and approve activities first.";
            }

            $rangeStrings = array_map(function($r) {
                return date('M d, Y', strtotime($r['start_date'])) . ' to ' . date('M d, Y', strtotime($r['end_date']));
            }, $ranges);
            $allowedList = implode('; ', $rangeStrings);

            return "The selected date (" . date('M d, Y', strtotime($date)) . ") is not within the approved Calendar of Activities for {$typeLabel}. Approved defense window(s): {$allowedList}.";
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

    /**
     * Main Pre-Oral Scheduling Page
     */
    public function index()
    {
        $strandFilter   = $this->request->getGet('strand_id');
        $statusFilter   = $this->request->getGet('schedule_status'); // 'scheduled', 'unscheduled', 'completed'
        $search         = trim((string) $this->request->getGet('search'));
        $editScheduleId = (int) ($this->request->getGet('edit') ?? 0);

        // Fetch all approved research titles with full details
        $approvedTitles = $this->getApprovedTitlesWithSchedules($strandFilter, $statusFilter, $search);

        // Calculate metrics
        $totalApproved = count($approvedTitles);
        $totalScheduled = 0;
        $totalUnscheduled = 0;
        $totalCompleted = 0;

        foreach ($approvedTitles as $item) {
            if (!empty($item['schedule_id'])) {
                if ($item['schedule_status'] === 'completed') {
                    $totalCompleted++;
                } else {
                    $totalScheduled++;
                }
            } else {
                $totalUnscheduled++;
            }
        }

        // Fetch approved calendar dates specifically for Pre-Oral Defense
        $allowedPreOralDates  = $this->activityModel->getAllowedIndividualDatesMap()[CalendarActivityModel::TYPE_PRE_ORAL] ?? [];
        $allowedDateRanges    = $this->activityModel->getAllowedDateRanges(CalendarActivityModel::TYPE_PRE_ORAL);

        // If editing a specific schedule, load its details
        $activeSchedule = null;
        if ($editScheduleId > 0) {
            $activeSchedule = $this->scheduleModel->find($editScheduleId);
        }

        $examinerData = $this->examinerAssignData($editScheduleId);

        $data = array_merge($examinerData, [
            'title'                 => 'Pre-Oral Scheduling',
            'page_title'            => 'Pre-Oral Defense Scheduling',
            'approvedTitles'        => $approvedTitles,
            'totalApproved'         => $totalApproved,
            'totalScheduled'        => $totalScheduled,
            'totalUnscheduled'      => $totalUnscheduled,
            'totalCompleted'        => $totalCompleted,
            'strands'               => $this->strandModel->findAll(),
            'categories'            => $this->categoryModel->findAll(),
            'strandFilter'          => $strandFilter,
            'statusFilter'          => $statusFilter,
            'search'                => $search,
            'allowedPreOralDates'   => $allowedPreOralDates,
            'allowedDateRanges'     => $allowedDateRanges,
            'activeSchedule'        => $activeSchedule,
            'editScheduleId'        => $editScheduleId,
            'students'              => $this->userModel->getStudents(),
            'instructors'           => $this->userModel->getInstructors(),
            'researchers'           => $this->researcherModel->findAll(),
        ]);

        return view('admin/pre_oral_scheduling/index', $data);
    }

    /**
     * Get approved research titles merged with their Pre-Oral schedules
     */
    protected function getApprovedTitlesWithSchedules($strandId = null, $statusFilter = null, $search = null): array
    {
        $db = \Config\Database::connect();

        // 1. Query approved titles from research_titles table
        $builder = $db->table('research_titles')
            ->select('
                research_titles.id AS title_id,
                research_titles.title AS research_title,
                research_titles.abstract,
                research_titles.status AS title_status,
                research_titles.approved_at,
                research_titles.researcher_id,
                research_titles.student_id,
                research_titles.instructor_id,
                research_titles.strand_id,
                research_titles.category_id,
                researchers.fullname AS researcher_name,
                researchers.author AS researcher_author,
                researchers.strand_degree_program,
                users.username AS student_name,
                users.email AS student_email,
                instructors.username AS instructor_name,
                strands.name AS strand_name,
                courses.name AS course_name,
                research_categories.name AS category_name
            ')
            ->join('researchers', 'researchers.id = research_titles.researcher_id', 'left')
            ->join('users', 'users.id = research_titles.student_id', 'left')
            ->join('users AS instructors', 'instructors.id = research_titles.instructor_id', 'left')
            ->join('strands', 'strands.id = research_titles.strand_id', 'left')
            ->join('courses', 'courses.id = researchers.course_id', 'left')
            ->join('research_categories', 'research_categories.id = research_titles.category_id', 'left')
            ->where('research_titles.status', 'approved');

        if (!empty($strandId)) {
            $builder->where('research_titles.strand_id', $strandId);
        }

        $records = $builder->get()->getResultArray();
        $seenTitleKeys = [];
        $results = [];

        // Also fetch all Pre-Oral defense schedules
        $schedules = $this->scheduleModel
            ->where('schedule_type', CalendarActivityModel::TYPE_PRE_ORAL)
            ->orderBy('schedule_date', 'ASC')
            ->orderBy('schedule_time', 'ASC')
            ->findAll();

        $scheduleByResearcher = [];
        $scheduleByStudent = [];
        $scheduleByTitleMatch = [];

        foreach ($schedules as $sch) {
            if (!empty($sch['researcher_id'])) {
                $scheduleByResearcher[$sch['researcher_id']] = $sch;
            }
            if (!empty($sch['student_id'])) {
                $scheduleByStudent[$sch['student_id']] = $sch;
            }
            if (!empty($sch['schedule_title'])) {
                $cleanTitle = strtolower(trim(str_ireplace('Pre-Oral Defense:', '', $sch['schedule_title'])));
                $scheduleByTitleMatch[$cleanTitle] = $sch;
            }
        }

        foreach ($records as $r) {
            $key = 'title_' . $r['title_id'];
            $seenTitleKeys[$key] = true;

            // Match schedule
            $matchedSchedule = null;
            if (!empty($r['researcher_id']) && isset($scheduleByResearcher[$r['researcher_id']])) {
                $matchedSchedule = $scheduleByResearcher[$r['researcher_id']];
            } elseif (!empty($r['student_id']) && isset($scheduleByStudent[$r['student_id']])) {
                $matchedSchedule = $scheduleByStudent[$r['student_id']];
            } else {
                $cleanTitle = strtolower(trim($r['research_title']));
                if (isset($scheduleByTitleMatch[$cleanTitle])) {
                    $matchedSchedule = $scheduleByTitleMatch[$cleanTitle];
                }
            }

            // Determine author/researcher display
            $authorName = !empty($r['researcher_name']) 
                ? $r['researcher_name'] 
                : (!empty($r['researcher_author']) ? $r['researcher_author'] : (!empty($r['student_name']) ? $r['student_name'] : 'N/A'));

            $strandCourse = !empty($r['strand_name']) 
                ? $r['strand_name'] . (!empty($r['course_name']) ? ' / ' . $r['course_name'] : '')
                : (!empty($r['strand_degree_program']) ? $r['strand_degree_program'] : (!empty($r['course_name']) ? $r['course_name'] : '—'));

            $entry = [
                'type'                   => 'research_title',
                'title_id'               => (int) $r['title_id'],
                'researcher_id'          => !empty($r['researcher_id']) ? (int)$r['researcher_id'] : null,
                'student_id'             => !empty($r['student_id']) ? (int)$r['student_id'] : null,
                'instructor_id'          => !empty($r['instructor_id']) ? (int)$r['instructor_id'] : null,
                'research_title'         => $r['research_title'],
                'author_name'            => $authorName,
                'student_name'           => $r['student_name'] ?? null,
                'instructor_name'        => $r['instructor_name'] ?? null,
                'course_strand'          => $strandCourse,
                'strand_name'            => $r['strand_name'] ?? '',
                'category_name'          => $r['category_name'] ?? '',
                'approved_at'            => $r['approved_at'],
                'schedule_id'            => $matchedSchedule ? (int)$matchedSchedule['id'] : null,
                'schedule_title'         => $matchedSchedule['schedule_title'] ?? null,
                'schedule_date'          => $matchedSchedule['schedule_date'] ?? null,
                'schedule_time'          => $matchedSchedule['schedule_time'] ?? null,
                'schedule_venue'         => $matchedSchedule['venue'] ?? null,
                'schedule_status'        => $matchedSchedule['status'] ?? 'unscheduled',
                'panelists'              => $matchedSchedule['panelists'] ?? null,
            ];

            $results[] = $entry;
        }

        // 2. Also check if there are researchers in `researchers` table with approved titles not captured in `research_titles`
        $resBuilder = $db->table('researchers')
            ->select('
                researchers.id AS researcher_id,
                researchers.fullname,
                researchers.author,
                researchers.approved_research_title,
                researchers.strand_degree_program,
                researchers.user_id,
                researchers.strand_id,
                researchers.course_id,
                researchers.category_id,
                users.username AS student_name,
                users.email AS student_email,
                strands.name AS strand_name,
                courses.name AS course_name,
                research_categories.name AS category_name
            ')
            ->join('users', 'users.id = researchers.user_id', 'left')
            ->join('strands', 'strands.id = researchers.strand_id', 'left')
            ->join('courses', 'courses.id = researchers.course_id', 'left')
            ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
            ->where('researchers.approved_research_title IS NOT NULL')
            ->where('researchers.approved_research_title !=', '');

        if (!empty($strandId)) {
            $resBuilder->where('researchers.strand_id', $strandId);
        }

        $extraResearchers = $resBuilder->get()->getResultArray();
        foreach ($extraResearchers as $res) {
            // check if already added
            $alreadyAdded = false;
            foreach ($results as $item) {
                if (!empty($item['researcher_id']) && (int)$item['researcher_id'] === (int)$res['researcher_id']) {
                    $alreadyAdded = true;
                    break;
                }
                if (strtolower(trim($item['research_title'])) === strtolower(trim($res['approved_research_title']))) {
                    $alreadyAdded = true;
                    break;
                }
            }

            if (!$alreadyAdded) {
                $matchedSchedule = $scheduleByResearcher[$res['researcher_id']] ?? ($scheduleByStudent[$res['user_id']] ?? null);
                $authorName = !empty($res['fullname']) ? $res['fullname'] : (!empty($res['author']) ? $res['author'] : ($res['student_name'] ?? 'N/A'));
                $strandCourse = !empty($res['strand_name']) 
                    ? $res['strand_name'] . (!empty($res['course_name']) ? ' / ' . $res['course_name'] : '')
                    : (!empty($res['strand_degree_program']) ? $res['strand_degree_program'] : (!empty($res['course_name']) ? $res['course_name'] : '—'));

                $results[] = [
                    'type'                   => 'researcher',
                    'title_id'               => null,
                    'researcher_id'          => (int) $res['researcher_id'],
                    'student_id'             => !empty($res['user_id']) ? (int)$res['user_id'] : null,
                    'instructor_id'          => null,
                    'research_title'         => $res['approved_research_title'],
                    'author_name'            => $authorName,
                    'student_name'           => $res['student_name'] ?? null,
                    'instructor_name'        => null,
                    'course_strand'          => $strandCourse,
                    'strand_name'            => $res['strand_name'] ?? '',
                    'category_name'          => $res['category_name'] ?? '',
                    'approved_at'            => null,
                    'schedule_id'            => $matchedSchedule ? (int)$matchedSchedule['id'] : null,
                    'schedule_title'         => $matchedSchedule['schedule_title'] ?? null,
                    'schedule_date'          => $matchedSchedule['schedule_date'] ?? null,
                    'schedule_time'          => $matchedSchedule['schedule_time'] ?? null,
                    'schedule_venue'         => $matchedSchedule['venue'] ?? null,
                    'schedule_status'        => $matchedSchedule['status'] ?? 'unscheduled',
                    'panelists'              => $matchedSchedule['panelists'] ?? null,
                ];
            }
        }

        // Apply Status Filter
        if (!empty($statusFilter)) {
            $results = array_filter($results, function($item) use ($statusFilter) {
                if ($statusFilter === 'scheduled') {
                    return !empty($item['schedule_id']) && $item['schedule_status'] === 'scheduled';
                } elseif ($statusFilter === 'completed') {
                    return !empty($item['schedule_id']) && $item['schedule_status'] === 'completed';
                } elseif ($statusFilter === 'unscheduled') {
                    return empty($item['schedule_id']) || $item['schedule_status'] === 'unscheduled';
                }
                return true;
            });
        }

        // Apply Search Keyword Filter
        if (!empty($search)) {
            $results = array_filter($results, function($item) use ($search) {
                return stripos($item['research_title'] ?? '', $search) !== false
                    || stripos($item['author_name'] ?? '', $search) !== false
                    || stripos($item['student_name'] ?? '', $search) !== false
                    || stripos($item['course_strand'] ?? '', $search) !== false
                    || stripos($item['schedule_venue'] ?? '', $search) !== false;
            });
        }

        return array_values($results);
    }

    /**
     * Store a new Pre-Oral Defense Schedule
     */
    public function store()
    {
        $scheduleDate = (string) $this->request->getPost('schedule_date');

        // Enforce approved Calendar of Activities window strictly
        $dateError = $this->validateScheduleDate($scheduleDate);
        if ($dateError !== null) {
            return redirect()->back()->withInput()->with('error', $dateError);
        }

        $researcherId = $this->request->getPost('researcher_id') ?: null;
        $studentId    = $this->request->getPost('student_id') ?: null;
        $instructorId = $this->request->getPost('instructor_id') ?: null;
        $titleText    = trim((string) $this->request->getPost('schedule_title'));

        if (empty($titleText)) {
            $titleText = 'Pre-Oral Defense Schedule';
        }

        $data = [
            'researcher_id'   => $researcherId,
            'student_id'      => $studentId,
            'instructor_id'   => $instructorId,
            'schedule_type'   => CalendarActivityModel::TYPE_PRE_ORAL,
            'schedule_title'  => $titleText,
            'schedule_date'   => $scheduleDate,
            'schedule_time'   => $this->request->getPost('schedule_time') ?: '09:00:00',
            'venue'           => $this->request->getPost('venue') ?: 'Research Office / Zoom',
            'panelists'       => $this->request->getPost('panelists') ?: null,
            'status'          => $this->request->getPost('status') ?? 'scheduled',
            'created_by'      => session()->get('user_id'),
        ];

        if ($this->scheduleModel->insert($data)) {
            $newId = $this->scheduleModel->getInsertID();
            $data = $this->applyExaminerPool((int) $newId, $data);

            // Sync with researcher table if researcher_id is present
            if ($researcherId) {
                $this->researcherModel->update($researcherId, [
                    'pre_oral_defense_date' => $scheduleDate
                ]);

                // Update progress tracking stage to pre_oral_scheduled if applicable
                $this->progressModel->updateStage(
                    $researcherId,
                    'pre_oral_scheduled',
                    'Pre-Oral Defense scheduled for ' . date('M d, Y', strtotime($scheduleDate)),
                    session()->get('user_id')
                );
            }

            AuditLogModel::log('PRE_ORAL_SCHEDULE_CREATE', 'research_schedules', $newId, $data);

            return redirect()->to('/admin/pre-oral-scheduling')->with('success', 'Pre-Oral Defense schedule has been created successfully within the approved Calendar of Activities.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save Pre-Oral schedule. Please check inputs.');
    }

    /**
     * Update an existing Pre-Oral Defense Schedule
     */
    public function update($id)
    {
        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            return redirect()->to('/admin/pre-oral-scheduling')->with('error', 'Pre-Oral Schedule not found.');
        }

        $scheduleDate = (string) $this->request->getPost('schedule_date');

        // Enforce approved Calendar of Activities window strictly
        $dateError = $this->validateScheduleDate($scheduleDate);
        if ($dateError !== null) {
            return redirect()->back()->withInput()->with('error', $dateError);
        }

        $researcherId = $this->request->getPost('researcher_id') ?: $schedule['researcher_id'];
        $studentId    = $this->request->getPost('student_id') ?: $schedule['student_id'];
        $instructorId = $this->request->getPost('instructor_id') ?: $schedule['instructor_id'];
        $titleText    = trim((string) $this->request->getPost('schedule_title'));

        if (empty($titleText)) {
            $titleText = $schedule['schedule_title'] ?: 'Pre-Oral Defense Schedule';
        }

        $data = [
            'researcher_id'   => $researcherId,
            'student_id'      => $studentId,
            'instructor_id'   => $instructorId,
            'schedule_type'   => CalendarActivityModel::TYPE_PRE_ORAL,
            'schedule_title'  => $titleText,
            'schedule_date'   => $scheduleDate,
            'schedule_time'   => $this->request->getPost('schedule_time') ?: '09:00:00',
            'venue'           => $this->request->getPost('venue') ?: 'Research Office / Zoom',
            'panelists'       => $this->request->getPost('panelists') ?: null,
            'status'          => $this->request->getPost('status') ?? 'scheduled',
        ];

        if ($this->scheduleModel->update($id, $data)) {
            $data = $this->applyExaminerPool((int) $id, $data);

            if ($researcherId) {
                $this->researcherModel->update($researcherId, [
                    'pre_oral_defense_date' => $scheduleDate
                ]);
            }

            AuditLogModel::log('PRE_ORAL_SCHEDULE_UPDATE', 'research_schedules', $id, $data);

            return redirect()->to('/admin/pre-oral-scheduling')->with('success', 'Pre-Oral Defense schedule updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update schedule.');
    }

    /**
     * Delete / Cancel a Pre-Oral Defense Schedule
     */
    public function delete($id)
    {
        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            return redirect()->to('/admin/pre-oral-scheduling')->with('error', 'Schedule not found.');
        }

        if ($this->scheduleModel->delete($id)) {
            AuditLogModel::log('PRE_ORAL_SCHEDULE_DELETE', 'research_schedules', $id, $schedule);
            return redirect()->to('/admin/pre-oral-scheduling')->with('success', 'Pre-Oral schedule removed successfully.');
        }

        return redirect()->to('/admin/pre-oral-scheduling')->with('error', 'Failed to delete schedule.');
    }

    /**
     * AJAX Get Event Details for viewing/modal
     */
    public function ajaxGetEvent($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $schedule = $this->scheduleModel
            ->select('research_schedules.*, users.username as student_name, u2.username as instructor_name, researchers.fullname as researcher_name, researchers.approved_research_title')
            ->join('users', 'users.id = research_schedules.student_id', 'left')
            ->join('users u2', 'u2.id = research_schedules.instructor_id', 'left')
            ->join('researchers', 'researchers.id = research_schedules.researcher_id', 'left')
            ->find($id);

        return $this->response->setJSON($schedule);
    }

    /**
     * AJAX Quick Add Approved Pre-Oral Schedule to Calendar of Activities
     */
    public function ajaxAddCalendarSchedule()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $date         = trim((string) $this->request->getPost('calendar_date'));
        $startTime    = trim((string) $this->request->getPost('calendar_start_time'));
        $endTime      = trim((string) $this->request->getPost('calendar_end_time'));
        $title        = trim((string) $this->request->getPost('calendar_title'));
        $academicYear = trim((string) $this->request->getPost('academic_year'));

        if (empty($date)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Defense date is required.']);
        }

        if (empty($title)) {
            $title = 'Pre-Oral Defense: ' . date('M d, Y', strtotime($date));
            if (!empty($startTime) && !empty($endTime)) {
                $title .= ' (' . date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($endTime)) . ')';
            }
        }

        $timeDesc = '';
        if (!empty($startTime) || !empty($endTime)) {
            $timeDesc = 'Schedule Time: ' . ($startTime ? date('h:i A', strtotime($startTime)) : '') . ($endTime ? ' to ' . date('h:i A', strtotime($endTime)) : '');
        }

        $activityData = [
            'activity_type' => CalendarActivityModel::TYPE_PRE_ORAL,
            'title'         => $title,
            'description'   => $timeDesc,
            'start_date'    => $date,
            'end_date'      => $date,
            'status'        => CalendarActivityModel::STATUS_APPROVED,
            'academic_year' => !empty($academicYear) ? $academicYear : date('Y') . '-' . (date('Y') + 1),
            'created_by'    => session()->get('user_id'),
        ];

        if ($this->activityModel->insert($activityData)) {
            $actId = $this->activityModel->getInsertID();
            AuditLogModel::log('CALENDAR_ACTIVITY_QUICK_CREATE', 'calendar_activities', $actId, $activityData);

            $allowedDates  = $this->activityModel->getAllowedIndividualDatesMap()[CalendarActivityModel::TYPE_PRE_ORAL] ?? [];
            $allowedRanges = $this->activityModel->getAllowedDateRanges(CalendarActivityModel::TYPE_PRE_ORAL);

            return $this->response->setJSON([
                'success'        => true,
                'message'        => 'Approved Pre-Oral Defense schedule added to Calendar of Activities successfully.',
                'new_date'       => $date,
                'start_time'     => $startTime,
                'end_time'       => $endTime,
                'allowed_dates'  => $allowedDates,
                'allowed_ranges' => $allowedRanges,
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to save schedule to Calendar of Activities. Please check inputs.'
        ]);
    }

    /**
     * AJAX Get Allowed Pre-Oral Dates from Calendar of Activities
     */
    public function ajaxGetAllowedDates()
    {
        $allowedDates  = $this->activityModel->getAllowedIndividualDatesMap()[CalendarActivityModel::TYPE_PRE_ORAL] ?? [];
        $allowedRanges = $this->activityModel->getAllowedDateRanges(CalendarActivityModel::TYPE_PRE_ORAL);

        return $this->response->setJSON([
            'success'        => true,
            'allowed_dates'  => $allowedDates,
            'allowed_ranges' => $allowedRanges,
        ]);
    }
}
