<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StrandsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'STEM'],
            ['name' => 'HUMSS'],
            ['name' => 'ABM'],
            ['name' => 'GAS'],
            ['name' => 'TVL'],
        ];

        $this->db->table('strands')->insertBatch($data);
    }
}
