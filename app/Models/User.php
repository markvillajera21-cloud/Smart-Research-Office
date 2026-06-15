<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'email', 'password', 'role'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]',
        'role'     => 'in_list[admin,teacher_archive_viewer,student_archive_viewer]'
    ];
    protected $validationMessages   = [
        'username' => [
            'is_unique' => 'This username is already taken.',
        ],
        'email' => [
            'is_unique' => 'This email address is already registered.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function save($data = null): bool
    {
        // Store original validation rules
        $originalRules = $this->validationRules;

        try {
            // Check if we're updating or inserting
            $isUpdate = isset($data['id']) || (is_array($data) && isset($data[0]['id']));
            
            if ($isUpdate) {
                // Get the ID
                $id = isset($data['id']) ? $data['id'] : $data[0]['id'];
                
                // Modify validation rules for update - exclude current record from unique checks
                $this->validationRules['username'] = "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{$id}]";
                $this->validationRules['email'] = "required|valid_email|is_unique[users.email,id,{$id}]";
                
                // Remove password required rule for updates
                $this->validationRules['password'] = 'permit_empty|min_length[8]';
            }

            // Call parent save method
            return parent::save($data);
        } finally {
            // Restore original validation rules
            $this->validationRules = $originalRules;
        }
    }
}
