<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArchiveViewerRole extends Migration
{
    public function up()
    {
        // Change role from enum to varchar to support dynamic roles
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'user',
                'null'       => false
            ]
        ]);
    }

    public function down()
    {
        // Revert back to enum if needed
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'user'],
                'default'    => 'user'
            ]
        ]);
    }
}
