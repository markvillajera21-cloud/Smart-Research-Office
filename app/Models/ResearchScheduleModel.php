<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchScheduleModel extends Model
{
    protected $table            = 'research_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'researcher_id', 'student_id', 'instructor_id', 'schedule_type',
        'schedule_title', 'schedule_date', 'schedule_time', 'venue',
        'panelists', 'status', 'created_by'
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

    public function getByDateRange($startDate, $endDate, $type = null)
    {
        if (!$this->tableExists($this->table) || !$this->tableExists('users') || !$this->tableExists('researchers')) {
            return [];
        }

        $this->select('research_schedules.*, users.username as student_name, u2.username as instructor_name, researchers.fullname as researcher_name,
             researchers.approved_research_title as researcher_approved_title,
             COALESCE(research_titles.title, researchers.approved_research_title) as research_title')
             ->join('users', 'users.id = research_schedules.student_id', 'left')
             ->join('users u2', 'u2.id = research_schedules.instructor_id', 'left')
             ->join('researchers', 'researchers.id = research_schedules.researcher_id', 'left')
             ->join('research_titles', 'research_titles.researcher_id = research_schedules.researcher_id
                    OR research_titles.student_id = research_schedules.student_id', 'left')
             ->where('schedule_date >=', $startDate)
             ->where('schedule_date <=', $endDate);
        if ($type) {
            $this->where('schedule_type', $type);
        }
        return $this->groupBy('research_schedules.id')->orderBy('schedule_date', 'ASC')->orderBy('schedule_time', 'ASC')->findAll();
    }

    public function getStudentSchedules($studentId)
    {
        if (!$this->tableExists($this->table) || !$this->tableExists('users')) {
            return [];
        }

        return $this->select('research_schedules.*, u2.username as instructor_name')
                    ->join('users u2', 'u2.id = research_schedules.instructor_id', 'left')
                    ->where('student_id', $studentId)
                    ->orderBy('schedule_date', 'DESC')
                    ->findAll();
    }

    public function getInstructorSchedules($instructorId)
    {
        if (!$this->tableExists($this->table) || !$this->tableExists('users') || !$this->tableExists('researchers')) {
            return [];
        }

        $instructorId = (int) $instructorId;

        return $this->select('research_schedules.*, users.username as student_name, researchers.fullname as researcher_name')
                    ->join('users', 'users.id = research_schedules.student_id', 'left')
                    ->join('researchers', 'researchers.id = research_schedules.researcher_id', 'left')
                    ->groupStart()
                        ->where('instructor_id', $instructorId)
                        ->orWhere('FIND_IN_SET(' . $this->db->escape((string) $instructorId) . ", REPLACE(panelists, ' ', '')) > 0", null, false)
                    ->groupEnd()
                    ->orderBy('schedule_date', 'DESC')
                    ->findAll();
    }

    /**
     * Returns all Pre-Oral Defense schedules with full researcher, author,
     * strand/course, and research title context for the Pre-Oral list view.
     *
     * @param  string|null $search   Filter by researcher name or schedule title
     * @param  string|null $status   Filter by schedule status (scheduled, completed, etc.)
     * @return array
     */
    public function getPreOralList(?string $search = null, ?string $status = null): array
    {
        if (!$this->tableExists($this->table)) {
            return [];
        }

        $this->select(
            'research_schedules.id,
             research_schedules.schedule_title,
             research_schedules.schedule_date,
             research_schedules.schedule_time,
             research_schedules.venue,
             research_schedules.status,
             research_schedules.researcher_id,
             research_schedules.student_id,
             researchers.fullname                AS researcher_name,
             researchers.approved_research_title AS researcher_approved_title,
             researchers.strand_degree_program,
             users.username                      AS student_name,
             strands.name                        AS strand_name,
             courses.name                        AS course_name,
             research_titles.title               AS research_title'
        )
        ->join('researchers',      'researchers.id = research_schedules.researcher_id', 'left')
        ->join('users',            'users.id = research_schedules.student_id',          'left')
        ->join('strands',          'strands.id = researchers.strand_id',                'left')
        ->join('courses',          'courses.id = researchers.course_id',                'left')
        ->join(
            'research_titles',
            'research_titles.researcher_id = research_schedules.researcher_id
             OR research_titles.student_id = research_schedules.student_id',
            'left'
        )
        ->where('research_schedules.schedule_type', 'pre_oral');

        if (!empty($status)) {
            $this->where('research_schedules.status', $status);
        }

        if (!empty($search)) {
            $this->groupStart()
                 ->like('researchers.fullname',             $search)
                 ->orLike('users.username',                 $search)
                 ->orLike('research_schedules.schedule_title', $search)
                 ->orLike('research_titles.title',          $search)
                 ->groupEnd();
        }

        return $this->groupBy('research_schedules.id')
                    ->orderBy('research_schedules.schedule_date', 'ASC')
                    ->orderBy('research_schedules.schedule_time', 'ASC')
                    ->findAll();
    }
}
