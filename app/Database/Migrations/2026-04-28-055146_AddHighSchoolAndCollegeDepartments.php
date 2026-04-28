<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHighSchoolAndCollegeDepartments extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->table('research_categories')->insertBatch([
            ['name' => 'High School Department', 'description' => 'High school research department', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'College Department', 'description' => 'College research department', 'created_at' => date('Y-m-d H:i:s')],
        ]);
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->table('research_categories')->whereIn('name', ['High School Department', 'College Department'])->delete();
    }
}
