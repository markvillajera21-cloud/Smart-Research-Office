<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToResearchersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'on_leave', 'completed'],
                'default'    => 'active',
                'after'      => 'school_year'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'status');
    }
}
