<?php

namespace App\Models;

use CodeIgniter\Model;

class ExaminerModel extends Model
{
    public const ROLE_ADVISER = 'adviser';
    public const ROLE_CRITIC = 'critic';
    public const ROLE_PANEL = 'panel_member';

    protected $table            = 'examiners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'examiner_role', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'          => 'required|min_length[2]|max_length[150]',
        'examiner_role' => 'required|in_list[adviser,critic,panel_member]',
    ];

    public static function roleLabels(): array
    {
        return [
            self::ROLE_ADVISER => 'Adviser',
            self::ROLE_CRITIC  => 'Critic',
            self::ROLE_PANEL   => 'Panel Member',
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

    public function getSelectable(string $role): array
    {
        if (!$this->poolReady()) {
            return [];
        }

        return $this->where('examiner_role', $role)
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function syncRegisteredPersonnel(): void
    {
        if (!$this->poolReady()) {
            return;
        }

        try {
            $db = \Config\Database::connect();

            // 1. Sync registered advisers from advisers table into examiners pool
            if ($db->tableExists('advisers')) {
                $advisers = $db->table('advisers')->get()->getResultArray();
                foreach ($advisers as $adv) {
                    $name = trim($adv['name'] ?? '');
                    if ($name === '') {
                        continue;
                    }
                    $existing = $this->where('examiner_role', self::ROLE_ADVISER)
                                     ->where('name', $name)
                                     ->first();
                    if (!$existing) {
                        $this->insert([
                            'name'          => $name,
                            'examiner_role' => self::ROLE_ADVISER,
                            'is_active'     => 1,
                        ]);
                    }
                }
            }

            // 2. Sync advisers from experts table where expert_role = 'adviser'
            if ($db->tableExists('experts')) {
                $experts = $db->table('experts')
                              ->where('expert_role', 'adviser')
                              ->where('is_active', 1)
                              ->get()->getResultArray();
                foreach ($experts as $exp) {
                    $name = trim($exp['name'] ?? '');
                    if ($name === '') {
                        continue;
                    }
                    $existing = $this->where('examiner_role', self::ROLE_ADVISER)
                                     ->where('name', $name)
                                     ->first();
                    if (!$existing) {
                        $this->insert([
                            'name'          => $name,
                            'examiner_role' => self::ROLE_ADVISER,
                            'is_active'     => 1,
                        ]);
                    }
                }
            }

            // 3. Sync registered faculty / instructors / teachers from users table into panel members pool
            if ($db->tableExists('users')) {
                $faculty = $db->table('users')
                              ->whereIn('role', ['teacher', 'instructor', 'admin', 'coordinator'])
                              ->get()->getResultArray();
                foreach ($faculty as $fac) {
                    $name = trim($fac['username'] ?? '');
                    if ($name === '') {
                        continue;
                    }
                    $existing = $this->where('examiner_role', self::ROLE_PANEL)
                                     ->where('name', $name)
                                     ->first();
                    if (!$existing) {
                        $this->insert([
                            'name'          => $name,
                            'examiner_role' => self::ROLE_PANEL,
                            'is_active'     => 1,
                        ]);
                    }
                }
            }

            // 4. Also make registered advisers available as potential panel members if needed
            if ($db->tableExists('advisers')) {
                $advisers = $db->table('advisers')->get()->getResultArray();
                foreach ($advisers as $adv) {
                    $name = trim($adv['name'] ?? '');
                    if ($name === '') {
                        continue;
                    }
                    $existing = $this->where('examiner_role', self::ROLE_PANEL)
                                     ->where('name', $name)
                                     ->first();
                    if (!$existing) {
                        $this->insert([
                            'name'          => $name,
                            'examiner_role' => self::ROLE_PANEL,
                            'is_active'     => 1,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently continue if sync fails
        }
    }

    public function assignmentLists(): array
    {
        $this->syncRegisteredPersonnel();

        return [
            'adviserExaminers' => $this->getSelectable(self::ROLE_ADVISER),
            'criticExaminers'  => $this->getSelectable(self::ROLE_CRITIC),
            'panelExaminers'   => $this->getSelectable(self::ROLE_PANEL),
        ];
    }

    public function resolveActive(int $id, string $role): ?array
    {
        if ($id < 1 || !$this->poolReady()) {
            return null;
        }

        $examiner = $this->find($id);
        if (!$examiner || ($examiner['examiner_role'] ?? '') !== $role || (int) ($examiner['is_active'] ?? 0) !== 1) {
            return null;
        }

        return $examiner;
    }
}
