<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCalendarActivitiesTable extends Migration
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
            'activity_type' => [
                'type'       => 'ENUM',
                'constraint' => ['title_hearing', 'pre_oral', 'final_defense'],
                'null'       => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['approved', 'pending', 'cancelled'],
                'default'    => 'approved',
                'null'       => false,
            ],
            'academic_year' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey('activity_type');
        $this->forge->addKey('status');
        $this->forge->addKey('start_date');
        $this->forge->addKey('end_date');
        $this->forge->createTable('calendar_activities', true);
    }

    public function down()
    {
        $this->forge->dropTable('calendar_activities', true);
    }
}
