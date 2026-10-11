<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchEvaluationsTable extends Migration
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
            'schedule_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'researcher_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'student_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'evaluated_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'result' => [
                'type'       => 'ENUM',
                'constraint' => ['passed', 'failed', 'for_compliance'],
                'null'       => false,
            ],
            'overall_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'compliance_deadline' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'compliance_submitted' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'evaluated_at' => [
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
        $this->forge->addKey('schedule_id');
        $this->forge->addKey('researcher_id');
        $this->forge->addKey('student_id');
        $this->forge->addKey('result');
        $this->forge->createTable('research_evaluations');
    }

    public function down()
    {
        $this->forge->dropTable('research_evaluations');
    }
}
