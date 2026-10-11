<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearchTitleModel;
use App\Models\ResearchProgressModel;
use App\Models\User;
use App\Models\ResearcherModel;
use App\Models\StrandModel;
use App\Models\ResearchCategoryModel;
use App\Models\SchoolYearModel;
use App\Models\AuditLogModel;

class ResearchTitles extends BaseController
{
    protected $titleModel;
    protected $progressModel;
    protected $userModel;
    protected $researcherModel;
    protected $strandModel;
    protected $categoryModel;
    protected $schoolYearModel;

    public function __construct()
    {
        $this->titleModel = new ResearchTitleModel();
        $this->progressModel = new ResearchProgressModel();
        $this->userModel = new User();
        $this->researcherModel = new ResearcherModel();
        $this->strandModel = new StrandModel();
        $this->categoryModel = new ResearchCategoryModel();
        $this->schoolYearModel = new SchoolYearModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $strandId = $this->request->getGet('strand_id');
        $categoryId = $this->request->getGet('category_id');
        $search = $this->request->getGet('search');

        $titles = $this->titleModel->getWithDetails($status, $strandId, $categoryId);
        if ($search) {
            $titles = array_filter($titles, function($t) use ($search) {
                return stripos($t['title'] ?? '', $search) !== false
                    || stripos($t['student_name'] ?? '', $search) !== false
                    || stripos($t['researcher_name'] ?? '', $search) !== false;
            });
        }

        $pendingCount = (new ResearchTitleModel())->where('status', 'pending')->countAllResults();
        $approvedCount = (new ResearchTitleModel())->where('status', 'approved')->countAllResults();
        $completedCount = (new ResearchTitleModel())->where('status', 'completed')->countAllResults();
        $rejectedCount = (new ResearchTitleModel())->where('status', 'rejected')->countAllResults();

        $data = [
            'title' => 'Title Management',
            'page_title' => 'Research Title Management',
            'titles' => $titles,
            'status' => $status,
            'strandId' => $strandId,
            'categoryId' => $categoryId,
            'search' => $search,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'completedCount' => $completedCount,
            'rejectedCount' => $rejectedCount,
            'strands' => $this->strandModel->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'students' => $this->userModel->getStudents(),
            'instructors' => $this->userModel->getInstructors(),
            'researchers' => $this->researcherModel->findAll(),
        ];

        return view('admin/research_titles/index', $data);
    }

    public function store()
    {
        $rawTitle = (string) $this->request->getPost('title');
        $title    = ResearchTitleModel::normalizeTitle($rawTitle);

        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Research title cannot be empty.');
        }

        // Exact duplicate prevention check (case-insensitive, space-normalized)
        $exactDuplicate = $this->titleModel->findExactDuplicate($title);
        if ($exactDuplicate !== null) {
            return redirect()->back()->withInput()->with('error', 'Duplicate Research Title: This research title already exists in the system. Please enter a different research title.');
        }

        $duplicates   = $this->titleModel->checkDuplicate($title);
        $topDuplicate = $duplicates[0]['similarity'] ?? 0;

        $data = [
            'researcher_id'         => $this->request->getPost('researcher_id') ?: null,
            'student_id'            => $this->request->getPost('student_id') ?: null,
            'instructor_id'         => $this->request->getPost('instructor_id') ?: null,
            'title'                 => $title,
            'abstract'              => $this->request->getPost('abstract'),
            'strand_id'             => $this->request->getPost('strand_id') ?: null,
            'category_id'           => $this->request->getPost('category_id') ?: null,
            'school_year_id'        => $this->request->getPost('school_year_id') ?: null,
            'duplicate_check_score' => $topDuplicate,
            'status'                => $this->request->getPost('status') ?? 'pending',
            'submitted_at'          => date('Y-m-d H:i:s'),
        ];

        if ($this->request->getPost('status') === 'approved') {
            $data['approved_at'] = date('Y-m-d H:i:s');
        }

