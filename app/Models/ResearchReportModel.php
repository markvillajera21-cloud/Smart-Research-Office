<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchReportModel extends Model
{
    protected $table            = 'research_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'report_type', 'report_params', 'generated_by', 'generated_at', 'file_path'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    public function getHistory($type = null, $limit = 50)
    {
        $this->select('research_reports.*, users.username as generator_name')
             ->join('users', 'users.id = research_reports.generated_by');
        if ($type) $this->where('report_type', $type);
        return $this->orderBy('generated_at', 'DESC')->limit($limit)->findAll();
    }

    public function logReport($type, $params = null, $generatedBy = null, $filePath = null)
    {
        $data = [
            'report_type'   => $type,
            'report_params' => is_array($params) ? json_encode($params) : $params,
            'generated_by'  => $generatedBy ?? session()->get('user_id') ?? 0,
            'generated_at'  => date('Y-m-d H:i:s'),
            'file_path'     => $filePath,
        ];
        return $this->insert($data);
    }
}
