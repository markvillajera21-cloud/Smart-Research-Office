<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearchReportModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchTitleModel;
use App\Models\ResearchProgressModel;
use App\Models\ResearchEvaluationModel;
use App\Models\StrandModel;
use App\Models\ResearchCategoryModel;
use App\Models\CourseModel;
use App\Models\SchoolYearModel;
use App\Models\StatusModel;
use App\Models\ResearcherModel;

class Reports extends BaseController
{
    protected $reportModel;
    protected $scheduleModel;
    protected $titleModel;
    protected $progressModel;
    protected $evaluationModel;
    protected $strandModel;
    protected $categoryModel;
    protected $courseModel;
    protected $schoolYearModel;
    protected $statusModel;
    protected $researcherModel;

    public function __construct()
    {
        $this->reportModel = new ResearchReportModel();
        $this->scheduleModel = new ResearchScheduleModel();
        $this->titleModel = new ResearchTitleModel();
        $this->progressModel = new ResearchProgressModel();
        $this->evaluationModel = new ResearchEvaluationModel();
        $this->strandModel = new StrandModel();
        $this->categoryModel = new ResearchCategoryModel();
        $this->courseModel = new CourseModel();
        $this->schoolYearModel = new SchoolYearModel();
        $this->statusModel = new StatusModel();
        $this->researcherModel = new ResearcherModel();
    }

    public function index()
    {
        $history = $this->reportModel->getHistory(null, 20);
        $data = [
            'title' => 'Reports',
            'page_title' => 'Research Reports Generator',
            'history' => $history,
            'strands' => $this->strandModel->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'courses' => $this->courseModel->orderBy('name', 'ASC')->findAll(),
            'schoolYears' => $this->schoolYearModel->orderBy('name', 'DESC')->findAll(),
            'statuses' => $this->statusModel->orderBy('name', 'ASC')->findAll(),
        ];
        return view('admin/reports/index', $data);
    }

    public function generateScheduleReport()
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');
        $type = $this->request->getGet('schedule_type');