        try {
            $id = $this->titleModel->insert($data);
            if ($id) {
                AuditLogModel::log('TITLE_CREATE', 'research_titles', $id, [
                    'title'         => $title,
                    'researcher_id' => $data['researcher_id'] ?? null,
                    'student_id'    => $data['student_id'] ?? null,
                    'status'        => $data['status'],
                ]);
                if ($data['status'] === 'approved' && $data['researcher_id']) {
                    $currentUserId = session()->get('user_id') ?: (session()->get('userId') ?: 1);
                    $this->progressModel->updateStage($data['researcher_id'], 'title_approved', 'Title approved via Title Management', $currentUserId);
                }
                $msg = $topDuplicate >= 70 ? 'Title created. WARNING: High duplicate similarity (' . $topDuplicate . '%)!' : 'Title submitted successfully';
                return redirect()->to('/admin/research-titles')->with('success', $msg);
            }
        } catch (\Throwable $e) {
            // Handle database duplicate key error safely
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'uk_research_title_unique')) {
                return redirect()->back()->withInput()->with('error', 'Duplicate Research Title: This research title already exists in the system. Please enter a different research title.');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create title');
    }

    public function checkDuplicateAjax()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $title     = (string) ($this->request->getPost('title') ?? '');
        $excludeId = $this->request->getPost('id') ? (int) $this->request->getPost('id') : null;

        $exactDuplicate = $this->titleModel->findExactDuplicate($title, $excludeId);
        $duplicates     = $this->titleModel->checkDuplicate($title, $excludeId);
        $topScore       = $duplicates[0]['similarity'] ?? 0;

        return $this->response->setJSON([
            'duplicates'         => $duplicates,
            'top_score'          => $exactDuplicate ? 100 : $topScore,
            'is_exact_duplicate' => $exactDuplicate !== null,
            'duplicate_title'    => $exactDuplicate ? $exactDuplicate['title'] : null,
            'message'            => $exactDuplicate ? 'This research title already exists in the system. Please enter a different research title.' : '',
        ]);
    }

    public function approve($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) {
            return redirect()->to('/admin/research-titles')->with('error', 'Title not found');
        }
        $data = [
            'status'      => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->titleModel->update($id, $data)) {
            AuditLogModel::log('TITLE_APPROVE', 'research_titles', $id, [
                'title' => $title['title'],
                'researcher_id' => $title['researcher_id'] ?? null,
                'student_id' => $title['student_id'] ?? null,
            ]);
            if (!empty($title['researcher_id'])) {
                $this->progressModel->updateStage($title['researcher_id'], 'title_approved', 'Title approved', session()->get('userId'));
            }
            return redirect()->to('/admin/research-titles')->with('success', 'Title approved successfully');
        }
        return redirect()->back()->with('error', 'Failed to approve');
    }

    public function reject($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) {
            return redirect()->to('/admin/research-titles')->with('error', 'Title not found');
        }
        $data = [
            'status'            => 'rejected',
            'rejection_reason'  => $this->request->getPost('reason'),
        ];
        if ($this->titleModel->update($id, $data)) {
            AuditLogModel::log('TITLE_REJECT', 'research_titles', $id, [
                'title' => $title['title'],
                'rejection_reason' => $data['rejection_reason'],
            ]);
            return redirect()->to('/admin/research-titles')->with('success', 'Title rejected');
        }
        return redirect()->back()->with('error', 'Failed to reject');
    }

    public function forRevision($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) {
            return redirect()->to('/admin/research-titles')->with('error', 'Title not found');
        }
        $data = [
            'status'          => 'for_revision',
            'revision_notes'  => $this->request->getPost('notes'),
        ];
        if ($this->titleModel->update($id, $data)) {
            AuditLogModel::log('TITLE_FOR_REVISION', 'research_titles', $id, [
                'title' => $title['title'],
                'revision_notes' => $data['revision_notes'],
            ]);
            return redirect()->to('/admin/research-titles')->with('success', 'Marked for revision');
        }
        return redirect()->back()->with('error', 'Failed');
    }

    public function markCompleted($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) {
            return redirect()->to('/admin/research-titles')->with('error', 'Title not found');
        }

        // Abstract Requirement: Prevent completing research without submitted abstract
        $abstractContent = trim((string)($title['abstract'] ?? ''));
        if (empty($abstractContent) && !empty($title['researcher_id'])) {
            $researcher = $this->researcherModel->find($title['researcher_id']);
            $abstractContent = trim((string)($researcher['abstract'] ?? ''));
        }

        if (empty($abstractContent)) {
            return redirect()->to('/admin/research-titles')->with('error', 'Cannot mark research as completed. An approved abstract must be submitted after passing the Final Defense before the research project can be completed.');
        }

        $data = [
            'status'      => 'completed',
            'approved_at' => $title['approved_at'] ?? date('Y-m-d H:i:s'),
        ];
        if ($this->titleModel->update($id, $data)) {
            AuditLogModel::log('TITLE_COMPLETE', 'research_titles', $id, [
                'title' => $title['title'],
            ]);
            if (!empty($title['researcher_id'])) {
                $this->progressModel->updateStage($title['researcher_id'], 'completed', 'Research completed', session()->get('userId'));
            }
            return redirect()->to('/admin/research-titles')->with('success', 'Marked as completed');
        }
        return redirect()->back()->with('error', 'Failed');
    }

    public function submitAbstract($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) {
            return redirect()->back()->with('error', 'Title record not found.');
        }

        $abstractText = trim((string) $this->request->getPost('abstract'));
        if (empty($abstractText)) {
            return redirect()->back()->with('error', 'Abstract content cannot be empty.');
        }

        $this->titleModel->update($id, ['abstract' => $abstractText]);

        // Sync with linked researcher if present
        if (!empty($title['researcher_id'])) {
            $this->researcherModel->update($title['researcher_id'], ['abstract' => $abstractText]);
        }

        AuditLogModel::log('TITLE_ABSTRACT_SUBMIT', 'research_titles', $id, [
            'title_id' => $id,
            'title'    => $title['title'],
        ]);

        return redirect()->back()->with('success', 'Research abstract submitted successfully.');
    }

    public function ajaxGetAbstract($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $title = $this->titleModel->select('research_titles.*, users.username as student_name, researchers.fullname as researcher_name')
            ->join('users', 'users.id = research_titles.student_id', 'left')
            ->join('researchers', 'researchers.id = research_titles.researcher_id', 'left')
            ->where('research_titles.id', $id)
            ->first();

        if (!$title) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found']);
        }

        return $this->response->setJSON([
            'status'          => 'success',
            'id'              => $title['id'],
            'title'           => $title['title'],
            'author'          => $title['researcher_name'] ?: ($title['student_name'] ?: 'N/A'),
            'research_status' => $title['status'],
            'abstract'        => $title['abstract'] ?? '',
            'has_abstract'    => !empty(trim($title['abstract'] ?? '')),
        ]);
    }

    public function edit($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) return redirect()->to('/admin/research-titles')->with('error', 'Title not found');
        return redirect()->to('/admin/research-titles')->withInput()->with('edit_title', $title);
    }

    public function update($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) return redirect()->to('/admin/research-titles')->with('error', 'Title not found');

        $rawTitle = (string) $this->request->getPost('title');
        $newTitle = ResearchTitleModel::normalizeTitle($rawTitle);

        if ($newTitle === '') {
            return redirect()->back()->withInput()->with('error', 'Research title cannot be empty.');
        }

        // Exact duplicate prevention check excluding current record
        $exactDuplicate = $this->titleModel->findExactDuplicate($newTitle, (int) $id);
        if ($exactDuplicate !== null) {
            return redirect()->back()->withInput()->with('error', 'Duplicate Research Title: This research title already exists in the system. Please enter a different research title.');
        }

        $duplicates   = $this->titleModel->checkDuplicate($newTitle, (int) $id);
        $topDuplicate = $duplicates[0]['similarity'] ?? 0;

        $data = [
            'researcher_id'         => $this->request->getPost('researcher_id') ?: null,
            'student_id'            => $this->request->getPost('student_id') ?: null,
            'instructor_id'         => $this->request->getPost('instructor_id') ?: null,
            'title'                 => $newTitle,
            'abstract'              => $this->request->getPost('abstract'),
            'strand_id'             => $this->request->getPost('strand_id') ?: null,
            'category_id'           => $this->request->getPost('category_id') ?: null,
            'school_year_id'        => $this->request->getPost('school_year_id') ?: null,
            'duplicate_check_score' => $topDuplicate,
            'status'                => $this->request->getPost('status') ?? 'pending',
        ];

        try {
            if ($this->titleModel->update($id, $data)) {
                AuditLogModel::log('TITLE_UPDATE', 'research_titles', $id, [
                    'title'         => $newTitle,
                    'researcher_id' => $data['researcher_id'] ?? null,
                    'student_id'    => $data['student_id'] ?? null,
                    'status'        => $data['status'],
                ]);
                return redirect()->to('/admin/research-titles')->with('success', 'Title updated successfully');
            }
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'uk_research_title_unique')) {
                return redirect()->back()->withInput()->with('error', 'Duplicate Research Title: This research title already exists in the system. Please enter a different research title.');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update title');
    }

    public function assignTeacher($id)
    {
        $title = $this->titleModel->find($id);
        if (!$title) {
            return redirect()->to('/admin/research-titles')->with('error', 'Research title not found.');
        }

        $instructorId = $this->request->getPost('instructor_id') ?: null;
        $this->titleModel->update($id, ['instructor_id' => $instructorId]);

        // If title has a student_id, keep the student assigned instructor in sync
        if (!empty($title['student_id']) && $instructorId) {
            $this->userModel->update($title['student_id'], ['assigned_instructor_id' => $instructorId]);
        }

        $instructorName = 'Unassigned';
        if ($instructorId) {
            $instructor = $this->userModel->find($instructorId);
            $instructorName = $instructor['username'] ?? "Instructor #{$instructorId}";
        }

        AuditLogModel::log('ASSIGN_RESEARCH_TEACHER', 'research_titles', $id, [
            'title_id'        => $id,
            'title'           => $title['title'],
            'student_id'      => $title['student_id'] ?? null,
            'instructor_id'   => $instructorId,
            'instructor_name' => $instructorName,
            'assigned_by'     => session()->get('username') ?? 'Admin',
        ]);

        return redirect()->to('/admin/research-titles')->with('success', 'Teacher/Research Instructor assigned successfully to "' . esc($title['title']) . '".');
    }

    public function delete($id)
    {
        $existing = $this->titleModel->find($id);
        if ($this->titleModel->delete($id)) {
            AuditLogModel::log('TITLE_DELETE', 'research_titles', $id, $existing ?? ['id' => $id]);
            return redirect()->to('/admin/research-titles')->with('success', 'Title deleted');
        }
        return redirect()->to('/admin/research-titles')->with('error', 'Failed to delete');
    }
}
