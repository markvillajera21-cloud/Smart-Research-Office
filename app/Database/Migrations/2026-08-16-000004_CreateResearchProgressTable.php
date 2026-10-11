<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchProgressTable extends Migration
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
            'researcher_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'stage' => [
                'type'       => 'ENUM',
                'constraint' => ['title_proposal', 'title_approved', 'pre_oral_scheduled', 'pre_oral_passed', 'final_defense_scheduled', 'final_defense_passed', 'completed'],
                'default'    => 'title_proposal',
                'null'       => false,
            ],
            'stage_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'updated_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'progress_percentage' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'last_updated' => [
                'type' => 'DATETIME',
                'null' => false,
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
        $this->forge->addKey('researcher_id');
        $this->forge->addKey('stage');
        $this->forge->createTable('research_progress');
    }

    public function down()
    {
        $this->forge->dropTable('research_progress');
    }
}
