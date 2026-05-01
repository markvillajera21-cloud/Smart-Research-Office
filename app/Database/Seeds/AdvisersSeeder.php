<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdvisersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Dr. Smith'],
            ['name' => 'Prof. Johnson'],
            ['name' => 'Dr. Williams'],
            ['name' => 'Prof. Brown'],
        ];

        $this->db->table('advisers')->insertBatch($data);
    }
}
