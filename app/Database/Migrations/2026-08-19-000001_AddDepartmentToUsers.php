<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'after' => 'role',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'department');
    }
}