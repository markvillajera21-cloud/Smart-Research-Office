<?php

namespace App\Controllers\Department;

use App\Controllers\BaseController;
use Config\Departments;

class Dashboard extends BaseController
{
    public function index()
    {
        $departmentSlug = session()->get('department');
        $department = Departments::get($departmentSlug);
        if (!$department) {
            return redirect()->to('/login')->with('error', 'Your account has no valid department assignment.');
        }

        $db = \Config\Database::connect();
        $categoryNames = $department['category_names'];
        $researcherFilter = function ($builder) use ($categoryNames) {
            return $builder->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                ->whereIn('research_categories.name', $categoryNames);
        };

        $researchers = $researcherFilter($db->table('researchers'))
            ->select('researchers.id, researchers.fullname, researchers.approved_research_title, researchers.status, research_categories.name as category_name')
            ->orderBy('researchers.updated_at', 'DESC')
            ->get(8)
            ->getResultArray();

        $totalResearch = $researcherFilter($db->table('researchers'))->countAllResults();
        $approvedResearch = $researcherFilter($db->table('researchers'))
            ->where('researchers.status', 'active')
            ->countAllResults();

        $scheduleQuery = $db->table('research_schedules rs')
            ->join('researchers r', 'r.id = rs.researcher_id', 'left')
            ->join('research_categories rc', 'rc.id = r.category_id', 'left')
            ->whereIn('rc.name', $categoryNames);
        $upcomingSchedules = (clone $scheduleQuery)
            ->where('rs.schedule_date >=', date('Y-m-d'))
            ->where('rs.status', 'scheduled')
            ->select('rs.schedule_title, rs.schedule_type, rs.schedule_date, rs.schedule_time, rs.venue')
            ->orderBy('rs.schedule_date', 'ASC')
            ->orderBy('rs.schedule_time', 'ASC')
            ->get(8)
            ->getResultArray();

        $evaluationQuery = $db->table('research_evaluations re')
            ->join('researchers r', 'r.id = re.researcher_id', 'left')
            ->join('research_categories rc', 'rc.id = r.category_id', 'left')
            ->whereIn('rc.name', $categoryNames);
        $evaluationStats = [
            'passed' => (clone $evaluationQuery)->where('re.result', 'passed')->countAllResults(),
            'failed' => (clone $evaluationQuery)->where('re.result', 'failed')->countAllResults(),
            'for_compliance' => (clone $evaluationQuery)->where('re.result', 'for_compliance')->countAllResults(),
        ];

        return view('department/dashboard', [
            'title' => $department['name'] . ' Dashboard',
            'page_title' => $department['name'] . ' Research Dashboard',
            'department' => $department,
            'departmentSlug' => $departmentSlug,
            'totalResearch' => $totalResearch,
            'approvedResearch' => $approvedResearch,
            'upcomingSchedules' => $upcomingSchedules,
            'evaluationStats' => $evaluationStats,
            'researchers' => $researchers,
        ]);
    }
}
