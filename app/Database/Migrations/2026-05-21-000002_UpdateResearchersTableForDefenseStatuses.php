<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateResearchersTableForDefenseStatuses extends Migration
{
    public function up()
    {
        $fields = [
            'pre_oral_defense_status_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'final_defense_status_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('researchers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', [
            'pre_oral_defense_status_id',
            'final_defense_status_id',
        ]);
    }
}
