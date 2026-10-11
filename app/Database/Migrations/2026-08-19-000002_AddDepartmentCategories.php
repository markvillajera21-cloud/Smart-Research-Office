<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentCategories extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        foreach (['CMAT', 'CEAS', 'Senior High'] as $name) {
            if (!$db->table('research_categories')->where('name', $name)->get()->getFirstRow()) {
                $db->table('research_categories')->insert([
                    'name' => $name,
                    'description' => $name . ' department',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function down()
    {
        \Config\Database::connect()->table('research_categories')->whereIn('name', ['CMAT', 'CEAS', 'Senior High'])->delete();
    }
}