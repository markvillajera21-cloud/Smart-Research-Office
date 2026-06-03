<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IncreaseSurnameFirstNameSize extends Migration
{
    public function up()
    {
        $fields = [
            'surname' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'first_name' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ext_name' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'author' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('researchers', $fields);
    }

    public function down()
    {
        $fields = [
            'surname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'ext_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'author' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('researchers', $fields);
    }
}
