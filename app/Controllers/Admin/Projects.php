<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class Projects extends BaseController
{
    private ProjectModel $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }

    public function index()
    {
        $search = (string) $this->request->getGet('search');
        $search = trim($search);

        $query = $this->projectModel->orderBy('created_at', 'DESC');
        if ($search !== '') {
            $query->groupStart()
                ->like('title', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }

        return view('admin/projects/index', [
            'title' => 'Projects',
            'page_title' => 'Projects',
            'projects' => $query->findAll(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin/projects/create', [
            'title' => 'Create Project',
            'page_title' => 'Create Project',
        ]);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[150]',
            'description' => 'permit_empty|max_length[2000]',
            'status' => 'required|in_list[draft,active,completed]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => (string) $this->request->getPost('title'),
            'description' => (string) $this->request->getPost('description'),
            'status' => (string) $this->request->getPost('status'),
        ];

        if ($this->projectModel->insert($data)) {
            return redirect()->to('admin/projects')->with('success', 'Project created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create project.');
    }
}

