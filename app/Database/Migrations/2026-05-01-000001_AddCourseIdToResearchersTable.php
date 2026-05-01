<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourseIdToResearchersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'strand_id',
            ],
        ]);

        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('researchers', 'researchers_course_id_foreign');
        $this->forge->dropColumn('researchers', 'course_id');
    }
}
