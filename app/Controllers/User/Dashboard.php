<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HistoryModel;
use App\Models\ResearcherModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $historyModel = new HistoryModel();
        $researcherModel = new ResearcherModel();
        $userId = session()->get('user_id');
        
        $researcher = $researcherModel->findByUserId($userId);

        $data = [
            'title' => 'Researcher Dashboard',
            'page_title' => 'My History',
            'milestones' => $historyModel->getUserPortfolio($userId),
            'productivity' => $historyModel->getProductivityData($userId),
            'researcher' => $researcher
        ];
        return view('user/dashboard', $data);
    }
}
