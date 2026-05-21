<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefenseFieldsToResearchersTable extends Migration
{
    public function up()
    {
        $fields = [
            'pre_oral_defense_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pre_oral_defense_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'final_defense_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'final_defense_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('researchers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', [
            'pre_oral_defense_date',
            'pre_oral_defense_status',
            'final_defense_date',
            'final_defense_status',
        ]);
    }
}
