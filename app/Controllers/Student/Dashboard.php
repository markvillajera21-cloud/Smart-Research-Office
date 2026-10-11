<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchTitleModel;
use App\Models\ResearchProgressModel;
use App\Models\ResearchEvaluationModel;
use App\Models\StrandModel;
use App\Models\ResearchCategoryModel;

class Dashboard extends BaseController
{
    protected $scheduleModel;
    protected $titleModel;
    protected $progressModel;
    protected $evaluationModel;
    protected $strandModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->scheduleModel = new ResearchScheduleModel();
        $this->titleModel = new ResearchTitleModel();
        $this->progressModel = new ResearchProgressModel();
        $this->evaluationModel = new ResearchEvaluationModel();
        $this->strandModel = new StrandModel();
        $this->categoryModel = new ResearchCategoryModel();
    }

    protected function getStudentId()
    {
        return session()->get('user_id');
    }

    public function index()
    {
        $studentId = $this->getStudentId();

        $mySchedules = $this->scheduleModel->getStudentSchedules($studentId);
        $myTitles = $this->titleModel->getStudentTitles($studentId);
        $myEvaluations = $this->evaluationModel->getStudentEvaluations($studentId);

        $upcomingSchedules = array_filter($mySchedules, function($s) {
            return strtotime($s['schedule_date'] . ' ' . $s['schedule_time']) >= time() && $s['status'] === 'scheduled';
        });

        $pendingTitles = array_filter($myTitles, function($t) {
            return $t['status'] === 'pending' || $t['status'] === 'for_revision';
        });

        $approvedCount = count(array_filter($myTitles, fn($t) => $t['status'] === 'approved'));
        $completedCount = count(array_filter($myTitles, fn($t) => $t['status'] === 'completed'));

        $data = [
            'title' => 'Student Dashboard',
            'page_title' => 'My Research Dashboard',
            'mySchedules' => $mySchedules,
            'upcomingSchedules' => $upcomingSchedules,
            'myTitles' => $myTitles,
            'pendingTitles' => $pendingTitles,
            'totalTitles' => count($myTitles),
            'approvedCount' => $approvedCount,
            'completedCount' => $completedCount,
            'myEvaluations' => $myEvaluations,
            'passedCount' => count(array_filter($myEvaluations, fn($e) => $e['result'] === 'passed')),
            'forComplianceCount' => count(array_filter($myEvaluations, fn($e) => $e['result'] === 'for_compliance' && empty($e['compliance_submitted']))),
        ];

        return view('student/dashboard', $data);
    }

    public function mySchedules()
    {
        $studentId = $this->getStudentId();
        $mySchedules = $this->scheduleModel->getStudentSchedules($studentId);

        $data = [
            'title' => 'My Schedules',
            'page_title' => 'My Research Schedules',
            'mySchedules' => $mySchedules,
        ];

        return view('student/my_schedules', $data);
    }

    public function myTitles()
    {
        $studentId = $this->getStudentId();
        $myTitles = $this->titleModel->getStudentTitles($studentId);

        // Also check if student has an entry in researchers table
        try {
            $researcherModel = new \App\Models\ResearcherModel();
            $researcherProfiles = $researcherModel->select('researchers.*, courses.name as course_name, strands.name as strand_name, school_years.name as school_year_name, statuses.name as status_name')
                ->join('courses', 'courses.id = researchers.course_id', 'left')
                ->join('strands', 'strands.id = researchers.strand_id', 'left')
                ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                ->join('statuses', 'statuses.id = researchers.status_id', 'left')
                ->where('researchers.user_id', $studentId)
                ->findAll();

            $existingTitleTexts = array_map(fn($t) => strtolower(trim($t['title'] ?? '')), $myTitles);

            foreach ($researcherProfiles as $rp) {
                $tName = trim($rp['approved_research_title'] ?? '');
                if (!empty($tName) && !in_array(strtolower($tName), $existingTitleTexts)) {
                    $myTitles[] = [
                        'id'                        => null,
                        'title'                     => $tName,
                        'abstract'                  => $rp['abstract'] ?? '',
                        'status'                    => !empty($rp['status_name']) ? strtolower($rp['status_name']) : (!empty($rp['status']) ? strtolower($rp['status']) : 'approved'),
                        'researcher_fullname'       => $rp['fullname'] ?? '',
                        'researcher_author'         => $rp['author'] ?? '',
                        'course_name'               => $rp['course_name'] ?? $rp['strand_degree_program'] ?? '',
                        'strand_name'               => $rp['strand_name'] ?? $rp['strand_degree_program'] ?? '',
                        'school_year_name'          => $rp['school_year_name'] ?? $rp['school_year'] ?? '',
                        'submitted_at'              => $rp['created_at'] ?? null,
                        'approved_at'               => $rp['approved_date'] ?? null,
                        'final_defense_status_name' => $rp['final_defense_status'] ?? null,
                        'is_researcher_record'      => true,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback to myTitles
        }

        $data = [
            'title'      => 'Student Titles',
            'page_title' => 'My Research Titles',
            'myTitles'   => $myTitles,
        ];

        return view('student/my_titles', $data);
    }

    public function submitTitle()
    {
        $studentId = $this->getStudentId();
        $myTitles = $this->titleModel->getStudentTitles($studentId);

        $data = [
            'title' => 'Submit Research Title',
            'page_title' => 'Submit New Research Title',
            'strands' => $this->strandModel->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'myTitles' => $myTitles,
        ];

        return view('student/submit_title', $data);
    }

    public function storeTitle()
    {
        $studentId = $this->getStudentId();
        $rawTitle  = (string) $this->request->getPost('title');
        $titleText = ResearchTitleModel::normalizeTitle($rawTitle);

        if ($titleText === '') {
            return redirect()->back()->withInput()->with('error', 'Research title cannot be empty.');
        }

        // Exact duplicate prevention check (case-insensitive, space-normalized)
        $exactDuplicate = $this->titleModel->findExactDuplicate($titleText);
        if ($exactDuplicate !== null) {
            return redirect()->back()->withInput()->with('error', 'Duplicate Research Title: This research title already exists in the system. Please enter a different research title.');
        }

        $duplicates   = $this->titleModel->checkDuplicate($titleText);
        $topDuplicate = $duplicates[0]['similarity'] ?? 0;

        $data = [
            'student_id'            => $studentId,
            'title'                 => $titleText,
            'abstract'              => $this->request->getPost('abstract'),
            'strand_id'             => $this->request->getPost('strand_id') ?: null,
            'category_id'           => $this->request->getPost('category_id') ?: null,
            'duplicate_check_score' => $topDuplicate,
            'status'                => 'pending',
            'submitted_at'          => date('Y-m-d H:i:s'),
        ];

        try {
            if ($this->titleModel->insert($data)) {
                AuditLogModel::log('TITLE_SUBMIT', 'research_titles', $this->titleModel->getInsertID(), [
                    'title'                 => $titleText,
                    'student_id'            => $studentId,
                    'strand_id'             => $data['strand_id'],
                    'category_id'           => $data['category_id'],
                    'duplicate_check_score' => $topDuplicate,
                ]);
                $msg = $topDuplicate >= 70 ? 'Title submitted. WARNING: High duplicate similarity (' . $topDuplicate . '%) detected!' : 'Research title submitted successfully. Awaiting approval.';
                return redirect()->to('/student/my-titles')->with('success', $msg);
            }
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'uk_research_title_unique')) {
                return redirect()->back()->withInput()->with('error', 'Duplicate Research Title: This research title already exists in the system. Please enter a different research title.');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Failed to submit title.');
    }

    public function searchTitles()
    {
        $search = $this->request->getGet('search');
        $strandId = $this->request->getGet('strand_id');
        $titles = $this->titleModel->getCompletedOnly($search, $strandId);

        $data = [
            'title' => 'Search Research Titles',
            'page_title' => 'Search Completed Research Titles',
            'titles' => $titles,
            'search' => $search,
            'strandId' => $strandId,
            'strands' => $this->strandModel->findAll(),
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('student/search_titles', $data);
    }

    public function completedResearches()
    {
        $search = $this->request->getGet('search');
        $strandId = $this->request->getGet('strand_id');
        $titles = $this->titleModel->getCompletedOnly($search, $strandId);

        $data = [
            'title' => 'Completed Researches',
            'page_title' => 'Completed Research Archive',
            'titles' => $titles,
            'search' => $search,
            'strandId' => $strandId,
            'strands' => $this->strandModel->findAll(),
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('student/completed_researches', $data);
    }

    public function viewAbstract($id)
    {
        $title = $this->titleModel->select('research_titles.*, 
            users.username as student_name, 
            u2.username as instructor_name, 
            strands.name as strand_name,
            research_categories.name as category_name,
            school_years.name as school_year_name')
            ->join('users', 'users.id = research_titles.student_id', 'left')
            ->join('users u2', 'u2.id = research_titles.instructor_id', 'left')
            ->join('strands', 'strands.id = research_titles.strand_id', 'left')
            ->join('research_categories', 'research_categories.id = research_titles.category_id', 'left')
            ->join('school_years', 'school_years.id = research_titles.school_year_id', 'left')
            ->where('research_titles.id', $id)
            ->first();

        if (!$title) {
            return redirect()->to('/student/completed-researches')->with('error', 'Research not found.');
        }

        if ($title['status'] !== 'completed' && $title['student_id'] != $this->getStudentId()) {
            return redirect()->to('/student/completed-researches')->with('error', 'Access denied. You can only view completed researches or your own submissions.');
        }

        $data = [
            'title' => 'View Abstract',
            'page_title' => 'Research Abstract',
            'research' => $title,
        ];

        return view('student/view_abstract', $data);
    }

    public function submitAbstract($id)
    {
        $studentId = $this->getStudentId();
        $title = $this->titleModel->where('id', $id)->where('student_id', $studentId)->first();
        if (!$title) {
            return redirect()->back()->with('error', 'Research record not found or unauthorized.');
        }

        $abstractText = trim((string) $this->request->getPost('abstract'));
        if (empty($abstractText)) {
            return redirect()->back()->with('error', 'Abstract content cannot be empty.');
        }

        $this->titleModel->update($id, ['abstract' => $abstractText]);

        // Sync with researcher table if linked
        if (!empty($title['researcher_id'])) {
            (new \App\Models\ResearcherModel())->update($title['researcher_id'], ['abstract' => $abstractText]);
        }

        AuditLogModel::log('STUDENT_ABSTRACT_SUBMIT', 'research_titles', $id, [
            'title_id'   => $id,
            'student_id' => $studentId,
            'title'      => $title['title'],
        ]);

        return redirect()->back()->with('success', 'Abstract submitted successfully! Your abstract is now recorded on your research profile.');
    }
    public function expertPool()
    {
        $expertModel = new \App\Models\ExpertModel();

        $experts = [];
        if ($expertModel->poolReady()) {
            $experts = $expertModel->where('is_active', 1)->orderBy('expert_role', 'ASC')->orderBy('name', 'ASC')->findAll();
        }

        $data = [
            'title'      => 'Expert Pool',
            'page_title' => 'Research Expert Pool',
            'experts'    => $experts,
        ];

        return view('student/expert_pool', $data);
    }
}
