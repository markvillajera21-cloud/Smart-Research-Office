<?php

namespace App\Models;

use CodeIgniter\Model;

class ResearchScheduleExaminerModel extends Model
{
    protected $table            = 'research_schedule_examiners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['schedule_id', 'examiner_id', 'assignment_role'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected function tableExists(string $table): bool
    {
        try {
            return $this->db->tableExists($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function poolReady(): bool
    {
        return $this->tableExists($this->table);
    }

    public function getAssignedMap(int $scheduleId): array
    {
        $map = [
            'adviser'      => null,
            'critic'       => null,
            'panel_member' => [],
        ];

        if ($scheduleId < 1 || !$this->poolReady()) {
            return $map;
        }

        $rows = $this->where('schedule_id', $scheduleId)->findAll();
        foreach ($rows as $row) {
            $role = $row['assignment_role'] ?? '';
            $examinerId = (int) ($row['examiner_id'] ?? 0);
            if ($role === ExaminerModel::ROLE_PANEL) {
                $map['panel_member'][] = $examinerId;
            } elseif ($role === ExaminerModel::ROLE_ADVISER || $role === ExaminerModel::ROLE_CRITIC) {
                $map[$role] = $examinerId;
            }
        }

        return $map;
    }

    public function syncAssignments(int $scheduleId, ?int $adviserId, ?int $criticId, array $panelIds): string
    {
        if ($scheduleId < 1 || !$this->poolReady()) {
            return '';
        }

        $examinerModel = new ExaminerModel();
        if (!$examinerModel->poolReady()) {
            return '';
        }

        $this->where('schedule_id', $scheduleId)->delete();

        $parts = [];
        $adviser = $adviserId ? $examinerModel->resolveActive($adviserId, ExaminerModel::ROLE_ADVISER) : null;
        if ($adviser) {
            $this->insert([
                'schedule_id'     => $scheduleId,
                'examiner_id'     => (int) $adviser['id'],
                'assignment_role' => ExaminerModel::ROLE_ADVISER,
            ]);
            $parts[] = 'Adviser: ' . $adviser['name'];
        }

        $critic = $criticId ? $examinerModel->resolveActive($criticId, ExaminerModel::ROLE_CRITIC) : null;
        if ($critic) {
            $this->insert([
                'schedule_id'     => $scheduleId,
                'examiner_id'     => (int) $critic['id'],
                'assignment_role' => ExaminerModel::ROLE_CRITIC,
            ]);
            $parts[] = 'Critic: ' . $critic['name'];
        }

        $panelNames = [];
        foreach (array_unique(array_filter(array_map('intval', $panelIds))) as $panelId) {
            $panelist = $examinerModel->resolveActive($panelId, ExaminerModel::ROLE_PANEL);
            if (!$panelist) {
                continue;
            }
            $this->insert([
                'schedule_id'     => $scheduleId,
                'examiner_id'     => (int) $panelist['id'],
                'assignment_role' => ExaminerModel::ROLE_PANEL,
            ]);
            $panelNames[] = $panelist['name'];
        }
        if ($panelNames !== []) {
            $parts[] = 'Panel Members: ' . implode(', ', $panelNames);
        }

        return implode('; ', $parts);
    }

    public function applyFromRequest(int $scheduleId, $request): ?string
    {
        $adviserId = (int) $request->getPost('examiner_adviser_id');
        $criticId  = (int) $request->getPost('examiner_critic_id');
        $panelIds  = array_values(array_filter(array_map('intval', (array) $request->getPost('examiner_panel_ids'))));

        if ($adviserId < 1 && $criticId < 1 && $panelIds === []) {
            return null;
        }

        $committeeStr = $this->syncAssignments($scheduleId, $adviserId ?: null, $criticId ?: null, $panelIds);
        $extraNotes   = trim((string) $request->getPost('panelists'));

        if (!empty($extraNotes) && !str_starts_with($extraNotes, 'Adviser:') && !str_starts_with($extraNotes, 'Critic:') && !str_starts_with($extraNotes, 'Panel Members:')) {
            return $committeeStr . ' | Notes: ' . $extraNotes;
        }

        return $committeeStr;
    }
}
