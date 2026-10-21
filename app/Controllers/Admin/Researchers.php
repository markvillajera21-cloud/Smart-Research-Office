<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearcherModel;
use App\Models\ResearchCategoryModel;
use App\Models\DesignationModel;
use App\Models\SchoolYearModel;
use App\Models\StrandModel;
use App\Models\CourseModel;
use App\Models\StatusModel;
use App\Models\AdviserModel;
use App\Models\GrammarianModel;
use App\Models\RemarkModel;
use App\Models\AbstractModel;
use App\Models\ResearchTeacherModel;
use App\Models\AuditLogModel;

class Researchers extends BaseController
{
    protected $researcherModel;
    protected $categoryModel;
    protected $designationModel;
    protected $schoolYearModel;
    protected $strandModel;
    protected $courseModel;
    protected $statusModel;
    protected $adviserModel;
    protected $grammarianModel;
    protected $researchTeacherModel;
    protected $defenseStatusModel;
    protected $expertModel;

    public function __construct()
    {
        $this->researcherModel = new ResearcherModel();
        $this->categoryModel = new ResearchCategoryModel();
        $this->designationModel = new DesignationModel();
        $this->schoolYearModel = new SchoolYearModel();
        $this->strandModel = new StrandModel();
        $this->courseModel = new CourseModel();
        $this->statusModel = new StatusModel();
        $this->adviserModel = new AdviserModel();
        $this->grammarianModel = new GrammarianModel();
        $this->statisticianModel = new RemarkModel();
        $this->abstractModel = new AbstractModel();
        $this->researchTeacherModel = new ResearchTeacherModel();
        $this->defenseStatusModel = new \App\Models\DefenseStatusModel();
        $this->expertModel = new \App\Models\ExpertModel();
    }

    protected function getSelectableExperts(string $role, $fallbackModel): array
    {
        if ($this->expertModel->poolReady()) {
            $experts = $this->expertModel->getSelectable($role);
            if (!empty($experts)) {
                return array_map(function($e) {
                    return [
                        'id'        => $e['legacy_id'] ?: $e['id'],
                        'name'      => $e['name'],
                        'is_active' => $e['is_active'] ?? 1,
                    ];
                }, $experts);
            }
        }
        return $fallbackModel->orderBy('name', 'ASC')->findAll();
    }

