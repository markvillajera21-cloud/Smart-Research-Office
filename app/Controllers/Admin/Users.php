<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User;
use App\Models\AuditLogModel;
use Config\Departments;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        if (! can_manage_users()) {
            return redirect()->to(role_home())->with('error', 'Access Denied: Only Administrator can manage user accounts.');
        }
        $data['users'] = $this->userModel->findAll();
        return view('admin/users/index', $data);
    }

    public function create()
    {
        if (! can_manage_users()) {
            return redirect()->to(role_home())->with('error', 'Access Denied: Only Administrator can create user accounts.');
        }
        $redirectTo = $this->request->getGet('redirect');
        return view('admin/users/create', ['redirectTo' => $redirectTo, 'departments' => Departments::choices()]);
    }

    public function store()
    {
        if (! can_manage_users()) {
            return redirect()->to(role_home())->with('error', 'Access Denied: Only Administrator can create user accounts.');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
            'department' => $this->request->getPost('department') ?: null,
        ];

        if ($this->userModel->save($data)) {
            $newId = $this->userModel->getInsertID() ?: null;
            AuditLogModel::log('USER_CREATE', 'users', $newId, [
                'username'   => $data['username'],
                'email'      => $data['email'],
                'role'       => $data['role'],
                'department' => $data['department'] ?? null,
            ]);

            $redirectTo = (string) $this->request->getPost('redirect_to');
            $redirectTo = trim($redirectTo);

            // Only allow safe internal redirects (e.g. "admin/researchers/create")
            if ($redirectTo !== '' && preg_match('/\A[a-zA-Z0-9\/\-_]+\z/', $redirectTo) === 1) {
                return redirect()->to('/' . ltrim($redirectTo, '/'))->with('success', 'User created successfully');
            }

            return redirect()->to('/admin/users')->with('success', 'User created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    public function edit($id)
    {
        if (! can_manage_users()) {
            return redirect()->to(role_home())->with('error', 'Access Denied: Only Administrator can edit user accounts.');
        }

        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }
        $data['departments'] = Departments::choices();
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        if (! can_manage_users()) {
            return redirect()->to(role_home())->with('error', 'Access Denied: Only Administrator can update user accounts.');
        }

        $existingUser = $this->userModel->find($id);
        if (!$existingUser) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $data = [
            'id'       => $id,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'department' => $this->request->getPost('department') ?: null,
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        if ($this->userModel->save($data)) {
            AuditLogModel::log('USER_UPDATE', 'users', $id, [
                'username'   => $data['username'],
                'email'      => $data['email'],
                'role'       => $data['role'],
                'department' => $data['department'] ?? null,
                'password_changed' => !empty($password),
            ]);
            return redirect()->to('/admin/users')->with('success', 'User updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    public function delete($id)
    {
        if (! can_manage_users()) {
            return redirect()->to(role_home())->with('error', 'Access Denied: Only Administrator can delete user accounts.');
        }

        $existing = $this->userModel->find($id);
        if (!$existing) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        // Prevent deleting the main logged-in admin account directly
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Cannot delete your own active administrator account.');
        }

        if ($this->userModel->delete($id)) {
            AuditLogModel::log('USER_DELETE', 'users', $id, $existing ?? ['id' => $id]);
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully');
        }
        return redirect()->to('/admin/users')->with('error', 'Failed to delete user');
    }
}
