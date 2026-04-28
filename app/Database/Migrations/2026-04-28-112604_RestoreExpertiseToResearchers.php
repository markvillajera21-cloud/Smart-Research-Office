<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestoreExpertiseToResearchers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'expertise' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'category_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'expertise');
    }
}
