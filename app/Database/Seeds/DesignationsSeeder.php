<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DesignationsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Research Assistant'],
            ['name' => 'Research Associate'],
            ['name' => 'Professor'],
            ['name' => 'Associate Professor'],
            ['name' => 'Assistant Professor'],
            ['name' => 'Lecturer'],
            ['name' => 'Lead Researcher'],
            ['name' => 'Principal Investigator'],
        ];

        $this->db->table('designations')->insertBatch($data);
    }
}
