<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExaminerRegistrationPool extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'examiner_role' => [
                'type'       => 'ENUM',
                'constraint' => ['adviser', 'critic', 'panel_member'],
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('examiner_role');
        $this->forge->addKey('is_active');
        $this->forge->createTable('examiners', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'schedule_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'examiner_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'assignment_role' => [
                'type'       => 'ENUM',
                'constraint' => ['adviser', 'critic', 'panel_member'],
                'null'       => false,
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
        $this->forge->addKey('examiner_id');
        $this->forge->createTable('research_schedule_examiners', true);
    }

    public function down()
    {
        $this->forge->dropTable('research_schedule_examiners', true);
        $this->forge->dropTable('examiners', true);
    }
}
