<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthorToResearchersTable extends Migration
{
    public function up()
    {
        $fields = [
            'author' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('researchers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'author');
    }
}
