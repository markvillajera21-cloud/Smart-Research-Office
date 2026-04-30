<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSurnameFirstNameMiddleInitialToResearchersTable extends Migration
{
    public function up()
    {
        $fields = [
            'surname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'middle_initial' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('researchers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', ['surname', 'first_name', 'middle_initial']);
    }
}
