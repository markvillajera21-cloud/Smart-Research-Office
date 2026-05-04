<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HistoryModel;
use App\Models\ResearcherModel;
use App\Models\User;

class Dashboard extends BaseController
{
    public function index()
    {
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
            'page_title' => 'Settings'
        ];
        
        return view('admin/settings', $data);
    }
}
