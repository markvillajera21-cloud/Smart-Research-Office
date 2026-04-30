<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveInstitutionalId extends Migration
{
    public function up()
    {
        $this->forge->dropKey('researchers', 'institutional_id');
        $this->forge->dropColumn('researchers', 'institutional_id');
    }

    public function down()
    {
        $this->forge->addColumn('researchers', [
            'institutional_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'user_id',
            ],
        ]);
        $this->forge->addUniqueKey('institutional_id');
    }
}
