<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeUserIdOptional extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('researchers', 'researchers_user_id_foreign');
        $this->forge->dropKey('researchers', 'user_id');
        
        $this->forge->modifyColumn('researchers', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('researchers', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'unique'     => true,
            ],
        ]);
        
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }
}
