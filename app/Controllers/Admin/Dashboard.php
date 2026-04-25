<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AuditLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('admin/dashboard');
    }

    public function auditLogs()
    {
        $auditModel = new AuditLogModel();
        $data = [
            'title' => 'System Audit Logs',
            'page_title' => 'Audit Trail',
            'logs' => $auditModel->getLogsWithUsers()
        ];
        return view('admin/audit_logs', $data);
    }
}
