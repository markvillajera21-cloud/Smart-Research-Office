<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefenseStatusesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Pending'],
            ['name' => 'Passed'],
            ['name' => 'Failed'],
        ];

        $this->db->table('defense_statuses')->insertBatch($data);
    }
}
