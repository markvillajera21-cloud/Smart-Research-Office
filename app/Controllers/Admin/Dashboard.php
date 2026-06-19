<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AuditLogModel;
use App\Models\User;
use App\Models\ProjectModel;
use App\Models\ResearcherModel;
use App\Models\ResearchCategoryModel;
use App\Models\StatusModel;
use App\Models\AdviserModel;
use App\Models\GrammarianModel;
use App\Models\RemarkModel;
use App\Models\ResearchTeacherModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new User();
        $researcherModel = new ResearcherModel();
        $statusModel = new StatusModel();
        $adviserModel = new AdviserModel();
        $grammarianModel = new GrammarianModel();
        $statisticianModel = new RemarkModel();
        $researchTeacherModel = new ResearchTeacherModel();

        $totalUsers = $userModel->countAll();
        
        $pendingStatus = $statusModel->where('name', 'Pending')->first();
        $pendingReviews = $pendingStatus ? $researcherModel->where('status_id', $pendingStatus['id'])->countAllResults() : 0;
        
        // Get counts for all roles
        $totalResearchTeachers = $researchTeacherModel->countAll();
        $totalGrammarians = $grammarianModel->countAll();
        $totalStatisticians = $statisticianModel->countAll();
        $totalAdvisers = $adviserModel->countAll();
        
        // Get total research count
        $totalResearch = $researcherModel->countAll();
        
        // Get approved and published (if statuses exist)
        $approvedStatus = $statusModel->where('name', 'Approved')->first();
        $totalApproved = $approvedStatus ? $researcherModel->where('status_id', $approvedStatus['id'])->countAllResults() : 0;
        
        $publishedStatus = $statusModel->where('name', 'Published')->first();
        $totalPublished = $publishedStatus ? $researcherModel->where('status_id', $publishedStatus['id'])->countAllResults() : 0;
        
        // Get department counts (High School and College)
        $totalHighSchool = $researcherModel->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                           ->where('research_categories.name', 'High School Department')
                                           ->countAllResults();
        $totalCollege = $researcherModel->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                       ->where('research_categories.name', 'College Department')
                                       ->countAllResults();

        $data = [
            'title' => 'Dashboard',
            'page_title' => 'Dashboard',
            'totalUsers' => $totalUsers,
            'pendingReviews' => $pendingReviews,
            'systemHealth' => 98,
            'totalResearchTeachers' => $totalResearchTeachers,
            'totalGrammarians' => $totalGrammarians,
            'totalStatisticians' => $totalStatisticians,
            'totalAdvisers' => $totalAdvisers,
            'totalResearch' => $totalResearch,
            'totalApproved' => $totalApproved,
            'totalPublished' => $totalPublished,
            'totalHighSchool' => $totalHighSchool,
            'totalCollege' => $totalCollege
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

    public function generatedReports()
    {
        $data = [
            'title' => 'Generated Reports',
            'page_title' => 'Generated Reports'
        ];
        
        return view('admin/generated-reports', $data);
    }
}
