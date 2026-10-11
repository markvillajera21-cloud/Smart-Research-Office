<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\ExaminerModel;

class Examiners extends BaseController
{
    protected ExaminerModel $examinerModel;

    public function __construct()
    {
        $this->examinerModel = new ExaminerModel();
    }

    public function index()
    {
        $role = (string) $this->request->getGet('role');
        $status = (string) $this->request->getGet('status');

        if (!$this->examinerModel->poolReady()) {
            return view('admin/examiners/index', [
                'examiners' => [],
                'roleFilter' => $role,
                'statusFilter' => $status,
                'poolMissing' => true,
            ]);
        }

        $query = $this->examinerModel->orderBy('examiner_role', 'ASC')->orderBy('name', 'ASC');
        if (array_key_exists($role, ExaminerModel::roleLabels())) {
            $query->where('examiner_role', $role);
        }
        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        return view('admin/examiners/index', [
            'examiners' => $query->findAll(),
            'roleFilter' => $role,
            'statusFilter' => $status,
            'poolMissing' => false,
        ]);
    }

    public function create()
    {
        return view('admin/examiners/create');
    }

    public function store()
    {
        $data = [
            'name'          => trim((string) $this->request->getPost('name')),
            'examiner_role' => (string) $this->request->getPost('examiner_role'),
            'is_active'     => 1,
        ];

        if ($this->examinerModel->save($data)) {
            $newId = $this->examinerModel->getInsertID() ?: null;
            AuditLogModel::log('EXAMINER_CREATE', 'examiners', $newId, $data);
            return redirect()->to('/admin/examiners')->with('success', 'Examiner registered successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->examinerModel->errors());
    }

    public function edit($id)
    {
        $examiner = $this->examinerModel->find($id);
        if (!$examiner) {
            return redirect()->to('/admin/examiners')->with('error', 'Examiner not found');
        }

        return view('admin/examiners/edit', ['examiner' => $examiner]);
    }

    public function update($id)
    {
        $examiner = $this->examinerModel->find($id);
        if (!$examiner) {
            return redirect()->to('/admin/examiners')->with('error', 'Examiner not found');
        }

        $data = [
            'id'            => (int) $id,
            'name'          => trim((string) $this->request->getPost('name')),
            'examiner_role' => (string) $this->request->getPost('examiner_role'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->examinerModel->save($data)) {
            AuditLogModel::log('EXAMINER_UPDATE', 'examiners', (int) $id, $data);
            return redirect()->to('/admin/examiners')->with('success', 'Examiner updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->examinerModel->errors());
    }

    public function toggle($id)
    {
        $examiner = $this->examinerModel->find($id);
        if (!$examiner) {
            return redirect()->to('/admin/examiners')->with('error', 'Examiner not found');
        }

        $nextStatus = (int) ($examiner['is_active'] ?? 0) === 1 ? 0 : 1;
        $this->examinerModel->update($id, ['is_active' => $nextStatus]);
        AuditLogModel::log('EXAMINER_TOGGLE', 'examiners', (int) $id, [
            'name' => $examiner['name'] ?? null,
            'is_active' => $nextStatus,
        ]);

        $message = $nextStatus === 1 ? 'Examiner reactivated' : 'Examiner deactivated and removed from selection lists';
        return redirect()->to('/admin/examiners')->with('success', $message);
    }

    public function delete($id)
    {
        $examiner = $this->examinerModel->find($id);
        if (!$examiner) {
            return redirect()->to('/admin/examiners')->with('error', 'Examiner not found');
        }

        if ($this->examinerModel->delete($id)) {
            AuditLogModel::log('EXAMINER_DELETE', 'examiners', (int) $id, $examiner);
            return redirect()->to('/admin/examiners')->with('success', 'Examiner removed from the pool');
        }

        return redirect()->to('/admin/examiners')->with('error', 'Failed to remove examiner');
    }
}
