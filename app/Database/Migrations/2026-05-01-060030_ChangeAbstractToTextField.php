<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeAbstractToTextField extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        try {
            $this->forge->dropForeignKey('researchers', 'researchers_abstract_id_foreign');
        } catch (\Exception $e) {
        }
        
        try {
            $this->forge->dropColumn('researchers', 'abstract_id');
        } catch (\Exception $e) {
        }
        
        $this->forge->addColumn('researchers', [
            'abstract' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'remark_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('researchers', 'abstract');
        
        $this->forge->addColumn('researchers', [
            'abstract_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'remark_id',
            ],
        ]);
        
        $this->forge->addForeignKey('abstract_id', 'abstracts', 'id', 'CASCADE', 'SET NULL');
    }
}
