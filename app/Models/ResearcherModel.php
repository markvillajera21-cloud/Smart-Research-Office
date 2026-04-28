<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearcherModel extends Model
{
    protected $table            = 'researchers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 
        'fullname',
        'institutional_id', 
        'category_id', 
        'expertise',
        'bio', 
        'joined_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Gets researchers joined with their user accounts.
     */
    public function getResearchersWithUsers()
    {
        return $this->select('researchers.*, users.username, users.email, research_categories.name as category_name')
                    ->join('users', 'users.id = researchers.user_id')
                    ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                    ->findAll();
    }

    /**
     * Finds a researcher profile by user ID.
     */
    public function findByUserId(int $userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}
