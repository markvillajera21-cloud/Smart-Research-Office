<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StatusesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Active'],
            ['name' => 'Inactive'],
            ['name' => 'On Leave'],
            ['name' => 'Completed'],
        ];

        $this->db->table('statuses')->insertBatch($data);
    }
}
