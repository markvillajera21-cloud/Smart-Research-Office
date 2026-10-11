<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchProgressModel extends Model
{
    protected $table            = 'research_progress';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'researcher_id', 'stage', 'stage_notes', 'updated_by',
        'progress_percentage', 'last_updated'
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

    public static array $stagePercentages = [
        'title_proposal'         => 0,
        'title_approved'         => 10,
        'pre_oral_scheduled'     => 25,
        'pre_oral_passed'        => 50,
        'final_defense_scheduled' => 75,
        'final_defense_passed'   => 90,
        'completed'              => 100,
    ];

    public static array $stageLabels = [
        'title_proposal'         => 'Title Proposal',
        'title_approved'         => 'Title Approved',
        'pre_oral_scheduled'     => 'Pre-Oral Scheduled',
        'pre_oral_passed'        => 'Pre-Oral Passed',
        'final_defense_scheduled' => 'Final Defense Scheduled',
        'final_defense_passed'   => 'Final Defense Passed',
        'completed'              => 'Completed',
    ];

    public function getByResearcher($researcherId)
    {
        return $this->select('research_progress.*, users.username as updater_name')
                    ->join('users', 'users.id = research_progress.updated_by', 'left')
                    ->where('researcher_id', $researcherId)
                    ->orderBy('last_updated', 'DESC')
                    ->first();
    }

    public function getAllWithDetails()
    {
        return $this->select('research_progress.*, researchers.fullname as researcher_name, users.username as student_name, users.email, strands.name as strand_name, research_categories.name as category_name')
                    ->join('researchers', 'researchers.id = research_progress.researcher_id')
                    ->join('users', 'users.id = researchers.user_id', 'left')
                    ->join('strands', 'strands.id = researchers.strand_id', 'left')
                    ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                    ->orderBy('research_progress.last_updated', 'DESC')
                    ->findAll();
    }

    public function getCompleted()
    {
        return $this->select('research_progress.*, researchers.fullname as researcher_name, users.username as student_name, strands.name as strand_name, research_categories.name as category_name')
                    ->join('researchers', 'researchers.id = research_progress.researcher_id')
                    ->join('users', 'users.id = researchers.user_id', 'left')
                    ->join('strands', 'strands.id = researchers.strand_id', 'left')
                    ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                    ->where('research_progress.stage', 'completed')
                    ->orderBy('research_progress.last_updated', 'DESC')
                    ->findAll();
    }

    public function getIncomplete()
    {
        return $this->select('research_progress.*, researchers.fullname as researcher_name, users.username as student_name, strands.name as strand_name, research_categories.name as category_name')
                    ->join('researchers', 'researchers.id = research_progress.researcher_id')
                    ->join('users', 'users.id = researchers.user_id', 'left')
                    ->join('strands', 'strands.id = researchers.strand_id', 'left')
                    ->join('research_categories', 'research_categories.id = researchers.category_id', 'left')
                    ->where('research_progress.stage !=', 'completed')
                    ->orderBy('research_progress.last_updated', 'ASC')
                    ->findAll();
    }

    public function getStuck($days = 60)
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-$days days"));
        return $this->select('research_progress.*, researchers.fullname as researcher_name, users.username as student_name')
                    ->join('researchers', 'researchers.id = research_progress.researcher_id')
                    ->join('users', 'users.id = researchers.user_id', 'left')
                    ->where('research_progress.stage !=', 'completed')
                    ->where('research_progress.last_updated <', $cutoff)
                    ->orderBy('research_progress.last_updated', 'ASC')
                    ->findAll();
    }

    public function getCompletionRate()
    {
        $total = $this->countAll();
        $completed = $this->where('stage', 'completed')->countAllResults(false);
        if ($total == 0) return 0;
        return round(($completed / $total) * 100, 1);
    }

    public function updateStage($researcherId, $stage, $notes = null, $updaterId = null)
    {
        $existing = $this->getByResearcher($researcherId);
        $percentage = self::$stagePercentages[$stage] ?? 0;
        $data = [
            'researcher_id'       => $researcherId,
            'stage'               => $stage,
            'progress_percentage' => $percentage,
            'stage_notes'         => $notes,
            'updated_by'          => $updaterId ?? session()->get('user_id'),
            'last_updated'        => date('Y-m-d H:i:s'),
        ];
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
