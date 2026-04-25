<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'details',
        'ip_address',
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // We don't need updated_at for logs

    /**
     * Record a system activity
     * 
     * @param string $action e.g. 'LOGIN', 'CREATE', 'UPDATE', 'DELETE'
     * @param string|null $entityType e.g. 'users', 'projects'
     * @param int|null $entityId The ID of the affected record
     * @param array|null $details Additional data about the action
     */
    public static function log(string $action, ?string $entityType = null, ?int $entityId = null, ?array $details = null)
    {
        $model = new self();
        $request = service('request');
        
        $data = [
            'user_id'     => session()->get('user_id'),
            'action'      => strtoupper($action),
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'details'     => $details ? json_encode($details) : null,
            'ip_address'  => $request->getIPAddress(),
            'user_agent'  => $request->getUserAgent()->getAgentString()
        ];

        return $model->insert($data);
    }

    /**
     * Get logs with user information
     */
    public function getLogsWithUsers()
    {
        return $this->select('audit_logs.*, users.username')
                    ->join('users', 'users.id = audit_logs.user_id', 'left')
                    ->orderBy('audit_logs.created_at', 'DESC')
                    ->findAll();
    }
}
