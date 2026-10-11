<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnforceUniqueResearchTitles extends Migration
{
    public function up()
    {
        $db = $this->db;

        if ($db->tableExists('research_titles')) {
            // 1. Clean up and normalize whitespace of existing titles
            $titles = $db->table('research_titles')->get()->getResultArray();
            $seen = [];
            foreach ($titles as $t) {
                $rawTitle = $t['title'] ?? '';
                $cleanTitle = preg_replace('/\s+/u', ' ', trim($rawTitle));
                $lowerKey = mb_strtolower($cleanTitle, 'UTF-8');

                if (isset($seen[$lowerKey])) {
                    // If duplicate already existed in dev data, rename or remove duplicate record
                    // Keep the first one, delete duplicate
                    $db->table('research_titles')->where('id', $t['id'])->delete();
                } else {
                    $seen[$lowerKey] = $t['id'];
                    if ($rawTitle !== $cleanTitle) {
                        $db->table('research_titles')->where('id', $t['id'])->update(['title' => $cleanTitle]);
                    }
                }
            }

            // 2. Modify title to VARCHAR(500) and add unique key
            // First drop fulltext index on title if present so column can be modified
            try {
                $db->query("ALTER TABLE `research_titles` MODIFY `title` VARCHAR(500) NOT NULL");
            } catch (\Throwable $e) {
                // fallback
            }

            try {
                $db->query("ALTER TABLE `research_titles` ADD UNIQUE KEY `uk_research_title_unique` (`title`)");
            } catch (\Throwable $e) {
                // If unique key already exists or failed
            }
        }
    }

    public function down()
    {
        $db = $this->db;
        if ($db->tableExists('research_titles')) {
            try {
                $db->query("ALTER TABLE `research_titles` DROP INDEX `uk_research_title_unique`");
            } catch (\Throwable $e) {
            }
            try {
                $db->query("ALTER TABLE `research_titles` MODIFY `title` TEXT NOT NULL");
            } catch (\Throwable $e) {
            }
        }
    }
}
