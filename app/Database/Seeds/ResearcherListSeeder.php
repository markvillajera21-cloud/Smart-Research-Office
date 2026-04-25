<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ResearcherModel;
use App\Models\User;

class ResearcherListSeeder extends Seeder
{
    public function run()
    {
        $userModel = new User();
        $researcherModel = new ResearcherModel();

        // Get admin and some other users
        $users = $userModel->limit(5)->findAll();

        $categories = ['STEM', 'Social Sciences', 'Humanities', 'Health & Medicine', 'Technology'];

        foreach ($users as $index => $user) {
            // Check if profile already exists
            if (!$researcherModel->where('user_id', $user['id'])->first()) {
                $researcherModel->insert([
                    'user_id'          => $user['id'],
                    'institutional_id' => 'SRO-' . date('Y') . '-' . str_pad($user['id'], 4, '0', STR_PAD_LEFT),
                    'category'         => $categories[$index % count($categories)],
                    'expertise'        => 'Specialist in ' . $categories[$index % count($categories)],
                    'bio'              => 'Professional researcher profile for ' . $user['username'],
                    'joined_at'        => date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days'))
                ]);
            }
        }
    }
}
