<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResearcherModel;
use App\Models\ResearchCategoryModel;

class Researchers extends BaseController
{
    protected $researcherModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->researcherModel = new ResearcherModel();
        $this->categoryModel = new ResearchCategoryModel();
    }

    public function index()
    {
        $categoryFilter = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name')
                                     ->join('users', 'users.id = researchers.user_id')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left');

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('researchers.institutional_id', $search)
                  ->orLike('users.username', $search)
                  ->orLike('users.email', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Researchers Directory',
            'page_title' => 'Researchers List',
            'researchers' => $query->orderBy('researchers.created_at', 'DESC')->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'selectedCategory' => $categoryFilter,
            'search' => $search
        ];

        return view('admin/researchers/index', $data);
    }

    public function create()
    {
        $userModel = new \App\Models\User();
        
        $existingUserIds = $this->researcherModel->findColumn('user_id') ?: [0];
        $availableUsers = $userModel->whereNotIn('id', $existingUserIds)
                                   ->where('role', 'user')
                                   ->orderBy('username', 'ASC')
                                   ->findAll();

        $data = [
            'title' => 'Add New Researcher',
            'page_title' => 'Assign Researcher Profile',
            'users' => $availableUsers,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'suggestedInstitutionalId' => $this->generateNextInstitutionalId(),
        ];

        return view('admin/researchers/create', $data);
    }

    private function generateNextInstitutionalId(): string
    {
        $year = date('Y');
        $prefix = "SRO-{$year}-";

        $last = $this->researcherModel
            ->select('institutional_id')
            ->like('institutional_id', $prefix, 'after')
            ->orderBy('institutional_id', 'DESC')
            ->first();

        $lastNumber = 0;
        if (is_array($last) && isset($last['institutional_id'])) {
            if (preg_match('/\A' . preg_quote($prefix, '/') . '(\d{4})\z/', $last['institutional_id'], $m)) {
                $lastNumber = (int) $m[1];
            }
        }

        return $prefix . str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        $institutionalId = (string) $this->request->getPost('institutional_id');
        $institutionalId = trim($institutionalId);

        // If user left the default prefix, generate a real unique ID.
        if ($institutionalId === '' || preg_match('/\ASRO-\d{4}-\z/', $institutionalId) === 1) {
            $institutionalId = $this->generateNextInstitutionalId();
            // Ensure the validator sees the generated value.
            $this->request->setGlobal('post', array_merge($this->request->getPost(), [
                'institutional_id' => $institutionalId,
            ]));
        }

        $rules = [
            'user_id'          => 'required|is_unique[researchers.user_id]',
            'fullname'         => 'required|min_length[3]',
            'institutional_id' => 'required|is_unique[researchers.institutional_id]',
            'category_id'      => 'required',
            'strand_degree_program' => 'permit_empty|in_list[HUMSS,STEM,ABM]',
            'joined_at'        => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id'                   => $this->request->getPost('user_id'),
            'fullname'                  => $this->request->getPost('fullname'),
            'institutional_id'          => $institutionalId,
            'school_year'               => $this->request->getPost('school_year'),
            'category_id'               => $this->request->getPost('category_id'),
            'expertise'                 => $this->request->getPost('expertise'),
            'strand_degree_program'     => $this->request->getPost('strand_degree_program'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
            'bio'                       => $this->request->getPost('bio'),
            'joined_at'                 => $this->request->getPost('joined_at'),
        ];

        if ($this->researcherModel->insert($data)) {
            return redirect()->to('admin/researchers')->with('success', 'Researcher profile created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create researcher profile.');
    }

    public function edit($id)
    {
        $researcher = $this->researcherModel->select('researchers.*, users.username')
                                           ->join('users', 'users.id = researchers.user_id')
                                           ->find($id);
        
        if (!$researcher) {
            return redirect()->to('admin/researchers')->with('error', 'Researcher not found.');
        }

        $data = [
            'title' => 'Edit Researcher',
            'page_title' => 'Update Profile: ' . $researcher['username'],
            'researcher' => $researcher,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll()
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
            'fullname'         => 'required|min_length[3]',
            'institutional_id' => "required|is_unique[researchers.institutional_id,id,{$id}]",
            'category_id'      => 'required',
            'strand_degree_program' => 'permit_empty|in_list[HUMSS,STEM,ABM]',
            'joined_at'        => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'fullname'                  => $this->request->getPost('fullname'),
            'institutional_id'          => $this->request->getPost('institutional_id'),
            'school_year'               => $this->request->getPost('school_year'),
            'category_id'               => $this->request->getPost('category_id'),
            'expertise'                 => $this->request->getPost('expertise'),
            'strand_degree_program'     => $this->request->getPost('strand_degree_program'),
            'approved_research_title'   => $this->request->getPost('approved_research_title'),
            'bio'                       => $this->request->getPost('bio'),
            'joined_at'                 => $this->request->getPost('joined_at'),
        ];

        if ($this->researcherModel->update($id, $data)) {
            return redirect()->to('admin/researchers')->with('success', 'Researcher profile updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update researcher profile.');
    }

    public function addCategory()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $name = $this->request->getPost('name');
        if (empty($name)) {
            return $this->response->setJSON(['error' => 'Category name is required']);
        }

        if ($this->categoryModel->where('name', $name)->first()) {
            return $this->response->setJSON(['error' => 'Category already exists']);
        }

        $id = $this->categoryModel->insert(['name' => $name]);
        if ($id) {
            return $this->response->setJSON([
                'success' => true,
                'id' => $id,
                'name' => $name
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to save category']);
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
                                     ->join('users', 'users.id = researchers.user_id')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->where('research_categories.name', 'High School Department');

        if ($strand && in_array($strand, ['HUMSS', 'STEM', 'ABM'], true)) {
            $query->where('researchers.strand_degree_program', $strand);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('researchers.institutional_id', $search)
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
                                     ->join('users', 'users.id = researchers.user_id')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                     ->where('research_categories.name', 'College Department');

        if ($strand && in_array($strand, ['HUMSS', 'STEM', 'ABM'], true)) {
            $query->where('researchers.strand_degree_program', $strand);
        }

        if ($search) {
            $query->groupStart()
                  ->like('researchers.fullname', $search)
                  ->orLike('researchers.institutional_id', $search)
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
}
