<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\HistoryModel;
use App\Models\User;

class ResearchHistorySeeder extends Seeder
{
    public function run()
    {
        $userModel = new User();
        $admin = $userModel->where('username', 'admin')->first();

        if ($admin) {
            // S.Y. 2025-2026 Records
            HistoryModel::record(
                $admin['id'],
                'PROJECT',
                'AI-Driven Research Analytics Phase 1',
                'Initiated the first phase of AI integration for research data processing in S.Y. 2025-2026.',
                75,
                ['school_year' => '2025-2026']
            );

            HistoryModel::record(
                $admin['id'],
                'GRANT',
                'Institutional Research Grant (S.Y. 2025-2026)',
                'Awarded for the development of sustainable research frameworks.',
                90,
                ['school_year' => '2025-2026']
            );

            HistoryModel::record(
                $admin['id'],
                'DATA',
                'Annual Research Productivity Report',
                'Compiled and published the research output analysis for the first semester of 2025-2026.',
                45,
                ['school_year' => '2025-2026']
            );

            // Previous records
            HistoryModel::record(
                $admin['id'],
                'GRANT',
                'National Science Foundation Grant Approved',
                'Successfully secured a $50,000 grant for the Smart Research Office project.',
                85
            );
        }
    }
}
