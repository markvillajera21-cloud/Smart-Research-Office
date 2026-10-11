<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchEvaluationModel extends Model
{
    protected $table            = 'research_evaluations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'schedule_id', 'researcher_id', 'student_id', 'evaluated_by',
        'result', 'overall_score', 'remarks', 'compliance_deadline',
        'compliance_submitted', 'evaluated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    protected function tableExists(string $table): bool
    {
        try {
            return $this->db()->tableExists($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getBySchedule($scheduleId)
    {
        if (!$this->tableExists($this->table)) {
            return null;
        }

        return $this->select('research_evaluations.*, users.username as evaluator_name')
                    ->join('users', 'users.id = research_evaluations.evaluated_by', 'left')
                    ->where('schedule_id', $scheduleId)
                    ->first();
    }

    public function getStudentEvaluations($studentId)
    {
        if (!$this->tableExists($this->table)) {
            return [];
        }

        return $this->select('research_evaluations.*, rs.schedule_type, rs.schedule_date, rs.schedule_title, users.username as evaluator_name')
                    ->join('research_schedules rs', 'rs.id = research_evaluations.schedule_id', 'left')
                    ->join('users', 'users.id = research_evaluations.evaluated_by', 'left')
                    ->where('research_evaluations.student_id', $studentId)
                    ->orderBy('evaluated_at', 'DESC')
                    ->findAll();
    }

    public function getInstructorEvaluations($instructorId)
    {
        if (!$this->tableExists($this->table)) {
            return [];
        }

        return $this->select('research_evaluations.*, rs.schedule_type, rs.schedule_date, rs.schedule_title, users.username as student_name, researchers.fullname as researcher_fullname')
                    ->join('research_schedules rs', 'rs.id = research_evaluations.schedule_id', 'left')
                    ->join('users', 'users.id = research_evaluations.student_id', 'left')
                    ->join('researchers', 'researchers.id = research_evaluations.researcher_id', 'left')
                    ->groupStart()
                        ->where('rs.instructor_id', $instructorId)
                        ->orWhere('research_evaluations.evaluated_by', $instructorId)
                        ->orWhere('users.assigned_instructor_id', $instructorId)
                    ->groupEnd()
                    ->orderBy('evaluated_at', 'DESC')
                    ->findAll();
    }

    public function getSummaryStats()
    {
        if (!$this->tableExists($this->table)) {
            return ['passed' => 0, 'failed' => 0, 'for_compliance' => 0, 'total' => 0];
        }

        $passed = $this->where('result', 'passed')->countAllResults(false);
        $failed = $this->where('result', 'failed')->countAllResults(false);
        $forCompliance = $this->where('result', 'for_compliance')->countAllResults(false);
        return [
            'passed' => $passed,
            'failed' => $failed,
            'for_compliance' => $forCompliance,
            'total' => $passed + $failed + $forCompliance
        ];
    }

    public function getPendingCompliance()
    {
        if (!$this->tableExists($this->table)) {
            return [];
        }

        return $this->select('research_evaluations.*, users.username as student_name, researchers.fullname as researcher_fullname, rs.schedule_type, rs.schedule_title, rs.schedule_date, u2.username as evaluator_name')
                    ->join('users', 'users.id = research_evaluations.student_id', 'left')
                    ->join('researchers', 'researchers.id = research_evaluations.researcher_id', 'left')
                    ->join('research_schedules rs', 'rs.id = research_evaluations.schedule_id', 'left')
                    ->join('users u2', 'u2.id = research_evaluations.evaluated_by', 'left')
                    ->where('result', 'for_compliance')
                    ->where('compliance_submitted', 0)
                    ->where('compliance_deadline >=', date('Y-m-d'))
                    ->orderBy('compliance_deadline', 'ASC')
                    ->findAll();
    }

    public function getOverdueCompliance()
    {
        if (!$this->tableExists($this->table)) {
            return [];
        }

        return $this->select('research_evaluations.*, users.username as student_name, researchers.fullname as researcher_fullname, rs.schedule_type, rs.schedule_title, rs.schedule_date, u2.username as evaluator_name')
                    ->join('users', 'users.id = research_evaluations.student_id', 'left')
                    ->join('researchers', 'researchers.id = research_evaluations.researcher_id', 'left')
                    ->join('research_schedules rs', 'rs.id = research_evaluations.schedule_id', 'left')
                    ->join('users u2', 'u2.id = research_evaluations.evaluated_by', 'left')
                    ->where('result', 'for_compliance')
                    ->where('compliance_submitted', 0)
                    ->where('compliance_deadline <', date('Y-m-d'))
                    ->orderBy('compliance_deadline', 'ASC')
                    ->findAll();
    }
}
