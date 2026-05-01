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
        'surname',
        'first_name',
        'middle_initial',
        'designation_id',
        'school_year_id',
        'strand_id',
        'course_id',
        'status_id',
        'adviser_id',
        'grammarian_id',
        'remark_id',
        'abstract',
        'category_id', 
        'expertise',
        'bio', 
        'joined_at',
        'approved_research_title',
        'approved_date',
        'strand_degree_program',
        'school_year',
        'status'
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
