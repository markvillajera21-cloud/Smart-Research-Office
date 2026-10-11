<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\ExpertModel;
use App\Models\AdviserModel;
use App\Models\GrammarianModel;
use App\Models\RemarkModel;

class Experts extends BaseController
{
    protected ExpertModel $expertModel;
    protected AdviserModel $adviserModel;
    protected GrammarianModel $grammarianModel;
    protected RemarkModel $remarkModel;

    public function __construct()
    {
        $this->expertModel      = new ExpertModel();
        $this->adviserModel     = new AdviserModel();
        $this->grammarianModel  = new GrammarianModel();
        $this->remarkModel      = new RemarkModel();
    }

    private function syncToLegacyTable(string $role, string $name, ?int $legacyId = null): ?int
    {
        try {
            $model = null;
            if ($role === ExpertModel::ROLE_ADVISER) {
                $model = $this->adviserModel;
            } elseif ($role === ExpertModel::ROLE_GRAMMARIAN) {
                $model = $this->grammarianModel;
            } elseif ($role === ExpertModel::ROLE_STATISTICIAN) {
                $model = $this->remarkModel;
            }

            if (!$model) {
                return null;
            }

            if ($legacyId && $model->find($legacyId)) {
                $model->update($legacyId, ['name' => $name]);
                return $legacyId;
            }

            $existing = $model->where('name', $name)->first();
            if ($existing) {
                return (int) $existing['id'];
            }

            $newId = $model->insert(['name' => $name]);
            return $newId ? (int) $newId : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function index()
    {
        $role   = (string) $this->request->getGet('role');
        $status = (string) $this->request->getGet('status');

        if (!$this->expertModel->poolReady()) {
            return view('admin/experts/index', [
                'experts'      => [],
                'roleFilter'   => $role,
                'statusFilter' => $status,
                'poolMissing'  => true,
            ]);
        }

        $query = $this->expertModel->orderBy('expert_role', 'ASC')->orderBy('name', 'ASC');
        if (array_key_exists($role, ExpertModel::roleLabels())) {
            $query->where('expert_role', $role);
        }
        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        return view('admin/experts/index', [
            'experts'      => $query->findAll(),
            'roleFilter'   => $role,
            'statusFilter' => $status,
            'poolMissing'  => false,
        ]);
    }

    public function create()
    {
        return view('admin/experts/create');
    }

    public function store()
    {
        $name           = trim((string) $this->request->getPost('name'));
        $role           = (string) $this->request->getPost('expert_role');
        $specialization = trim((string) $this->request->getPost('specialization'));
        $department     = trim((string) $this->request->getPost('department'));
        $isActive       = $this->request->getPost('is_active') !== null ? ($this->request->getPost('is_active') ? 1 : 0) : 1;

        $legacyId = $this->syncToLegacyTable($role, $name);

        $data = [
            'name'           => $name,
            'expert_role'    => $role,
            'specialization' => $specialization ?: null,
            'department'     => $department ?: null,
            'legacy_id'      => $legacyId,
            'is_active'      => $isActive,
        ];

        if ($this->expertModel->save($data)) {
            $newId = $this->expertModel->getInsertID() ?: null;
            AuditLogModel::log('EXPERT_CREATE', 'experts', $newId, $data);
            return redirect()->to('/admin/experts')->with('success', 'Expert registered successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->expertModel->errors());
    }

    public function edit($id)
    {
        $expert = $this->expertModel->find($id);
        if (!$expert) {
            return redirect()->to('/admin/experts')->with('error', 'Expert not found');
        }

        return view('admin/experts/edit', ['expert' => $expert]);
    }

    public function update($id)
    {
        $expert = $this->expertModel->find($id);
        if (!$expert) {
            return redirect()->to('/admin/experts')->with('error', 'Expert not found');
        }

        $name           = trim((string) $this->request->getPost('name'));
        $role           = (string) $this->request->getPost('expert_role');
        $specialization = trim((string) $this->request->getPost('specialization'));
        $department     = trim((string) $this->request->getPost('department'));
        $isActive       = $this->request->getPost('is_active') ? 1 : 0;

        $legacyId = $this->syncToLegacyTable($role, $name, $expert['legacy_id'] ? (int) $expert['legacy_id'] : null);

        $data = [
            'id'             => (int) $id,
            'name'           => $name,
            'expert_role'    => $role,
            'specialization' => $specialization ?: null,
            'department'     => $department ?: null,
            'legacy_id'      => $legacyId,
            'is_active'      => $isActive,
        ];

        if ($this->expertModel->save($data)) {
            AuditLogModel::log('EXPERT_UPDATE', 'experts', (int) $id, $data);
            return redirect()->to('/admin/experts')->with('success', 'Expert updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->expertModel->errors());
    }

    public function toggle($id)
    {
        $expert = $this->expertModel->find($id);
        if (!$expert) {
            return redirect()->to('/admin/experts')->with('error', 'Expert not found');
        }

        $nextStatus = (int) ($expert['is_active'] ?? 0) === 1 ? 0 : 1;
        $this->expertModel->update($id, ['is_active' => $nextStatus]);
        AuditLogModel::log('EXPERT_TOGGLE', 'experts', (int) $id, [
            'name'      => $expert['name'] ?? null,
            'is_active' => $nextStatus,
        ]);

        $message = $nextStatus === 1 ? 'Expert reactivated' : 'Expert deactivated and removed from selection lists';
        return redirect()->to('/admin/experts')->with('success', $message);
    }

    public function delete($id)
    {
        $expert = $this->expertModel->find($id);
        if (!$expert) {
            return redirect()->to('/admin/experts')->with('error', 'Expert not found');
        }

        if ($this->expertModel->delete($id)) {
            AuditLogModel::log('EXPERT_DELETE', 'experts', (int) $id, $expert);
            return redirect()->to('/admin/experts')->with('success', 'Expert removed from the pool');
        }

        return redirect()->to('/admin/experts')->with('error', 'Failed to remove expert');
    }
}