    public function index()
    {
        $categoryFilter = $this->request->getGet('category');
        $schoolYearFilter = $this->request->getGet('school_year');
        $strandFilter = $this->request->getGet('strand');
        $adviserFilter = $this->request->getGet('adviser');
        $grammarianFilter = $this->request->getGet('grammarian');
        $statisticianFilter = $this->request->getGet('statistician');
        $researchTeacherFilter = $this->request->getGet('research_teacher');
        $search = trim((string) ($this->request->getGet('search') ?? ''));
        $studentSearch = trim((string) ($this->request->getGet('student_search') ?? ''));
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left');

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($schoolYearFilter) {
            $query->where('researchers.school_year_id', $schoolYearFilter);
        }

        if ($strandFilter) {
            $query->where('researchers.strand_id', $strandFilter);
        }
        
        if ($adviserFilter) {
            $query->where('researchers.adviser_id', $adviserFilter);
        }
        
        if ($grammarianFilter) {
            $query->where('researchers.grammarian_id', $grammarianFilter);
        }
        
        if ($statisticianFilter) {
            $query->where('researchers.remark_id', $statisticianFilter);
        }
        
        if ($researchTeacherFilter) {
            $query->where('researchers.research_teacher_id', $researchTeacherFilter);
        }

        if (!empty($studentSearch)) {
            $query->groupStart()
                  ->like('users.username', $studentSearch)
                  ->orLike('researchers.fullname', $studentSearch)
                  ->orLike('researchers.author', $studentSearch)
                  ->orLike('researchers.surname', $studentSearch)
                  ->orLike('researchers.first_name', $studentSearch)
                  ->orLike('users.email', $studentSearch)
                  ->groupEnd();
        }

        if (!empty($search)) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $query->orderBy('researchers.created_at', 'DESC');

        $data = [
            'title' => 'Research Directory',
            'page_title' => 'Research List',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'selectedCategory' => $categoryFilter,
            'selectedSchoolYear' => $schoolYearFilter,
            'selectedStrand' => $strandFilter,
            'selectedAdviser' => $adviserFilter,
            'selectedGrammarian' => $grammarianFilter,
            'selectedStatistician' => $statisticianFilter,
            'selectedResearchTeacher' => $researchTeacherFilter,
            'search' => $search,
            'studentSearch' => $studentSearch,
        ];

        return view('admin/researchers/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Researcher',
            'page_title' => 'Create Researcher Profile',
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'designations' => $this->designationModel->orderBy('name', 'ASC')->findAll(),
            'schoolYears' => $this->schoolYearModel->orderBy('name', 'ASC')->findAll(),
            'strands' => $this->strandModel->orderBy('name', 'ASC')->findAll(),
            'statuses' => $this->statusModel->orderBy('name', 'ASC')->findAll(),
            'advisers' => $this->getSelectableExperts(\App\Models\ExpertModel::ROLE_ADVISER, $this->adviserModel),
            'grammarians' => $this->getSelectableExperts(\App\Models\ExpertModel::ROLE_GRAMMARIAN, $this->grammarianModel),
            'statisticians' => $this->getSelectableExperts(\App\Models\ExpertModel::ROLE_STATISTICIAN, $this->statisticianModel),
            'researchTeachers' => $this->researchTeacherModel->orderBy('name', 'ASC')->findAll(),
            'abstracts' => $this->abstractModel->orderBy('name', 'ASC')->findAll(),
            'defenseStatuses' => $this->defenseStatusModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/researchers/create', $data);
    }

    public function store()
    {
        $rules = [
            'surname'         => 'required|min_length[2]',
            'category_id'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $members = $this->request->getPost('surname');
        
        // Use first member as fullname for display
        $fullname = trim(explode("\n", $members)[0]) ?? 'Member';

        $strandValue = $this->request->getPost('strand_id');
        $strand = $this->resolveStrandId($strandValue);
        $strandId = $strand['id'];
        $strandName = $strand['name'];

        $data = [
            'fullname'                  => $fullname,
            'author'                    => $this->request->getPost('author') ?: null,
            'surname'                   => $members,
            'first_name'                => '',
            'middle_initial'            => '',
            'ext_name'                  => '',
            'designation_id'            => $this->request->getPost('designation_id') ?: null,
            'school_year_id'            => $this->request->getPost('school_year_id') ?: null,
            'strand_id'                 => $strandId ?: null,
            'strand_degree_program'     => $strandName,
            'status_id'                 => $this->request->getPost('status_id') ?: null,
            'adviser_id'                => $this->request->getPost('adviser_id') ?: null,
            'grammarian_id'             => $this->request->getPost('grammarian_id') ?: null,
            'remark_id'                 => $this->request->getPost('remark_id') ?: null,
            'research_teacher_id'       => $this->request->getPost('research_teacher_id') ?: null,
            'abstract'                  => $this->request->getPost('abstract') ?: null,
            'category_id'               => $this->request->getPost('category_id'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
            'approved_date'             => $this->request->getPost('approved_date') ?: null,
            'pre_oral_defense_date'     => $this->request->getPost('pre_oral_defense_date') ?: null,
            'pre_oral_defense_status_id'   => $this->request->getPost('pre_oral_defense_status_id') ?: null,
            'final_defense_date'        => $this->request->getPost('final_defense_date') ?: null,
            'final_defense_status_id'      => $this->request->getPost('final_defense_status_id') ?: null,
            'remarks'                   => $this->request->getPost('remarks'),
            'status'                    => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->researcherModel->insert($data)) {
            $newId = $this->researcherModel->getInsertID();
            AuditLogModel::log('RESEARCHER_CREATE', 'researchers', $newId, [
                'fullname' => $data['fullname'],
                'approved_research_title' => $data['approved_research_title'] ?? null,
                'category_id' => $data['category_id'],
                'strand_id' => $data['strand_id'] ?? null,
            ]);
            return redirect()->to('admin/researchers')->with('success', 'Researcher profile created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create researcher profile.');
    }

    public function edit($id)
    {
        $researcher = $this->researcherModel->select('researchers.*, users.username')
                                           ->join('users', 'users.id = researchers.user_id', 'left')
                                           ->find($id);
        
        if (!$researcher) {
            return redirect()->to('admin/researchers')->with('error', 'Researcher not found.');
        }

        $data = [
            'title' => 'Edit Researcher',
            'page_title' => 'Update Profile: ' . ($researcher['username'] ?? $researcher['fullname']),
            'researcher' => $researcher,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'designations' => $this->designationModel->orderBy('name', 'ASC')->findAll(),
            'schoolYears' => $this->schoolYearModel->orderBy('name', 'ASC')->findAll(),
            'strands' => $this->strandModel->orderBy('name', 'ASC')->findAll(),
            'statuses' => $this->statusModel->orderBy('name', 'ASC')->findAll(),
            'advisers' => $this->getSelectableExperts(\App\Models\ExpertModel::ROLE_ADVISER, $this->adviserModel),
            'grammarians' => $this->getSelectableExperts(\App\Models\ExpertModel::ROLE_GRAMMARIAN, $this->grammarianModel),
            'statisticians' => $this->getSelectableExperts(\App\Models\ExpertModel::ROLE_STATISTICIAN, $this->statisticianModel),
            'researchTeachers' => $this->researchTeacherModel->orderBy('name', 'ASC')->findAll(),
            'abstracts' => $this->abstractModel->orderBy('name', 'ASC')->findAll(),
            'defenseStatuses' => $this->defenseStatusModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/researchers/edit', $data);
    }

    public function update($id)
    {
        $researcher = $this->researcherModel->find($id);
        if (!$researcher) {
            return redirect()->to('admin/researchers')->with('error', 'Researcher not found.');
        }

        $rules = [
            'surname'         => 'required|min_length[2]',
            'category_id'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $members = $this->request->getPost('surname');
        
        // Use first member as fullname for display
        $fullname = trim(explode("\n", $members)[0]) ?? 'Member';

        $strandValue = $this->request->getPost('strand_id');
        $strand = $this->resolveStrandId($strandValue);
        $strandId = $strand['id'];
        $strandName = $strand['name'];

        $data = [
            'fullname'                  => $fullname,
            'author'                    => $this->request->getPost('author') ?: null,
            'surname'                   => $members,
            'first_name'                => '',
            'middle_initial'            => '',
            'ext_name'                  => '',
            'designation_id'            => $this->request->getPost('designation_id') ?: null,
            'school_year_id'            => $this->request->getPost('school_year_id') ?: null,
            'strand_id'                 => $strandId ?: null,
            'strand_degree_program'     => $strandName,
            'status_id'                 => $this->request->getPost('status_id') ?: null,
            'adviser_id'                => $this->request->getPost('adviser_id') ?: null,
            'grammarian_id'             => $this->request->getPost('grammarian_id') ?: null,
            'remark_id'                 => $this->request->getPost('remark_id') ?: null,
            'research_teacher_id'       => $this->request->getPost('research_teacher_id') ?: null,
            'abstract'                  => $this->request->getPost('abstract') ?: null,
            'category_id'               => $this->request->getPost('category_id'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
            'approved_date'             => $this->request->getPost('approved_date') ?: null,
            'pre_oral_defense_date'     => $this->request->getPost('pre_oral_defense_date') ?: null,
            'pre_oral_defense_status_id'   => $this->request->getPost('pre_oral_defense_status_id') ?: null,
            'final_defense_date'        => $this->request->getPost('final_defense_date') ?: null,
            'final_defense_status_id'      => $this->request->getPost('final_defense_status_id') ?: null,
            'remarks'                   => $this->request->getPost('remarks'),
            'status'                    => $this->request->getPost('status') ?? 'active'
        ];

        // Abstract Requirement: Prevent marking as completed without submitted abstract
        $statusId = $data['status_id'] ?? null;
        $statusObj = $statusId ? $this->statusModel->find($statusId) : null;
        $statusName = strtolower($statusObj['name'] ?? '');
        if ((str_contains($statusName, 'complete') && !str_contains($statusName, 'not')) || ($data['status'] === 'completed')) {
            if (empty(trim((string)($data['abstract'] ?? '')))) {
                return redirect()->back()->withInput()->with('error', 'Cannot mark research as completed. An approved abstract must be submitted after passing the Final Defense before the research project can be marked as completed.');
            }
        }

        if ($this->researcherModel->update($id, $data)) {
            AuditLogModel::log('RESEARCHER_UPDATE', 'researchers', $id, [
                'fullname' => $data['fullname'],
                'approved_research_title' => $data['approved_research_title'] ?? null,
                'category_id' => $data['category_id'],
                'strand_id' => $data['strand_id'] ?? null,
                'status' => $data['status'],
            ]);
            $from = $this->request->getPost('from');
            if ($from === 'update_status') {
                return redirect()->to('admin/researchers/update-status')->with('success', 'Researcher profile updated successfully.');
            }
            return redirect()->to('admin/researchers')->with('success', 'Researcher profile updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update researcher profile.');
    }

    public function addCategory()
    {
        return $this->addEntity($this->categoryModel);
    }

    public function addDesignation()
    {
        return $this->addEntity($this->designationModel);
    }

    public function addSchoolYear()
    {
        return $this->addEntity($this->schoolYearModel);
    }

    public function addStrand()
    {
        return $this->addEntity($this->strandModel);
    }

    public function editCategory()
    {
        return $this->editEntity($this->categoryModel);
    }

    public function editDesignation()
    {
        return $this->editEntity($this->designationModel);
    }

    public function editSchoolYear()
    {
        return $this->editEntity($this->schoolYearModel);
    }

    public function editStrand()
    {
        return $this->editEntity($this->strandModel);
    }

    public function deleteCategory($id)
    {
        return $this->deleteEntity($this->categoryModel, $id);
    }

    public function deleteDesignation($id)
    {
        return $this->deleteEntity($this->designationModel, $id);
    }

    public function deleteSchoolYear($id)
    {
        return $this->deleteEntity($this->schoolYearModel, $id);
    }

    public function deleteStrand($id)
    {
        return $this->deleteEntity($this->strandModel, $id);
    }

    public function addCourse()
    {
        return $this->addEntity($this->courseModel);
    }

    public function editCourse()
    {
        return $this->editEntity($this->courseModel);
    }

    public function deleteCourse($id)
    {
        return $this->deleteEntity($this->courseModel, $id);
    }

    public function addStatus()
    {
        return $this->addEntity($this->statusModel);
    }

    public function editStatus()
    {
        return $this->editEntity($this->statusModel);
    }

    public function deleteStatus($id)
    {
        return $this->deleteEntity($this->statusModel, $id);
    }

    public function addAdviser()
    {
        return $this->addEntity($this->adviserModel);
    }

    public function editAdviser()
    {
        return $this->editEntity($this->adviserModel);
    }

    public function deleteAdviser($id)
    {
        return $this->deleteEntity($this->adviserModel, $id);
    }

    public function addGrammarian()
    {
        return $this->addEntity($this->grammarianModel);
    }

    public function editGrammarian()
    {
        return $this->editEntity($this->grammarianModel);
    }

    public function deleteGrammarian($id)
    {
        return $this->deleteEntity($this->grammarianModel, $id);
    }

    public function addRemark()
    {
        return $this->addEntity($this->statisticianModel);
    }

    public function editRemark()
    {
        return $this->editEntity($this->statisticianModel);
    }

    public function deleteRemark($id)
    {
        return $this->deleteEntity($this->statisticianModel, $id);
    }

    public function addResearchTeacher()
    {
        return $this->addEntity($this->researchTeacherModel);
    }

    public function editResearchTeacher()
    {
        return $this->editEntity($this->researchTeacherModel);
    }

    public function deleteResearchTeacher($id)
    {
        return $this->deleteEntity($this->researchTeacherModel, $id);
    }

    public function addDefenseStatus()
    {
        return $this->addEntity($this->defenseStatusModel);
    }

    public function editDefenseStatus()
    {
        return $this->editEntity($this->defenseStatusModel);
    }

    public function deleteDefenseStatus($id)
    {
        return $this->deleteEntity($this->defenseStatusModel, $id);
    }

    public function addAbstract()
    {
        return $this->addEntity($this->abstractModel);
    }

    public function editAbstract()
    {
        return $this->editEntity($this->abstractModel);
    }

    public function deleteAbstract($id)
    {
        return $this->deleteEntity($this->abstractModel, $id);
    }

    private function tableNameFromModel(object $model): string
    {
        if (method_exists($model, 'getTable')) {
            return (string) $model->getTable();
        }
        $tbl = $model->table ?? null;
        if (is_string($tbl) && $tbl !== '') {
            return $tbl;
        }
        $cls = (new \ReflectionClass($model))->getShortName();
        $cls = preg_replace('/Model$/', '', $cls);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $cls));
    }

    private function resolveStrandId($value): ?array
    {
        if (empty($value)) {
            return ['id' => null, 'name' => null];
        }
        if (is_numeric($value)) {
            $strand = $this->strandModel->find((int) $value);
            return $strand ? ['id' => (int) $value, 'name' => $strand['name']] : ['id' => null, 'name' => null];
        }
        $name = trim((string) $value);
        $existing = $this->strandModel->where('name', $name)->first();
        if ($existing) {
            return ['id' => (int) $existing['id'], 'name' => $existing['name']];
        }
        $this->strandModel->insert(['name' => $name]);
        return ['id' => (int) $this->strandModel->getInsertID(), 'name' => $name];
    }

    private function addEntity($model)
    {
        $name = $this->request->getPost('name');
        if (empty($name)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Name is required']);
            }
            return redirect()->back()->withInput()->with('error', 'Name is required');
        }

        if ($model->where('name', $name)->first()) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Already exists']);
            }
            return redirect()->back()->withInput()->with('error', 'Already exists');
        }

        $id = $model->insert(['name' => $name]);
        if ($id) {
            $table = $this->tableNameFromModel($model);
            AuditLogModel::log('MASTERLIST_ADD', $table, $id, ['name' => $name]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'id' => $id,
                    'name' => $name
                ]);
            }
            return redirect()->back()->with('success', 'Added successfully');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Failed to save']);
        }
        return redirect()->back()->withInput()->with('error', 'Failed to save');
    }

    private function editEntity($model)
    {
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        
        if (empty($id) || empty($name)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'ID and name are required']);
            }
            return redirect()->back()->withInput()->with('error', 'ID and name are required');
        }

        $entity = $model->find($id);
        if (!$entity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Not found']);
            }
            return redirect()->back()->with('error', 'Not found');
        }

        $existing = $model->where('name', $name)->where('id !=', $id)->first();
        if ($existing) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Already exists']);
            }
            return redirect()->back()->withInput()->with('error', 'Already exists');
        }

        if ($model->update($id, ['name' => $name])) {
            $table = $this->tableNameFromModel($model);
            AuditLogModel::log('MASTERLIST_EDIT', $table, $id, ['name' => $name]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'id' => $id,
                    'name' => $name
                ]);
            }
            return redirect()->back()->with('success', 'Updated successfully');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Failed to update']);
        }
        return redirect()->back()->withInput()->with('error', 'Failed to update');
    }

    private function deleteEntity($model, $id)
    {
        $existing = $model->find($id);
        if ($model->delete($id)) {
            $table = $this->tableNameFromModel($model);
            AuditLogModel::log('MASTERLIST_DELETE', $table, $id, $existing ?? ['id' => $id]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true]);
            }
            return redirect()->back()->with('success', 'Deleted successfully');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Failed to delete']);
        }
        return redirect()->back()->with('error', 'Failed to delete');
    }

    public function delete($id)
    {
        $existing = $this->researcherModel->find($id);
        if ($this->researcherModel->delete($id)) {
            AuditLogModel::log('RESEARCHER_DELETE', 'researchers', $id, $existing ?? ['id' => $id]);
            return redirect()->to('admin/researchers')->with('success', 'Researcher profile deleted successfully.');
        }
        return redirect()->to('admin/researchers')->with('error', 'Failed to delete researcher profile.');
    }

    public function highSchool()
    {
        $search = $this->request->getGet('search');
        $strand = $this->request->getGet('strand');
        $schoolYear = $this->request->getGet('school_year');
        $category = $this->request->getGet('category');
        $adviser = $this->request->getGet('adviser');
        $grammarian = $this->request->getGet('grammarian');
        $statistician = $this->request->getGet('statistician');
        $researchTeacher = $this->request->getGet('research_teacher');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left')
                                     ->where('research_categories.name', 'High School Department');

        if ($strand) {
            $query->where('researchers.strand_degree_program', $strand);
        }

        if ($schoolYear) {
            $query->where('researchers.school_year_id', $schoolYear);
        }

        if ($category) {
            $query->where('researchers.category_id', $category);
        }

        if ($adviser) {
            $query->where('researchers.adviser_id', $adviser);
        }

        if ($grammarian) {
            $query->where('researchers.grammarian_id', $grammarian);
        }

        if ($statistician) {
            $query->where('researchers.remark_id', $statistician);
        }

        if ($researchTeacher) {
            $query->where('researchers.research_teacher_id', $researchTeacher);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $pager = \Config\Services::pager();
        $researchers = $query->orderBy('researchers.created_at', 'DESC')->paginate(10, 'highSchool');

        $data = [
            'title' => 'High School Department',
            'page_title' => 'High School Department Researchers',
            'researchers' => $researchers,
            'pager' => $pager,
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'search' => $search,
            'strand' => $strand,
            'schoolYear' => $schoolYear,
            'category' => $category,
            'adviser' => $adviser,
            'grammarian' => $grammarian,
            'statistician' => $statistician,
            'researchTeacher' => $researchTeacher
        ];

        return view('admin/researchers/high_school', $data);
    }

    public function college()
    {
        $search = $this->request->getGet('search');
        $strand = $this->request->getGet('strand');
        $schoolYear = $this->request->getGet('school_year');
        $category = $this->request->getGet('category');
        $adviser = $this->request->getGet('adviser');
        $grammarian = $this->request->getGet('grammarian');
        $statistician = $this->request->getGet('statistician');
        $researchTeacher = $this->request->getGet('research_teacher');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left')
                                     ->where('research_categories.name', 'College Department');

        if ($strand) {
            $query->where('researchers.strand_degree_program', $strand);
        }

        if ($schoolYear) {
            $query->where('researchers.school_year_id', $schoolYear);
        }

        if ($category) {
            $query->where('researchers.category_id', $category);
        }

        if ($adviser) {
            $query->where('researchers.adviser_id', $adviser);
        }

        if ($grammarian) {
            $query->where('researchers.grammarian_id', $grammarian);
        }

        if ($statistician) {
            $query->where('researchers.remark_id', $statistician);
        }

        if ($researchTeacher) {
            $query->where('researchers.research_teacher_id', $researchTeacher);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $pager = \Config\Services::pager();
        $researchers = $query->orderBy('researchers.created_at', 'DESC')->paginate(10, 'college');

        $data = [
            'title' => 'College Department',
            'page_title' => 'College Department Researchers',
            'researchers' => $researchers,
            'pager' => $pager,
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'search' => $search,
            'strand' => $strand,
            'schoolYear' => $schoolYear,
            'category' => $category,
            'adviser' => $adviser,
            'grammarian' => $grammarian,
            'statistician' => $statistician,
            'researchTeacher' => $researchTeacher
        ];

        return view('admin/researchers/college', $data);
    }

    public function updateStatus($id)
    {
        $researcher = $this->researcherModel->find($id);
        if (!$researcher) {
            return redirect()->to('admin/researchers')->with('error', 'Researcher not found.');
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['active', 'inactive', 'on_leave', 'completed'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        if ($this->researcherModel->update($id, ['status' => $status])) {
            return redirect()->to('admin/researchers')->with('success', 'Status updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update status.');
    }

    public function updateStatusPage()
    {
        $categoryFilter = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        
        $query = $this->researcherModel->select('researchers.*, research_categories.name as category_name, statuses.name as status_name, pre_oral_defense_statuses.name as pre_oral_defense_status_name, final_defense_statuses.name as final_defense_status_name')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('statuses', 'statuses.id = researchers.status_id', 'left')
                                     ->join('defense_statuses as pre_oral_defense_statuses', 'pre_oral_defense_statuses.id = researchers.pre_oral_defense_status_id', 'left')
                                     ->join('defense_statuses as final_defense_statuses', 'final_defense_statuses.id = researchers.final_defense_status_id', 'left');

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->orLike('researchers.fullname', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Update Status',
            'page_title' => 'Update Researcher Status',
            'researchers' => $query->orderBy('researchers.fullname', 'ASC')->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'statuses' => $this->statusModel->findAll(),
            'selectedCategory' => $categoryFilter,
            'search' => $search
        ];

        return view('admin/researchers/update_status', $data);
    }

    public function saveStatus($id)
    {
        $researcher = $this->researcherModel->find($id);
        if (!$researcher) {
            return redirect()->to('admin/researchers/update-status')->with('error', 'Researcher not found.');
        }

        $statusId = $this->request->getPost('status_id');

        // Abstract Requirement Check: Prevent marking as completed without submitted abstract
        $statusObj = $statusId ? $this->statusModel->find($statusId) : null;
        $statusName = strtolower($statusObj['name'] ?? '');
        if (str_contains($statusName, 'complete') && !str_contains($statusName, 'not')) {
            if (empty(trim((string)($researcher['abstract'] ?? '')))) {
                return redirect()->to('admin/researchers/update-status')->with('error', 'Cannot mark research as completed. An approved abstract must be submitted after passing the Final Defense before the research project can be completed.');
            }
        }

        if ($this->researcherModel->update($id, ['status_id' => $statusId])) {
            AuditLogModel::log('RESEARCHER_STATUS_UPDATE', 'researchers', $id, [
                'status_id' => $statusId,
                'status_name' => $statusObj['name'] ?? 'N/A',
            ]);
            return redirect()->to('admin/researchers/update-status')->with('success', 'Status updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update status.');
    }

    public function submitAbstract($id)
    {
        $researcher = $this->researcherModel->find($id);
        if (!$researcher) {
            return redirect()->back()->with('error', 'Researcher record not found.');
        }

        $abstractText = trim((string) $this->request->getPost('abstract'));
        if (empty($abstractText)) {
            return redirect()->back()->with('error', 'Abstract content cannot be empty.');
        }

        $this->researcherModel->update($id, ['abstract' => $abstractText]);

        // Sync with research_titles if matched by researcher_id
        if (class_exists(\App\Models\ResearchTitleModel::class)) {
            $titleModel = new \App\Models\ResearchTitleModel();
            $matchedTitle = $titleModel->where('researcher_id', $id)->first();
            if ($matchedTitle) {
                $titleModel->update($matchedTitle['id'], ['abstract' => $abstractText]);
            }
        }

        AuditLogModel::log('RESEARCH_ABSTRACT_SUBMIT', 'researchers', $id, [
            'researcher_id' => $id,
            'fullname'      => $researcher['fullname'] ?? '',
            'title'         => $researcher['approved_research_title'] ?? '',
        ]);

        return redirect()->back()->with('success', 'Research abstract submitted and saved successfully.');
    }

    public function ajaxGetAbstract($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $query = $this->researcherModel->select('researchers.*, defense_statuses.name as final_defense_status_name')
            ->join('defense_statuses', 'defense_statuses.id = researchers.final_defense_status_id', 'left')
            ->where('researchers.id', $id)
            ->first();

        if (!$query) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Record not found']);
        }

        return $this->response->setJSON([
            'status'               => 'success',
            'id'                   => $query['id'],
            'fullname'             => $query['fullname'] ?: ($query['surname'] . ', ' . $query['first_name']),
            'title'                => $query['approved_research_title'] ?? '',
            'final_defense_date'   => $query['final_defense_date'] ? date('M d, Y', strtotime($query['final_defense_date'])) : 'Not yet scheduled',
            'final_defense_status' => $query['final_defense_status_name'] ?? 'Pending',
            'abstract'             => $query['abstract'] ?? '',
            'has_abstract'         => !empty(trim($query['abstract'] ?? '')),
        ]);
    }

    public function researchTeachers()
    {
        $schoolYearFilter = $this->request->getGet('school_year');
        $categoryFilter = $this->request->getGet('category');
        $strandFilter = $this->request->getGet('strand');
        $adviserFilter = $this->request->getGet('adviser');
        $grammarianFilter = $this->request->getGet('grammarian');
        $statisticianFilter = $this->request->getGet('statistician');
        $researchTeacherFilter = $this->request->getGet('research_teacher');
        $search = $this->request->getGet('search');

        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left');

        if ($schoolYearFilter) {
            $query->where('researchers.school_year_id', $schoolYearFilter);
        }

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($strandFilter) {
            $query->where('researchers.strand_id', $strandFilter);
        }

        if ($adviserFilter) {
            $query->where('researchers.adviser_id', $adviserFilter);
        }

        if ($grammarianFilter) {
            $query->where('researchers.grammarian_id', $grammarianFilter);
        }

        if ($statisticianFilter) {
            $query->where('researchers.remark_id', $statisticianFilter);
        }

        if ($researchTeacherFilter) {
            $query->where('researchers.research_teacher_id', $researchTeacherFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $query->orderBy('researchers.created_at', 'DESC');

        $data = [
            'title' => 'Research Teachers',
            'page_title' => 'Research by Research Teachers',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'schoolYear' => $schoolYearFilter,
            'category' => $categoryFilter,
            'strand' => $strandFilter,
            'adviser' => $adviserFilter,
            'grammarian' => $grammarianFilter,
            'statistician' => $statisticianFilter,
            'researchTeacher' => $researchTeacherFilter,
            'search' => $search
        ];

        return view('admin/researchers/research_teachers', $data);
    }

    public function grammarians()
    {
        $schoolYearFilter = $this->request->getGet('school_year');
        $categoryFilter = $this->request->getGet('category');
        $strandFilter = $this->request->getGet('strand');
        $adviserFilter = $this->request->getGet('adviser');
        $grammarianFilter = $this->request->getGet('grammarian');
        $statisticianFilter = $this->request->getGet('statistician');
        $researchTeacherFilter = $this->request->getGet('research_teacher');
        $search = $this->request->getGet('search');

        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left');

        if ($schoolYearFilter) {
            $query->where('researchers.school_year_id', $schoolYearFilter);
        }

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($strandFilter) {
            $query->where('researchers.strand_id', $strandFilter);
        }

        if ($adviserFilter) {
            $query->where('researchers.adviser_id', $adviserFilter);
        }

        if ($grammarianFilter) {
            $query->where('researchers.grammarian_id', $grammarianFilter);
        }

        if ($statisticianFilter) {
            $query->where('researchers.remark_id', $statisticianFilter);
        }

        if ($researchTeacherFilter) {
            $query->where('researchers.research_teacher_id', $researchTeacherFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $query->orderBy('researchers.created_at', 'DESC');

        $data = [
            'title' => 'Grammarians',
            'page_title' => 'Research by Grammarians',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'schoolYear' => $schoolYearFilter,
            'category' => $categoryFilter,
            'strand' => $strandFilter,
            'adviser' => $adviserFilter,
            'grammarian' => $grammarianFilter,
            'statistician' => $statisticianFilter,
            'researchTeacher' => $researchTeacherFilter,
            'search' => $search
        ];

        return view('admin/researchers/grammarians_list', $data);
    }

    public function statisticians()
    {
        $schoolYearFilter = $this->request->getGet('school_year');
        $categoryFilter = $this->request->getGet('category');
        $strandFilter = $this->request->getGet('strand');
        $adviserFilter = $this->request->getGet('adviser');
        $grammarianFilter = $this->request->getGet('grammarian');
        $statisticianFilter = $this->request->getGet('statistician');
        $researchTeacherFilter = $this->request->getGet('research_teacher');
        $search = $this->request->getGet('search');

        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left');

        if ($schoolYearFilter) {
            $query->where('researchers.school_year_id', $schoolYearFilter);
        }

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($strandFilter) {
            $query->where('researchers.strand_id', $strandFilter);
        }

        if ($adviserFilter) {
            $query->where('researchers.adviser_id', $adviserFilter);
        }

        if ($grammarianFilter) {
            $query->where('researchers.grammarian_id', $grammarianFilter);
        }

        if ($statisticianFilter) {
            $query->where('researchers.remark_id', $statisticianFilter);
        }

        if ($researchTeacherFilter) {
            $query->where('researchers.research_teacher_id', $researchTeacherFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $query->orderBy('researchers.created_at', 'DESC');

        $data = [
            'title' => 'Statisticians',
            'page_title' => 'Research by Statisticians',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'schoolYear' => $schoolYearFilter,
            'category' => $categoryFilter,
            'strand' => $strandFilter,
            'adviser' => $adviserFilter,
            'grammarian' => $grammarianFilter,
            'statistician' => $statisticianFilter,
            'researchTeacher' => $researchTeacherFilter,
            'search' => $search
        ];

        return view('admin/researchers/statisticians_list', $data);
    }

    public function advisers()
    {
        $schoolYearFilter = $this->request->getGet('school_year');
        $categoryFilter = $this->request->getGet('category');
        $strandFilter = $this->request->getGet('strand');
        $adviserFilter = $this->request->getGet('adviser');
        $grammarianFilter = $this->request->getGet('grammarian');
        $statisticianFilter = $this->request->getGet('statistician');
        $researchTeacherFilter = $this->request->getGet('research_teacher');
        $search = $this->request->getGet('search');

        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as statistician_name, research_teachers.name as research_teacher_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left')
                                     ->join('research_teachers', 'research_teachers.id = researchers.research_teacher_id', 'left');

        if ($schoolYearFilter) {
            $query->where('researchers.school_year_id', $schoolYearFilter);
        }

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($strandFilter) {
            $query->where('researchers.strand_id', $strandFilter);
        }

        if ($adviserFilter) {
            $query->where('researchers.adviser_id', $adviserFilter);
        }

        if ($grammarianFilter) {
            $query->where('researchers.grammarian_id', $grammarianFilter);
        }

        if ($statisticianFilter) {
            $query->where('researchers.remark_id', $statisticianFilter);
        }

        if ($researchTeacherFilter) {
            $query->where('researchers.research_teacher_id', $researchTeacherFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->orLike('researchers.author', $search)
                  ->orLike('researchers.surname', $search)
                  ->orLike('researchers.approved_research_title', $search)
                  ->groupEnd();
        }

        $query->orderBy('researchers.created_at', 'DESC');

        $data = [
            'title' => 'Advisers',
            'page_title' => 'Research by Advisers',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'schoolYears' => $this->schoolYearModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'statisticians' => $this->statisticianModel->findAll(),
            'researchTeachers' => $this->researchTeacherModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'schoolYear' => $schoolYearFilter,
            'category' => $categoryFilter,
            'strand' => $strandFilter,
            'adviser' => $adviserFilter,
            'grammarian' => $grammarianFilter,
            'statistician' => $statisticianFilter,
            'researchTeacher' => $researchTeacherFilter,
            'search' => $search
        ];

        return view('admin/researchers/advisers_list', $data);
    }

    public function assignTeacher($id)
    {
        $researcher = $this->researcherModel->find($id);
        if (!$researcher) {
            return redirect()->to('admin/researchers')->with('error', 'Researcher record not found.');
        }

        $teacherId = $this->request->getPost('research_teacher_id') ?: null;
        $this->researcherModel->update($id, ['research_teacher_id' => $teacherId]);

        // Keep associated user account assigned instructor in sync
        if (!empty($researcher['user_id']) && $teacherId) {
            (new \App\Models\User())->update($researcher['user_id'], ['assigned_instructor_id' => $teacherId]);
        }

        // Keep associated research_titles in sync
        (new \App\Models\ResearchTitleModel())
            ->where('researcher_id', $id)
            ->set(['instructor_id' => $teacherId])
            ->update();

        $teacherName = 'Unassigned';
        if ($teacherId) {
            $t = $this->researchTeacherModel->find($teacherId);
            $teacherName = $t['name'] ?? "Instructor #{$teacherId}";
        }

        AuditLogModel::log('ASSIGN_RESEARCH_TEACHER', 'researchers', $id, [
            'researcher_id'       => $id,
            'researcher_name'     => $researcher['fullname'] ?? $researcher['author'],
            'research_teacher_id' => $teacherId,
            'teacher_name'        => $teacherName,
            'assigned_by'         => session()->get('username') ?? 'Admin',
        ]);

        return redirect()->to('admin/researchers')->with('success', 'Teacher/Research Instructor assigned successfully to ' . esc($researcher['fullname'] ?? $researcher['author']) . '.');
    }
}
