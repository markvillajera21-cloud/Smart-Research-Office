<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeUserRoles extends Migration
{
    public function up()
    {
        $this->db->query("UPDATE users SET role = 'teacher' WHERE role IN ('instructor', 'teacher_archive_viewer')");
        $this->db->query("UPDATE users SET role = 'coordinator' WHERE role = 'sub_admin'");
        $this->db->query("UPDATE users SET role = 'research_staff' WHERE role = 'researcher_archive_viewer'");
    }

    public function down()
    {
        $this->db->query("UPDATE users SET role = 'instructor' WHERE role = 'teacher'");
        $this->db->query("UPDATE users SET role = 'sub_admin' WHERE role = 'coordinator'");
        $this->db->query("UPDATE users SET role = 'researcher_archive_viewer' WHERE role = 'research_staff'");
    }
}
