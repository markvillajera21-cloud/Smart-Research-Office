<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResearchCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Elementary', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Junior High School', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Senior High School', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'College', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Staff', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $db = \Config\Database::connect();
        foreach ($categories as $category) {
            $existing = $db->table('research_categories')->where('name', $category['name'])->get()->getRowArray();
            if (!$existing) {
                $db->table('research_categories')->insert($category);
            }
        }
    }
}
