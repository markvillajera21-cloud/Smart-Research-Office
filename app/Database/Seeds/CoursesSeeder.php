<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Bachelor of Science in Information Technology'],
            ['name' => 'Bachelor of Science in Computer Science'],
            ['name' => 'Bachelor of Science in Business Administration'],
            ['name' => 'Bachelor of Secondary Education'],
            ['name' => 'Bachelor of Elementary Education'],
            ['name' => 'Bachelor of Science in Nursing'],
            ['name' => 'Bachelor of Science in Psychology'],
            ['name' => 'N/A'],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
