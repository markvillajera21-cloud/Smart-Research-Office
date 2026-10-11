<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExpertRegistrationPool extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'expert_role' => [
                'type'       => 'ENUM',
                'constraint' => ['adviser', 'grammarian', 'statistician'],
                'null'       => false,
            ],
            'legacy_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('expert_role');
        $this->forge->addKey('is_active');
        $this->forge->createTable('experts', true);

        // Seed existing legacy records into experts table if available
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        if ($db->tableExists('advisers')) {
            $advisers = $db->table('advisers')->get()->getResultArray();
            foreach ($advisers as $adv) {
                if (!empty($adv['name'])) {
                    $db->table('experts')->insert([
                        'name'        => $adv['name'],
                        'expert_role' => 'adviser',
                        'legacy_id'   => $adv['id'] ?? null,
                        'is_active'   => 1,
                        'created_at'  => $adv['created_at'] ?? $now,
                        'updated_at'  => $adv['updated_at'] ?? $now,
                    ]);
                }
            }
        }

        if ($db->tableExists('grammarians')) {
            $grammarians = $db->table('grammarians')->get()->getResultArray();
            foreach ($grammarians as $gram) {
                if (!empty($gram['name'])) {
                    $db->table('experts')->insert([
                        'name'        => $gram['name'],
                        'expert_role' => 'grammarian',
                        'legacy_id'   => $gram['id'] ?? null,
                        'is_active'   => 1,
                        'created_at'  => $gram['created_at'] ?? $now,
                        'updated_at'  => $gram['updated_at'] ?? $now,
                    ]);
                }
            }
        }

        if ($db->tableExists('remarks')) {
            $statisticians = $db->table('remarks')->get()->getResultArray();
            foreach ($statisticians as $stat) {
                if (!empty($stat['name'])) {
                    $db->table('experts')->insert([
                        'name'        => $stat['name'],
                        'expert_role' => 'statistician',
                        'legacy_id'   => $stat['id'] ?? null,
                        'is_active'   => 1,
                        'created_at'  => $stat['created_at'] ?? $now,
                        'updated_at'  => $stat['updated_at'] ?? $now,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $this->forge->dropTable('experts', true);
    }
}
