<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResearchTeacherIdAndRemarksToResearchers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'research_teacher_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'remark_id'
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'approved_date'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', ['research_teacher_id', 'remarks']);
    }
}
