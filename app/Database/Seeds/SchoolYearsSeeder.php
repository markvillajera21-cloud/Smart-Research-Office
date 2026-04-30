<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SchoolYearsSeeder extends Seeder
{
    public function run()
    {
        $currentYear = date('Y');
        $data = [];
        
        for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) {
            $data[] = ['name' => $year . '-' . ($year + 1)];
        }

        $this->db->table('school_years')->insertBatch($data);
    }
}
