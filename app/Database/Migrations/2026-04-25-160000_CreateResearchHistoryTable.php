<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchHistoryTable extends Migration
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
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // e.g., 'Project', 'Grant', 'Publication', 'Data'
            ],
            'event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'impact_score' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1, // To help with productivity heatmaps (1-100)
            ],
            'achieved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'JSON',
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
        $this->forge->createTable('research_history');
    }

    public function down()
    {
        $this->forge->dropTable('research_history');
    }
}
