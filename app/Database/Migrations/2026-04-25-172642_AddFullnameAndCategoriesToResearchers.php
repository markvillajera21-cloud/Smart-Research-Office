<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFullnameAndCategoriesToResearchers extends Migration
{
    public function up()
    {
        // 1. Create research_categories table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('research_categories');

        // 2. Add default categories
        $db = \Config\Database::connect();
        $db->table('research_categories')->insertBatch([
            ['name' => 'STEM', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Social Sciences', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Humanities', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Health & Medicine', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Technology', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Other', 'created_at' => date('Y-m-d H:i:s')],
        ]);

        // 3. Modify researchers table
        $fields = [
            'fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'after'      => 'user_id',
                'null'       => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'institutional_id',
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('researchers', $fields);

        // Migrate existing category data if possible (mapping strings to IDs)
        // Since it was ENUM, we can try to match names
        $researchers = $db->table('researchers')->get()->getResultArray();
        $categories = $db->table('research_categories')->get()->getResultArray();
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat['name']] = $cat['id'];
        }

        foreach ($researchers as $r) {
            $catId = $catMap[$r['category']] ?? $catMap['Other'];
            $db->table('researchers')
               ->where('id', $r['id'])
               ->update(['category_id' => $catId]);
        }

        // Drop the old category column
        $this->forge->dropColumn('researchers', 'category');

        // Add foreign key
        $this->forge->addForeignKey('category_id', 'research_categories', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('research_categories');
        $this->forge->dropColumn('researchers', 'fullname');
        $this->forge->dropColumn('researchers', 'category_id');
        
        // Restore category column
        $this->forge->addColumn('researchers', [
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['STEM', 'Social Sciences', 'Humanities', 'Health & Medicine', 'Technology', 'Other'],
                'default'    => 'Other',
                'after'      => 'institutional_id',
            ],
        ]);
    }
}
