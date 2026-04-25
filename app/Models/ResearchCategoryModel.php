<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchCategoryModel extends Model
{
    protected $table            = 'research_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all categories formatted for a dropdown [id => name]
     */
    public function getForDropdown()
    {
        $categories = $this->orderBy('name', 'ASC')->findAll();
        $options = [];
        foreach ($categories as $cat) {
            $options[$cat['id']] = $cat['name'];
        }
        return $options;
    }
}
