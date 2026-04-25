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
        
        $query = $this->researcherModel->select('researchers.*, users.username, users.email, research_categories.name as category_name')
                                     ->join('users', 'users.id = researchers.user_id')
                                     ->join('research_categories', 'research_categories.id = researchers.category_id', 'left');

        if ($categoryFilter) {
            $query->where('researchers.category_id', $categoryFilter);
        }

        $data = [
            'title' => 'Researchers Directory',
            'page_title' => 'Researchers List',
            'researchers' => $query->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'selectedCategory' => $categoryFilter
        ];

        return view('admin/researchers/index', $data);
    }

    public function create()
    {
        $userModel = new \App\Models\User();
        
        $existingUserIds = $this->researcherModel->findColumn('user_id') ?: [0];
        $availableUsers = $userModel->whereNotIn('id', $existingUserIds)
                                   ->where('role', 'user')
                                   ->findAll();

        $data = [
            'title' => 'Add New Researcher',
            'page_title' => 'Assign Researcher Profile',
            'users' => $availableUsers,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll()
        ];

        return view('admin/researchers/create', $data);
    }

    public function store()
    {
        $rules = [
            'user_id'          => 'required|is_unique[researchers.user_id]',
            'fullname'         => 'required|min_length[3]',
            'institutional_id' => 'required|is_unique[researchers.institutional_id]',
            'category_id'      => 'required',
            'expertise'        => 'permit_empty|string',
            'joined_at'        => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id'          => $this->request->getPost('user_id'),
            'fullname'         => $this->request->getPost('fullname'),
            'institutional_id' => $this->request->getPost('institutional_id'),
            'category_id'      => $this->request->getPost('category_id'),
            'expertise'        => $this->request->getPost('expertise'),
            'bio'              => $this->request->getPost('bio'),
            'joined_at'        => $this->request->getPost('joined_at'),
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
            'expertise'        => 'permit_empty|string',
            'joined_at'        => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'fullname'         => $this->request->getPost('fullname'),
            'institutional_id' => $this->request->getPost('institutional_id'),
            'category_id'      => $this->request->getPost('category_id'),
            'expertise'        => $this->request->getPost('expertise'),
            'bio'              => $this->request->getPost('bio'),
            'joined_at'        => $this->request->getPost('joined_at'),
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
}
