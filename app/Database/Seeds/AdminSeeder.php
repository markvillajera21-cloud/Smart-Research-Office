<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $users = $this->db->table('users');
        $adminExists = $users->where('username', 'admin')->get()->getRow();

        if (!$adminExists) {
            $data = [
                'username' => 'admin',
                'email'    => 'admin@smartoffice.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $users->insert($data);
        }
    }
}
