<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HistoryModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $historyModel = new HistoryModel();
        $userId = session()->get('user_id');

        $data = [
            'title' => 'Researcher Dashboard',
            'page_title' => 'My History',
            'milestones' => $historyModel->getUserPortfolio($userId),
            'productivity' => $historyModel->getProductivityData($userId)
        ];
        return view('user/dashboard', $data);
    }
}
