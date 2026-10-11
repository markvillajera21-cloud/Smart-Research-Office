<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HistoryModel;
use App\Models\ResearcherModel;
use App\Models\AuditLogModel;
use App\Models\User;

class Dashboard extends BaseController
{
    public function index()
    {
        if (is_student() || is_teacher() || is_research_office()) {
            return redirect()->to(role_home());
        }

        $historyModel = new HistoryModel();
        $researcherModel = new ResearcherModel();
        $userId = session()->get('user_id');
        
        $researcher = $researcherModel->findByUserId($userId);

        $session = session();
        $session->remove('error');

        $data = [
            'title' => 'Researcher Dashboard',
            'page_title' => 'My History',
            'milestones' => $historyModel->getUserPortfolio($userId),
            'productivity' => $historyModel->getProductivityData($userId),
            'researcher' => $researcher
        ];
        return view('user/dashboard', $data);
    }

    public function profile()
    {
        $userModel = new User();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        
        $session = session();
        $session->remove('error');

        $data = [
            'title' => 'Profile',
            'page_title' => 'My Profile',
            'user' => $user
        ];
        
        return view('admin/profile', $data);
    }

    public function settings()
    {
        $session = session();
        $session->remove('error');

        $data = [
            'title' => 'Settings',
            'page_title' => 'Settings',
            'formAction' => base_url('user/settings'),
        ];
        
        return view('admin/settings', $data);
    }

    public function doSettings()
    {
        $userModel = new User();
        $userId = (int) session()->get('user_id');
        $user = $userModel->find($userId);
        if (! $user) {
            return redirect()->to('/logout');
        }

        $newPassword = (string) $this->request->getPost('new_password');
        $confirmPassword = (string) $this->request->getPost('confirm_password');
        $currentPassword = (string) $this->request->getPost('current_password');

        if ($newPassword !== '' || $confirmPassword !== '') {
            if ($currentPassword === '' || ! password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Current password is incorrect.');
            }
            if (strlen($newPassword) < 8) {
                return redirect()->back()->withInput()->with('error', 'New password must be at least 8 characters.');
            }
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'New passwords do not match.');
            }
            $userModel->update($userId, ['password' => $newPassword]);
            AuditLogModel::log('PASSWORD_CHANGE', 'users', $userId, ['via' => 'user_settings']);
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}
