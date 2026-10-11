<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpertModel extends Model
{
    public const ROLE_ADVISER     = 'adviser';
    public const ROLE_GRAMMARIAN  = 'grammarian';
    public const ROLE_STATISTICIAN = 'statistician';

    protected $table            = 'experts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'expert_role', 'specialization', 'department', 'legacy_id', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'        => 'required|min_length[2]|max_length[150]',
        'expert_role' => 'required|in_list[adviser,grammarian,statistician]',
    ];

    public static function roleLabels(): array
    {
        return [
            self::ROLE_ADVISER     => 'Adviser',
            self::ROLE_GRAMMARIAN  => 'Grammarian',
            self::ROLE_STATISTICIAN => 'Statistician',
        ];
    }

    public static function roleLabel(?string $role): string
    {
        return self::roleLabels()[$role] ?? ucwords(str_replace('_', ' ', (string) $role));
    }

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

    /**
     * Get active experts of a specific role, with fallback/compatibility ID for research assignments
     */
    public function getSelectable(string $role): array
    {
        if (!$this->poolReady()) {
            return [];
        }

        return $this->where('expert_role', $role)
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function assignmentLists(): array
    {
        return [
            'adviserExperts'     => $this->getSelectable(self::ROLE_ADVISER),
            'grammarianExperts'  => $this->getSelectable(self::ROLE_GRAMMARIAN),
            'statisticianExperts' => $this->getSelectable(self::ROLE_STATISTICIAN),
        ];
    }

    public function resolveActive(int $id, string $role): ?array
    {
        if ($id < 1 || !$this->poolReady()) {
            return null;
        }

        $expert = $this->find($id);
        if (!$expert || ($expert['expert_role'] ?? '') !== $role || (int) ($expert['is_active'] ?? 0) !== 1) {
            return null;
        }

        return $expert;
    }
}
