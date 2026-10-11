<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchReportsTable extends Migration
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
            'report_type' => [
                'type'       => 'ENUM',
                'constraint' => ['schedule', 'status', 'completion'],
                'null'       => false,
            ],
            'report_params' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'generated_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'generated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
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
        $this->forge->addKey('report_type');
        $this->forge->createTable('research_reports');
    }

    public function down()
    {
        $this->forge->dropTable('research_reports');
    }
}
