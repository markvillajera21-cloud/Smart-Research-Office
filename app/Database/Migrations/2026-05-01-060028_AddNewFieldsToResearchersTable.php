<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewFieldsToResearchersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'adviser_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'approved_date',
            ],
            'grammarian_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'adviser_id',
            ],
            'remark_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'grammarian_id',
            ],
            'abstract_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'remark_id',
            ],
        ]);

        $this->forge->addForeignKey('adviser_id', 'advisers', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('grammarian_id', 'grammarians', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('remark_id', 'remarks', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('abstract_id', 'abstracts', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('researchers', 'researchers_adviser_id_foreign');
        $this->forge->dropForeignKey('researchers', 'researchers_grammarian_id_foreign');
        $this->forge->dropForeignKey('researchers', 'researchers_remark_id_foreign');
        $this->forge->dropForeignKey('researchers', 'researchers_abstract_id_foreign');
        
        $this->forge->dropColumn('researchers', [
            'adviser_id',
            'grammarian_id',
            'remark_id',
            'abstract_id'
        ]);
    }
}
