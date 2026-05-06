<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResearchTeachersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Dr. Garcia'],
            ['name' => 'Prof. Martinez'],
            ['name' => 'Dr. Rodriguez'],
            ['name' => 'Prof. Lopez'],
        ];

        $this->db->table('research_teachers')->insertBatch($data);
    }
}