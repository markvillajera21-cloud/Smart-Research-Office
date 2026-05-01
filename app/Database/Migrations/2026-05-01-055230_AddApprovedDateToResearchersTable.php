<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovedDateToResearchersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'approved_date' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'approved_research_title',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'approved_date');
    }
}
