<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IncreaseMiddleInitialSize extends Migration
{
    public function up()
    {
        $fields = [
            'middle_initial' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('researchers', $fields);
    }

    public function down()
    {
        $fields = [
            'middle_initial' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('researchers', $fields);
    }
}