        $schedules = $this->scheduleModel->getByDateRange($startDate, $endDate, $type);
        $params = ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $type];
        $this->reportModel->logReport('schedule', $params);

        $stats = [
            'total' => count($schedules),
            'title_hearing' => count(array_filter($schedules, fn($s) => $s['schedule_type'] === 'title_hearing')),
            'pre_oral' => count(array_filter($schedules, fn($s) => $s['schedule_type'] === 'pre_oral')),
            'final_defense' => count(array_filter($schedules, fn($s) => $s['schedule_type'] === 'final_defense')),
            'completed' => count(array_filter($schedules, fn($s) => $s['status'] === 'completed')),
            'cancelled' => count(array_filter($schedules, fn($s) => $s['status'] === 'cancelled')),
        ];

        $data = [
            'title' => 'Schedule Report',
            'page_title' => 'Research Schedule Report',
            'report_type' => 'schedule',
            'params' => $params,
            'schedules' => $schedules,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
        ];
        return view('admin/reports/schedule_report', $data);
    }

    public function generateStatusReport()
    {
        $strandId = $this->request->getGet('strand_id');
        $categoryId = $this->request->getGet('category_id');

        $titles = $this->titleModel->getWithDetails(null, $strandId, $categoryId);
        $params = ['strand_id' => $strandId, 'category_id' => $categoryId];
        $this->reportModel->logReport('status', $params);

        $statusCounts = [
            'pending' => 0, 'approved' => 0, 'rejected' => 0, 'for_revision' => 0, 'completed' => 0
        ];
        foreach ($titles as $t) {
            if (isset($statusCounts[$t['status']])) $statusCounts[$t['status']]++;
        }

        $data = [
            'title' => 'Research Status Report',
            'page_title' => 'Research Status Report',
            'report_type' => 'status',
            'params' => $params,
            'titles' => $titles,
            'statusCounts' => $statusCounts,
            'strandId' => $strandId,
            'categoryId' => $categoryId,
            'strands' => $this->strandModel->findAll(),
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('admin/reports/status_report', $data);
    }

    public function generateCompletionReport()
    {
        $schoolYear = $this->request->getGet('school_year');
        $params = ['school_year' => $schoolYear];
        $this->reportModel->logReport('completion', $params);

        $completed = $this->progressModel->getCompleted();
        $incomplete = $this->progressModel->getIncomplete();
        $stuck30 = $this->progressModel->getStuck(30);
        $stuck60 = $this->progressModel->getStuck(60);
        $stuck90 = $this->progressModel->getStuck(90);

        $rate = $this->progressModel->getCompletionRate();
        $total = count($completed) + count($incomplete);

        $strandStats = [];
        foreach ($this->strandModel->findAll() as $strand) {
            $c = count(array_filter($completed, fn($r) => $r['strand_name'] === $strand['name']));
            $strandStats[] = [
                'strand' => $strand['name'],
                'completed' => $c,
                'total' => $c + count(array_filter($incomplete, fn($r) => $r['strand_name'] === $strand['name'])),
            ];
        }

        $data = [
            'title' => 'Completion Report',
            'page_title' => 'Research Completion Report',
            'report_type' => 'completion',
            'params' => $params,
            'completed' => $completed,
            'incomplete' => $incomplete,
            'stuck30' => $stuck30,
            'stuck60' => $stuck60,
            'stuck90' => $stuck90,
            'completionRate' => $rate,
            'totalResearch' => $total,
            'strandStats' => $strandStats,
        ];
        return view('admin/reports/completion_report', $data);
    }

    public function generateResearcherReport()
    {
        // strand_id is the actual FK used by researchers for Course/Program
        // school_year_id is the FK for Academic Year
        // status is a free-text column ('active', 'not complete', etc.) — status_id is always NULL
        $strandId     = trim((string)($this->request->getGet('strand_id') ?? ''));
        $schoolYearId = trim((string)($this->request->getGet('school_year_id') ?? ''));
        $statusText   = trim((string)($this->request->getGet('status_text') ?? ''));
        $search       = trim((string)($this->request->getGet('search') ?? ''));

        $researchers = $this->buildResearcherQuery($strandId, $schoolYearId, $statusText, $search);

        $params = [
            'strand_id'      => $strandId,
            'school_year_id' => $schoolYearId,
            'status_text'    => $statusText,
            'search'         => $search,
        ];
        $this->reportModel->logReport('researchers', $params);

        // Summary stats from filtered results
        $totalCount  = count($researchers);
        $courseStats = [];
        $yearStats   = [];
        $statusStats = [];

        foreach ($researchers as $r) {
            $cName = $this->resolveCourse($r);
            $yName = $this->resolveYear($r);
            $sName = $this->resolveStatus($r);
            $courseStats[$cName] = ($courseStats[$cName] ?? 0) + 1;
            $yearStats[$yName]   = ($yearStats[$yName]   ?? 0) + 1;
            $statusStats[$sName] = ($statusStats[$sName] ?? 0) + 1;
        }

        // Human-readable label for selected strand
        $selectedCourseName = '';
        if (!empty($strandId)) {
            $strandRow = $this->strandModel->find($strandId);
            $selectedCourseName = $strandRow['name'] ?? '';
        }
        $selectedYearName = '';
        if (!empty($schoolYearId)) {
            $yearRow = $this->schoolYearModel->find($schoolYearId);
            $selectedYearName = $yearRow['name'] ?? '';
        }

        // Collect distinct status values that researchers actually have
        $db = db_connect();
        $distinctStatuses = $db->query("SELECT DISTINCT LOWER(TRIM(status)) as s FROM researchers WHERE status IS NOT NULL AND status != '' ORDER BY s")->getResultArray();

        $data = [
            'title'              => 'Researcher Report',
            'page_title'         => 'Researcher & Student Report',
            'report_type'        => 'researchers',
            'params'             => $params,
            'researchers'        => $researchers,
            'strands'            => $this->strandModel->orderBy('name', 'ASC')->findAll(),
            'schoolYears'        => $this->schoolYearModel->orderBy('name', 'DESC')->findAll(),
            'distinctStatuses'   => array_column($distinctStatuses, 's'),
            'selectedStrand'     => $strandId,
            'selectedSchoolYear' => $schoolYearId,
            'selectedStatus'     => $statusText,
            'search'             => $search,
            'selectedCourseName' => $selectedCourseName,
            'selectedYearName'   => $selectedYearName,
            'selectedStatusName' => $statusText,
            'totalCount'         => $totalCount,
            'courseStats'        => $courseStats,
            'yearStats'          => $yearStats,
            'statusStats'        => $statusStats,
        ];

        return view('admin/reports/researcher_report', $data);
    }

    /**
     * Central query builder.
     * 
     * IMPORTANT: Researchers store program in `strand_id` (not course_id — that's always NULL).
     * Status is stored as free-text in the `status` column (status_id FK is always NULL).
     *
     * @param string $strandId    ID from the strands table (Course/Program filter)
     * @param string $schoolYearId ID from school_years table
     * @param string $statusText  Free-text status value e.g. 'active', 'not complete'
     * @param string $search      Keyword search string
     */
    private function buildResearcherQuery(string $strandId, string $schoolYearId, string $statusText, string $search): array
    {
        $query = $this->researcherModel
            ->select('
                researchers.*,
                users.username, users.email,
                courses.name  as course_name,
                school_years.name as school_year_name,
                strands.name  as strand_name,
                statuses.name as status_name,
                advisers.name as adviser_name,
                grammarians.name as grammarian_name,
                remarks.name as statistician_name,
                research_teachers.name as research_teacher_name
            ')
            ->join('users',             'users.id = researchers.user_id',                         'left')
            ->join('courses',           'courses.id = researchers.course_id',                     'left')
            ->join('school_years',      'school_years.id = researchers.school_year_id',           'left')
            ->join('strands',           'strands.id = researchers.strand_id',                     'left')
            ->join('statuses',          'statuses.id = researchers.status_id',                    'left')
            ->join('advisers',          'advisers.id = researchers.adviser_id',                   'left')
            ->join('grammarians',       'grammarians.id = researchers.grammarian_id',             'left')
            ->join('remarks',           'remarks.id = researchers.remark_id',                     'left')
            ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left');

        // PRIMARY FILTER: Course / Program
        // Researchers use strand_id for their course/program (course_id is NULL for all records).
        // Also fall back to strand_degree_program text column if strand_id is NULL.
        if (!empty($strandId)) {
            $strandRow = $this->strandModel->find($strandId);
            if ($strandRow) {
                $query->groupStart()
                      ->where('researchers.strand_id', $strandId)
                      ->orLike('researchers.strand_degree_program', $strandRow['name'], 'both', true, true)
                      ->groupEnd();
            } else {
                $query->where('researchers.strand_id', $strandId);
            }
        }

        // PRIMARY FILTER: Academic Year
        if (!empty($schoolYearId)) {
            $query->where('researchers.school_year_id', $schoolYearId);
        }

        // OPTIONAL FILTER: Status (free-text column — status_id FK is always NULL in this system)
        if (!empty($statusText)) {
            $query->where('LOWER(TRIM(researchers.status))', strtolower(trim($statusText)));
        }

        // OPTIONAL FILTER: Keyword search
        if (!empty($search)) {
            $query->groupStart()
                  ->like('researchers.fullname',               $search)
                  ->orLike('researchers.author',               $search)
                  ->orLike('researchers.surname',              $search)
                  ->orLike('researchers.first_name',           $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->orLike('users.username',                   $search)
                  ->orLike('strands.name',                     $search)
                  ->orLike('researchers.strand_degree_program',$search)
                  ->groupEnd();
        }

        return $query->orderBy('researchers.created_at', 'DESC')->findAll();
    }

    /** Resolve display name for course/program from a researcher row. */
    private function resolveCourse(array $r): string
    {
        return !empty($r['strand_name'])
            ? $r['strand_name']
            : (!empty($r['strand_degree_program'])
                ? $r['strand_degree_program']
                : (!empty($r['course_name'])
                    ? $r['course_name']
                    : 'Unassigned Program'));
    }

    /** Resolve display name for academic year from a researcher row. */
    private function resolveYear(array $r): string
    {
        return !empty($r['school_year_name'])
            ? $r['school_year_name']
            : (!empty($r['school_year']) ? $r['school_year'] : 'Unspecified A.Y.');
    }

    /** Resolve display status label from a researcher row. */
    private function resolveStatus(array $r): string
    {
        // status_id FK is always NULL; use the free-text status column
        return !empty($r['status']) ? ucwords(str_replace('_', ' ', $r['status'])) : 'Active';
    }

    public function history()
    {
        $type = $this->request->getGet('type');
        $history = $this->reportModel->getHistory($type, 100);
        $data = [
            'title' => 'Report History',
            'page_title' => 'Generated Reports History',
            'history' => $history,
            'type' => $type,
        ];
        return view('admin/reports/history', $data);
    }

    public function export()
    {
        $type = $this->request->getGet('type');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="roarms_' . $type . '_report_' . date('Y-m-d_His') . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['RMS ' . strtoupper($type) . ' Report - Generated ' . date('F d, Y h:i A')]);
        fputcsv($output, ['---------------------------------------------']);

        if ($type === 'schedule') {
            $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
            $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');
            $data = $this->scheduleModel->getByDateRange($startDate, $endDate);
            fputcsv($output, ['ID', 'Type', 'Title', 'Date', 'Time', 'Venue', 'Student', 'Instructor', 'Status']);
            foreach ($data as $r) {
                fputcsv($output, [
                    $r['id'], ucwords(str_replace('_', ' ', $r['schedule_type'])),
                    $r['schedule_title'], $r['schedule_date'], $r['schedule_time'],
                    $r['venue'], $r['student_name'] ?? $r['researcher_name'] ?? 'N/A',
                    $r['instructor_name'] ?? 'N/A', ucfirst($r['status'])
                ]);
            }
        } elseif ($type === 'status') {
            $data = $this->titleModel->getWithDetails();
            fputcsv($output, ['ID', 'Title', 'Student/Researcher', 'Strand', 'Category', 'Status', 'Submitted', 'Approved At']);
            foreach ($data as $r) {
                fputcsv($output, [
                    $r['id'], $r['title'],
                    $r['student_name'] ?? $r['researcher_name'] ?? 'N/A',
                    $r['strand_name'] ?? 'N/A', $r['category_name'] ?? 'N/A',
                    ucwords(str_replace('_', ' ', $r['status'])),
                    $r['submitted_at'], $r['approved_at']
                ]);
            }
        } elseif ($type === 'researchers') {
            // Use the same shared query builder — guarantees CSV matches on-screen table exactly
            $strandId     = trim((string)($this->request->getGet('strand_id') ?? ''));
            $schoolYearId = trim((string)($this->request->getGet('school_year_id') ?? ''));
            $statusText   = trim((string)($this->request->getGet('status_text') ?? ''));
            $search       = trim((string)($this->request->getGet('search') ?? ''));

            $data = $this->buildResearcherQuery($strandId, $schoolYearId, $statusText, $search);

            fputcsv($output, ['#', 'Researcher Name', 'Course / Program', 'Academic Year', 'Research Title', 'Research Status', 'Registered Date']);
            $cnt = 0;
            foreach ($data as $r) {
                $cnt++;
                $name = !empty($r['fullname']) ? $r['fullname'] : (!empty($r['author']) ? $r['author'] : trim(($r['surname'] ?? '') . ', ' . ($r['first_name'] ?? '')));
                if (empty(trim($name, ', '))) $name = $r['username'] ?? 'N/A';
                fputcsv($output, [
                    $cnt,
                    $name,
                    $this->resolveCourse($r),
                    $this->resolveYear($r),
                    !empty($r['approved_research_title']) ? $r['approved_research_title'] : 'Untitled Project',
                    $this->resolveStatus($r),
                    !empty($r['created_at']) ? date('M d, Y', strtotime($r['created_at'])) : 'N/A',
                ]);
            }
        } else {
            $data = $this->progressModel->getAllWithDetails();
            fputcsv($output, ['Researcher', 'Student', 'Strand', 'Category', 'Stage', 'Progress %', 'Last Updated']);
            foreach ($data as $r) {
                fputcsv($output, [
                    $r['researcher_name'] ?? 'N/A', $r['student_name'] ?? 'N/A',
                    $r['strand_name'] ?? 'N/A', $r['category_name'] ?? 'N/A',
                    ucwords(str_replace('_', ' ', $r['stage'])),
                    $r['progress_percentage'] . '%', $r['last_updated']
                ]);
            }
        }
        fclose($output);
        exit;
    }
}
