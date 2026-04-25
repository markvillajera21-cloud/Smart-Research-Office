<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\User;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/login');
    }

    public function doLogin()
    {
        $userModel = new User();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ]);

            return $this->redirectDashboard();
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/register');
    }

    private function redirectDashboard()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->to('/user/dashboard');
    }

    public function doRegister()
    {
        $userModel = new User();
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => 'user'
        ];

        if ($userModel->save($data)) {
            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        }

        return redirect()->back()->withInput()->with('errors', $userModel->errors());
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
