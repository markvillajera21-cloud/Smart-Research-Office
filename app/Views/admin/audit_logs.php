<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">System Audit Trail</h5>
            <p class="text-muted small mb-0">Track all administrative and user activities for compliance and security.</p>
        </div>
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Export Report
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Details</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="small text-nowrap">
                                <?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                        <?= $log['username'] ? strtoupper(substr($log['username'], 0, 1)) : '?' ?>
                                    </div>
                                    <span class="fw-medium small"><?= $log['username'] ?? 'System/Guest' ?></span>
                                </div>
                            </td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                switch ($log['action']) {
                                    case 'LOGIN': $badgeClass = 'bg-success'; break;
                                    case 'LOGIN_FAILED': $badgeClass = 'bg-danger'; break;
                                    case 'REGISTER': $badgeClass = 'bg-info'; break;
                                    case 'DELETE': $badgeClass = 'bg-danger'; break;
                                    case 'LOGOUT': $badgeClass = 'bg-warning text-dark'; break;
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?> small"><?= $log['action'] ?></span>
                            </td>
                            <td class="small text-muted">
                                <?= $log['entity_type'] ? ucfirst($log['entity_type']) : '-' ?>
                                <?php if ($log['entity_id']): ?>
                                    <span class="badge bg-light text-dark border ms-1">ID: <?= $log['entity_id'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 200px;" title='<?= $log['details'] ?>'>
                                    <?= $log['details'] ?: '-' ?>
                                </div>
                            </td>
                            <td class="small font-monospace text-muted">
                                <?= $log['ip_address'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No audit logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
