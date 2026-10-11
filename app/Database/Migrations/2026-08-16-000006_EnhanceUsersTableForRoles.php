<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceUsersTableForRoles extends Migration
{
    public function up()
    {
        $fields = [
            'assigned_instructor_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'after'          => 'role'
            ],
            'login_attempts' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'default'        => 0,
                'after'          => 'assigned_instructor_id'
            ],
            'lockout_until' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'login_attempts'
            ],
        ];
        $this->forge->addColumn('users', $fields);
        $this->forge->addKey('assigned_instructor_id');
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['assigned_instructor_id', 'login_attempts', 'lockout_until']);
    }
}
