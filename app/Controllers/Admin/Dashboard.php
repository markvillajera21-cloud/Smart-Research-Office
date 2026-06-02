<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AuditLogModel;
use App\Models\User;
use App\Models\ProjectModel;
use App\Models\ResearcherModel;
use App\Models\StatusModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new User();
        $researcherModel = new ResearcherModel();
        $statusModel = new StatusModel();

        $totalUsers = $userModel->countAll();
        
        $pendingStatus = $statusModel->where('name', 'Pending')->first();
        $pendingReviews = $pendingStatus ? $researcherModel->where('status_id', $pendingStatus['id'])->countAllResults() : 0;

        $data = [
            'totalUsers' => $totalUsers,
            'pendingReviews' => $pendingReviews,
            'systemHealth' => 98
        ];

        return view('admin/dashboard', $data);
    }

    public function auditLogs()
    {
        $auditModel = new AuditLogModel();
        $data = [
            'title' => 'System Audit Logs',
            'page_title' => 'Audit Trail',
            'logs' => $auditModel->getLogsWithUsers()
        ];
        return view('admin/audit_logs', $data);
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
            'page_title' => 'Settings'
        ];
        
        return view('admin/settings', $data);
    }
}
