<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RemarksSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Excellent'],
            ['name' => 'Good'],
            ['name' => 'Fair'],
            ['name' => 'Needs Improvement'],
        ];

        $this->db->table('remarks')->insertBatch($data);
    }
}
