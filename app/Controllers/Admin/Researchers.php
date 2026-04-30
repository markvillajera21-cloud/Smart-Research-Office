<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearcherModel;
use App\Models\ResearchCategoryModel;
use App\Models\DesignationModel;
use App\Models\SchoolYearModel;
use App\Models\StrandModel;

class Researchers extends BaseController
{
    protected $researcherModel;
    protected $categoryModel;
    protected $designationModel;
    protected $schoolYearModel;
    protected $strandModel;

    public function __construct()
    {
        $this->researcherModel = new ResearcherModel();
        $this->categoryModel = new ResearchCategoryModel();
        $this->designationModel = new DesignationModel();
        $this->schoolYearModel = new SchoolYearModel();
        $this->strandModel = new StrandModel();
    }

    public function index()
    {
        $categoryFilter = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name, designations.name as designation_name, school_years.name as school_year_name, strands.name as strand_name')
                                     ->join('users', 'users.id = researchers.user_id', 'left')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->join('designations', 'designations.id = researchers.designation_id', 'left')
                                     ->join('school_years', 'school_years.id = researchers.school_year_id', 'left')
                                     ->join('strands', 'strands.id = researchers.strand_id', 'left');

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

        $data = [
            'title' => 'Research Directory',
            'page_title' => 'Research List',
            'researchers' => $query->orderBy('researchers.created_at', 'DESC')->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'selectedCategory' => $categoryFilter,
            'search' => $search
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
            'category_id'               => $this->request->getPost('category_id'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
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
            'category_id'               => $this->request->getPost('category_id'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
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
