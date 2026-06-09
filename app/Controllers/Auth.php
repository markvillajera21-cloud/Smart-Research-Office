<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\User;
use App\Models\AuditLogModel;

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

            AuditLogModel::log('LOGIN', 'users', $user['id'], ['status' => 'success']);

            return $this->redirectDashboard();
        }

        AuditLogModel::log('LOGIN_FAILED', 'users', null, ['email' => $email]);
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
        if (session()->get('role') === 'admin' || session()->get('role') === 'archive_viewer') {
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
            'role'     => $this->request->getPost('role')
        ];

        if ($userModel->save($data)) {
            $newUserId = $userModel->getInsertID();
            AuditLogModel::log('REGISTER', 'users', $newUserId, ['username' => $data['username']]);
            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        }

        return redirect()->back()->withInput()->with('errors', $userModel->errors());
    }

    public function logout()
    {
        AuditLogModel::log('LOGOUT', 'users', session()->get('user_id'));
        session()->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/forgot_password');
    }

    public function doForgotPassword()
    {
        $userModel = new User();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'No account found with that email address.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password must be at least 6 characters.');
        }

        $userModel->update($user['id'], ['password' => $password]);

        AuditLogModel::log('PASSWORD_RESET', 'users', $user['id'], ['email' => $email]);

        return redirect()->to('/login')->with('success', 'Password has been reset successfully! Please login.');
    }

    public function resetPassword($token)
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }

        $db = \Config\Database::connect();
        $reset = $db->table('password_resets')->where('token', $token)->first();

        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired password reset token.');
        }

        $expires = strtotime($reset->created_at . ' + 1 hour');
        if (time() > $expires) {
            $db->table('password_resets')->where('token', $token)->delete();
            return redirect()->to('/forgot-password')->with('error', 'Password reset token has expired.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function doResetPassword()
    {
        $userModel = new User();
        $db = \Config\Database::connect();

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $reset = $db->table('password_resets')->where('token', $token)->first();

        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired password reset token.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password must be at least 6 characters.');
        }

        $user = $userModel->where('email', $reset->email)->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'No account found.');
        }

        $userModel->update($user['id'], ['password' => $password]);

        $db->table('password_resets')->where('token', $token)->delete();

        AuditLogModel::log('PASSWORD_RESET', 'users', $user['id'], ['email' => $reset->email]);

        return redirect()->to('/login')->with('success', 'Password has been reset successfully! Please login.');
    }
}
