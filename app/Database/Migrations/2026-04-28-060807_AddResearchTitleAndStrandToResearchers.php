<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResearchTitleAndStrandToResearchers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'approved_research_title' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'strand_degree_program' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'approved_research_title');
        $this->forge->dropColumn('researchers', 'strand_degree_program');
    }
}
