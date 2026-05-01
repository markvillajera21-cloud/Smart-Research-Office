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
    protected $remarkModel;
    protected $abstractModel;

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
        $this->remarkModel = new RemarkModel();
        $this->abstractModel = new AbstractModel();
    }

    public function index()
    {
        $categoryFilter = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        $sort = $this->request->getGet('sort');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name, courses.name as course_name, advisers.name as adviser_name, grammarians.name as grammarian_name, remarks.name as remark_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left')
                                     ->join('courses', 'courses.id = researchers.course_id', 'left')
                                     ->join('advisers', 'advisers.id = researchers.adviser_id', 'left')
                                     ->join('grammarians', 'grammarians.id = researchers.grammarian_id', 'left')
                                     ->join('remarks', 'remarks.id = researchers.remark_id', 'left');

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->groupEnd();
        }

        switch ($sort) {
            case 'name':
                $query->orderBy('researchers.fullname', 'ASC');
                break;
            case 'designation':
                $query->orderBy('designation_name', 'ASC');
                break;
            case 'category':
                $query->orderBy('category_name', 'ASC');
                break;
            case 'course':
                $query->orderBy('course_name', 'ASC');
                break;
            case 'approved_title':
                $query->orderBy('researchers.approved_research_title', 'ASC');
                break;
            case 'approved_date':
                $query->orderBy('researchers.approved_date', 'ASC');
                break;
            case 'remarks':
                $query->orderBy('researchers.remark_id', 'ASC');
                break;
            case 'abstract':
                $query->orderBy('researchers.abstract', 'ASC');
                break;
            case 'status':
                $query->orderBy('researchers.status', 'ASC');
                break;
            case 'joining_date':
                $query->orderBy('researchers.joined_at', 'ASC');
                break;
            case 'bio':
                $query->orderBy('researchers.bio', 'ASC');
                break;
            case 'department':
                $query->orderBy('category_name', 'ASC');
                break;
            case 'adviser':
                $query->orderBy('adviser_name', 'ASC');
                break;
            case 'grammarian':
                $query->orderBy('grammarian_name', 'ASC');
                break;
            case 'degree':
                $query->orderBy('strand_name', 'ASC');
                break;
            case 'school_year':
                $query->orderBy('school_year_name', 'ASC');
                break;
            case 'date':
                $query->orderBy('researchers.created_at', 'ASC');
                break;
            default:
                $query->orderBy('researchers.created_at', 'DESC');
        }

        $data = [
            'title' => 'Research Directory',
            'page_title' => 'Research List',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'advisers' => $this->adviserModel->findAll(),
            'grammarians' => $this->grammarianModel->findAll(),
            'strands' => $this->strandModel->findAll(),
            'selectedCategory' => $categoryFilter,
            'search' => $search,
            'sort' => $sort
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
            'courses' => $this->courseModel->orderBy('name', 'ASC')->findAll(),
            'statuses' => $this->statusModel->orderBy('name', 'ASC')->findAll(),
            'advisers' => $this->adviserModel->orderBy('name', 'ASC')->findAll(),
            'grammarians' => $this->grammarianModel->orderBy('name', 'ASC')->findAll(),
            'remarks' => $this->remarkModel->orderBy('name', 'ASC')->findAll(),
            'abstracts' => $this->abstractModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/researchers/create', $data);
    }

    public function store()
    {
        $rules = [
            'surname'         => 'required|min_length[2]',
            'first_name'      => 'required|min_length[2]',
            'category_id'     => 'required',
            'joined_at'       => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $surname = $this->request->getPost('surname');
        $firstName = $this->request->getPost('first_name');
        $middleInitial = $this->request->getPost('middle_initial');
        
        $fullnameParts = [$surname, $firstName];
        if ($middleInitial) {
            $fullnameParts[] = $middleInitial . '.';
        }
        $fullname = implode(' ', $fullnameParts);

        $data = [
            'fullname'                  => $fullname,
            'surname'                   => $surname,
            'first_name'                => $firstName,
            'middle_initial'            => $middleInitial,
            'designation_id'            => $this->request->getPost('designation_id') ?: null,
            'school_year_id'            => $this->request->getPost('school_year_id') ?: null,
            'strand_id'                 => $this->request->getPost('strand_id') ?: null,
            'course_id'                 => $this->request->getPost('course_id') ?: null,
            'status_id'                 => $this->request->getPost('status_id') ?: null,
            'adviser_id'                => $this->request->getPost('adviser_id') ?: null,
            'grammarian_id'             => $this->request->getPost('grammarian_id') ?: null,
            'remark_id'                 => $this->request->getPost('remark_id') ?: null,
            'abstract'                  => $this->request->getPost('abstract') ?: null,
            'category_id'               => $this->request->getPost('category_id'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
            'approved_date'             => $this->request->getPost('approved_date') ?: null,
            'bio'                       => $this->request->getPost('bio'),
            'joined_at'                 => $this->request->getPost('joined_at'),
            'status'                    => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->researcherModel->insert($data)) {
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
            'courses' => $this->courseModel->orderBy('name', 'ASC')->findAll(),
            'statuses' => $this->statusModel->orderBy('name', 'ASC')->findAll(),
            'advisers' => $this->adviserModel->orderBy('name', 'ASC')->findAll(),
            'grammarians' => $this->grammarianModel->orderBy('name', 'ASC')->findAll(),
            'remarks' => $this->remarkModel->orderBy('name', 'ASC')->findAll(),
            'abstracts' => $this->abstractModel->orderBy('name', 'ASC')->findAll(),
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
            'first_name'      => 'required|min_length[2]',
            'category_id'     => 'required',
            'joined_at'       => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $surname = $this->request->getPost('surname');
        $firstName = $this->request->getPost('first_name');
        $middleInitial = $this->request->getPost('middle_initial');
        
        $fullnameParts = [$surname, $firstName];
        if ($middleInitial) {
            $fullnameParts[] = $middleInitial . '.';
        }
        $fullname = implode(' ', $fullnameParts);

        $data = [
            'fullname'                  => $fullname,
            'surname'                   => $surname,
            'first_name'                => $firstName,
            'middle_initial'            => $middleInitial,
            'designation_id'            => $this->request->getPost('designation_id') ?: null,
            'school_year_id'            => $this->request->getPost('school_year_id') ?: null,
            'strand_id'                 => $this->request->getPost('strand_id') ?: null,
            'course_id'                 => $this->request->getPost('course_id') ?: null,
            'status_id'                 => $this->request->getPost('status_id') ?: null,
            'adviser_id'                => $this->request->getPost('adviser_id') ?: null,
            'grammarian_id'             => $this->request->getPost('grammarian_id') ?: null,
            'remark_id'                 => $this->request->getPost('remark_id') ?: null,
            'abstract'                  => $this->request->getPost('abstract') ?: null,
            'category_id'               => $this->request->getPost('category_id'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
            'approved_date'             => $this->request->getPost('approved_date') ?: null,
            'bio'                       => $this->request->getPost('bio'),
            'joined_at'                 => $this->request->getPost('joined_at'),
            'status'                    => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->researcherModel->update($id, $data)) {
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
        return $this->addEntity($this->remarkModel);
    }

    public function editRemark()
    {
        return $this->editEntity($this->remarkModel);
    }

    public function deleteRemark($id)
    {
        return $this->deleteEntity($this->remarkModel, $id);
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

    private function addEntity($model)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $name = $this->request->getPost('name');
        if (empty($name)) {
            return $this->response->setJSON(['error' => 'Name is required']);
        }

        if ($model->where('name', $name)->first()) {
            return $this->response->setJSON(['error' => 'Already exists']);
        }

        $id = $model->insert(['name' => $name]);
        if ($id) {
            return $this->response->setJSON([
                'success' => true,
                'id' => $id,
                'name' => $name
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to save']);
    }

    private function editEntity($model)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        
        if (empty($id) || empty($name)) {
            return $this->response->setJSON(['error' => 'ID and name are required']);
        }

        $entity = $model->find($id);
        if (!$entity) {
            return $this->response->setJSON(['error' => 'Not found']);
        }

        $existing = $model->where('name', $name)->where('id !=', $id)->first();
        if ($existing) {
            return $this->response->setJSON(['error' => 'Already exists']);
        }

        if ($model->update($id, ['name' => $name])) {
            return $this->response->setJSON([
                'success' => true,
                'id' => $id,
                'name' => $name
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to update']);
    }

    private function deleteEntity($model, $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['error' => 'Failed to delete']);
    }

    public function delete($id)
    {
        if ($this->researcherModel->delete($id)) {
            return redirect()->to('admin/researchers')->with('success', 'Researcher profile deleted successfully.');
        }
        return redirect()->to('admin/researchers')->with('error', 'Failed to delete researcher profile.');
    }

    public function highSchool()
    {
        $search = $this->request->getGet('search');
        $strand = $this->request->getGet('strand');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->where('research_categories.name', 'High School Department');

        if ($strand && in_array($strand, ['HUMSS', 'STEM', 'ABM'], true)) {
            $query->where('researchers.strand_degree_program', $strand);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
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
            'search' => $search,
            'strand' => $strand
        ];

        return view('admin/researchers/high_school', $data);
    }

    public function college()
    {
        $search = $this->request->getGet('search');
        $strand = $this->request->getGet('strand');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->where('research_categories.name', 'College Department');

        if ($strand && in_array($strand, ['HUMSS', 'STEM', 'ABM'], true)) {
            $query->where('researchers.strand_degree_program', $strand);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
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
            'search' => $search,
            'strand' => $strand
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
}
