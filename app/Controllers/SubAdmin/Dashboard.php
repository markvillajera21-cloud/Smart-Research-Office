<?php

namespace App\Controllers\SubAdmin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\User;
use App\Models\ResearcherModel;
use App\Models\ResearchCategoryModel;
use App\Models\StatusModel;
use App\Models\AdviserModel;
use App\Models\GrammarianModel;
use App\Models\RemarkModel;
use App\Models\ResearchTeacherModel;
use App\Models\ResearchScheduleModel;
use App\Models\ResearchTitleModel;
use App\Models\ExpertModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel            = new User();
        $researcherModel      = new ResearcherModel();
        $statusModel          = new StatusModel();
        $adviserModel         = new AdviserModel();
        $grammarianModel      = new GrammarianModel();
        $statisticianModel    = new RemarkModel();
        $researchTeacherModel = new ResearchTeacherModel();
        $scheduleModel        = new ResearchScheduleModel();
        $titleModel           = new ResearchTitleModel();
        $expertModel          = new ExpertModel();

        // Research Counts
        $totalResearch = $researcherModel->countAll();

        // Pending Title Reviews
        $pendingTitles = $titleModel->where('status', 'pending')->countAllResults();

        // Approved & Completed Researches
        $approvedTitles  = $titleModel->where('status', 'approved')->countAllResults();
        $completedTitles = $titleModel->where('status', 'completed')->countAllResults();

        // Active Schedules & Defenses
        $totalSchedules = $scheduleModel->countAll();

        // Personnel Pools
        $totalResearchTeachers = $researchTeacherModel->countAll();
        $totalGrammarians      = $grammarianModel->countAll();
        $totalStatisticians    = $statisticianModel->countAll();
        $totalAdvisers         = $adviserModel->countAll();
        $totalExperts          = $expertModel->countAll();

        // Department breakdown
        $totalHighSchool = $researcherModel->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                           ->where('research_categories.name', 'High School Department')
                                           ->countAllResults();
        $totalCollege    = $researcherModel->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                                           ->where('research_categories.name', 'College Department')
                                           ->countAllResults();

        // Recent Activity Logs (view-only for Sub-Admin)
        $auditModel = new AuditLogModel();
        $recentLogs = $auditModel->getLogsWithUsers();
        if (is_array($recentLogs)) {
            $recentLogs = array_slice($recentLogs, 0, 8);
        }

        $data = [
            'title'                 => 'Sub-Admin Dashboard',
            'page_title'            => 'Sub-Admin Overview',
            'totalResearch'         => $totalResearch,
            'pendingTitles'         => $pendingTitles,
            'approvedTitles'        => $approvedTitles,
            'completedTitles'       => $completedTitles,
            'totalSchedules'        => $totalSchedules,
            'totalExperts'          => $totalExperts,
            'totalResearchTeachers' => $totalResearchTeachers,
            'totalGrammarians'      => $totalGrammarians,
            'totalStatisticians'    => $totalStatisticians,
            'totalAdvisers'         => $totalAdvisers,
            'totalHighSchool'       => $totalHighSchool,
            'totalCollege'          => $totalCollege,
            'recentLogs'            => $recentLogs,
        ];

        return view('sub_admin/dashboard', $data);
    }

    public function profile()
    {
        $userModel = new User();
        $userId    = session()->get('user_id');
        $user      = $userModel->find($userId);

        $data = [
            'title'      => 'Sub-Admin Profile',
            'page_title' => 'My Profile',
            'user'       => $user,
        ];

        return view('admin/profile', $data);
    }

    public function settings()
    {
        $data = [
            'title'      => 'Settings',
            'page_title' => 'Settings',
            'formAction' => base_url('sub-admin/settings'),
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
            AuditLogModel::log('PASSWORD_CHANGE', 'users', $userId, ['via' => 'subadmin_settings']);
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}
