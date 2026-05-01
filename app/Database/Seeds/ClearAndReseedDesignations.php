<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClearAndReseedDesignations extends Seeder
{
    public function run()
    {
        $this->db->table('designations')->truncate();
        
        $data = [
            ['name' => 'Teaching Personnel'],
            ['name' => 'Non-Teaching Personnel'],
            ['name' => 'N/A'],
        ];

        $this->db->table('designations')->insertBatch($data);
    }
}
