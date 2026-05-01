<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusIdToResearchersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'status_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'course_id',
            ],
        ]);

        $this->forge->addForeignKey('status_id', 'statuses', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('researchers', 'researchers_status_id_foreign');
        $this->forge->dropColumn('researchers', 'status_id');
    }
}
