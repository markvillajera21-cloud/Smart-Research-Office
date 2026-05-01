<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AbstractsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Complete'],
            ['name' => 'In Progress'],
            ['name' => 'Pending'],
            ['name' => 'Not Started'],
        ];

        $this->db->table('abstracts')->insertBatch($data);
    }
}
