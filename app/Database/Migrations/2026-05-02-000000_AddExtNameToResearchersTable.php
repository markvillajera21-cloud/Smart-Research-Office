<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExtNameToResearchersTable extends Migration
{
    public function up()
    {
        $fields = [
            'ext_name' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('researchers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'ext_name');
    }
}
