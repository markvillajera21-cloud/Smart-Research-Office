<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\User;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('admin/users/index', $data);
    }

    public function create()
    {
        return view('admin/users/create');
    }

    public function store()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
        ];

        if ($this->userModel->save($data)) {
            return redirect()->to('/admin/users')->with('success', 'User created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    public function edit($id)
    {
        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'id'       => $id,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        if ($this->userModel->save($data)) {
            return redirect()->to('/admin/users')->with('success', 'User updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully');
        }
        return redirect()->to('/admin/users')->with('error', 'Failed to delete user');
    }
}
