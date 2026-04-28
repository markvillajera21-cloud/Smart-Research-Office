<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropExpertiseFromResearchers extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('researchers', 'expertise');
    }

    public function down()
    {
        $this->forge->addColumn('researchers', [
            'expertise' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'category_id',
            ],
        ]);
    }
}
