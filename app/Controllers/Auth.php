<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\User;
use App\Models\AuditLogModel;
use Config\Departments;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/login');
    }

    public function departmentLogin(string $department)
    {
        $departmentConfig = Departments::get($department);
        if (!$departmentConfig) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if (session()->get('isLoggedIn')) {
            return redirect()->to(role_home());
        }
        return view('auth/department_login', ['department' => $department, 'departmentConfig' => $departmentConfig]);
    }

    public function doLogin()
    {
        $userModel = new User();
        $identifier = trim((string) $this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $selectedRole = trim((string) $this->request->getPost('role'));

        if (! array_key_exists($selectedRole, \Config\Roles::LOGIN_CHOICES)) {
            return redirect()->back()->withInput()->with('error', 'Please select a valid role.');
        }

        $user = $userModel
            ->groupStart()
                ->where('email', $identifier)
                ->orWhere('username', $identifier)
            ->groupEnd()
            ->first();

        if ($user && password_verify($password, $user['password'])) {
            if (! \Config\Roles::matchesLoginRole($user['role'] ?? null, $selectedRole)) {
                AuditLogModel::log('LOGIN_FAILED', 'users', $user['id'], [
                    'identifier' => $identifier,
                    'reason' => 'role_mismatch',
                    'selected_role' => $selectedRole,
                    'account_role' => $user['role'] ?? null,
                ]);
                return redirect()->back()->withInput()->with('error', 'The selected role does not match this account.');
            }

            $session = session();
            $session->set([
                'user_id'    => $user['id'],
                'userId'     => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'department' => $user['department'] ?? null,
                'isLoggedIn' => true
            ]);

            AuditLogModel::log('LOGIN', 'users', $user['id'], [
                'status' => 'success',
                'selected_role' => $selectedRole,
            ]);

            return $this->redirectDashboard();
        }

        AuditLogModel::log('LOGIN_FAILED', 'users', null, ['identifier' => $identifier, 'selected_role' => $selectedRole]);
        return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
    }

    public function doDepartmentLogin(string $department)
    {
        $departmentConfig = Departments::get($department);
        if (!$departmentConfig) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $userModel = new User();
        $identifier = trim((string) $this->request->getPost('email'));
        $password = $this->request->getPost('password');

        $selectedRole = trim((string) $this->request->getPost('role'));
        if (! array_key_exists($selectedRole, \Config\Roles::LOGIN_CHOICES)) {
            return redirect()->back()->withInput()->with('error', 'Please select a valid role.');
        }

        $user = $userModel
            ->groupStart()
                ->where('email', $identifier)
                ->orWhere('username', $identifier)
            ->groupEnd()
            ->first();

        if (! $user) {
            AuditLogModel::log('LOGIN_FAILED', 'users', null, ['identifier' => $identifier, 'department' => $department, 'reason' => 'user_not_found']);
            return redirect()->back()->withInput()->with('error', 'Invalid login credentials.');
        }

        if (! password_verify($password, $user['password'])) {
            AuditLogModel::log('LOGIN_FAILED', 'users', $user['id'], ['identifier' => $identifier, 'department' => $department, 'reason' => 'wrong_password']);
            return redirect()->back()->withInput()->with('error', 'Invalid login credentials.');
        }

        $normalizedRole = \Config\Roles::normalize($user['role'] ?? '');
        $userDepartment = $user['department'] ?? null;

        $isAdmin = $normalizedRole === 'admin';
        $isResearchOffice = is_research_office($user['role'] ?? null);

        $hasDepartmentAccess = $isAdmin
            || $userDepartment === $department
            || ($isResearchOffice && $userDepartment === $department);

        if (! $hasDepartmentAccess) {
            AuditLogModel::log('LOGIN_FAILED', 'users', $user['id'], [
                'identifier' => $identifier,
                'department' => $department,
                'reason' => 'department_mismatch',
                'user_department' => $userDepartment,
            ]);
            $deptName = esc($departmentConfig['name']);
            $userDeptLabel = $userDepartment
                ? esc(\Config\Departments::get($userDepartment)['name'] ?? $userDepartment)
                : 'No department assigned';
            return redirect()->back()->withInput()->with(
                'error',
                "This account is not assigned to {$deptName}. Current assignment: {$userDeptLabel}."
            );
        }

        if (! \Config\Roles::matchesLoginRole($user['role'] ?? null, $selectedRole)) {
            AuditLogModel::log('LOGIN_FAILED', 'users', $user['id'], [
                'identifier' => $identifier,
                'department' => $department,
                'reason' => 'role_mismatch',
                'selected_role' => $selectedRole,
                'account_role' => $user['role'] ?? null,
            ]);
            return redirect()->back()->withInput()->with('error', 'The selected role does not match this account.');
        }

        session()->set([
            'user_id'    => $user['id'],
            'userId'     => $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'department' => $department,
            'isLoggedIn' => true,
        ]);
        AuditLogModel::log('LOGIN', 'users', $user['id'], ['status' => 'success', 'department' => $department]);

        if ($isResearchOffice) {
            return redirect()->to('/department/dashboard');
        }

        return redirect()->to(role_home());
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
        return redirect()->to(role_home());
    }

    public function doRegister()
    {
        $userModel = new User();
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => 'student'
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

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
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
        $reset = $db->table('password_resets')->where('token', $token)->get()->getFirstRow();

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

        $reset = $db->table('password_resets')->where('token', $token)->get()->getRow();

        if (!$reset) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired password reset token.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
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
