<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HistoryModel;

class History extends BaseController
{
    public function index()
    {
        $historyModel = new HistoryModel();
        
        // Get all history records joined with user data
        $milestones = $historyModel->select('research_history.*, users.username, users.email')
                                  ->join('users', 'users.id = research_history.user_id')
                                  ->orderBy('research_history.achieved_at', 'DESC')
                                  ->findAll();

        $data = [
            'title' => 'Researcher History Management',
            'page_title' => 'Global Research History',
            'milestones' => $milestones
        ];

        return view('admin/history', $data);
    }
}
