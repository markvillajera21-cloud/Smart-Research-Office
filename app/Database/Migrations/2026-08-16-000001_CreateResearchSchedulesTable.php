<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResearchSchedulesTable extends Migration
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
                'null'           => true,
            ],
            'student_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'instructor_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'schedule_type' => [
                'type'       => 'ENUM',
                'constraint' => ['title_hearing', 'pre_oral', 'final_defense'],
                'null'       => false,
            ],
            'schedule_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'schedule_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'schedule_time' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'venue' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'panelists' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'completed', 'cancelled', 'rescheduled'],
                'default'    => 'scheduled',
            ],
            'created_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
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
        $this->forge->addKey('student_id');
        $this->forge->addKey('instructor_id');
        $this->forge->addKey('schedule_date');
        $this->forge->createTable('research_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('research_schedules');
    }
}
