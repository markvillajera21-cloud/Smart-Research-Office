<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GrammariansSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Prof. Davis'],
            ['name' => 'Dr. Miller'],
            ['name' => 'Prof. Wilson'],
            ['name' => 'Dr. Moore'],
        ];

        $this->db->table('grammarians')->insertBatch($data);
    }
}
