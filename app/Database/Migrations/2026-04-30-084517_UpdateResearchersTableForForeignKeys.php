<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateResearchersTableForForeignKeys extends Migration
{
    public function up()
    {
        $this->forge->addColumn('researchers', [
            'designation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'fullname',
            ],
            'school_year_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'designation_id',
            ],
            'strand_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'school_year_id',
            ],
        ]);
        
        $this->forge->addForeignKey('designation_id', 'designations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('school_year_id', 'school_years', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('strand_id', 'strands', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('researchers', 'researchers_designation_id_foreign');
        $this->forge->dropForeignKey('researchers', 'researchers_school_year_id_foreign');
        $this->forge->dropForeignKey('researchers', 'researchers_strand_id_foreign');
        
        $this->forge->dropColumn('researchers', ['designation_id', 'school_year_id', 'strand_id']);
    }
}
