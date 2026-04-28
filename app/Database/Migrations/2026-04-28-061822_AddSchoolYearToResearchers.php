<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchoolYearToResearchers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'school_year' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'school_year');
    }
}
