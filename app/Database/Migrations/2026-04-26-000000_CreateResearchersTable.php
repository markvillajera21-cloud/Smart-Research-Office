<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'unique'         => true,
            ],
            'institutional_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['STEM', 'Social Sciences', 'Humanities', 'Health & Medicine', 'Technology', 'Other'],
                'default'    => 'Other',
            ],
            'bio' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'joined_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('researchers');
    }

    public function down()
    {
        $this->forge->dropTable('researchers');
    }
}
