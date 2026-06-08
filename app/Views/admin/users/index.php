<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h5 class="mb-1">Role Management</h5>
            <p class="text-muted small mb-0">Manage system roles and administrators.</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <?php if (session()->get('role') === 'admin'): ?>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-2"></i> Add User
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-0">User Info</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th class="text-end pe-0">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="ps-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark"><?= esc($user['username']) ?></div>
                                    <div class="text-muted small"><?= esc($user['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php 
                                $roleBadges = [
                                    'user' => 'bg-secondary-subtle text-secondary',
                                    'admin' => 'bg-danger-subtle text-danger',
                                    'archive_viewer' => 'bg-info-subtle text-info'
                                ];
                                $badgeClass = $roleBadges[$user['role']] ?? 'bg-secondary-subtle text-secondary';
                                $roleNames = [
                                    'user' => 'User',
                                    'admin' => 'Admin',
                                    'archive_viewer' => 'Archive Viewer'
                                ];
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= $roleNames[$user['role']] ?? ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td class="text-muted small">
                            <?= date('M d, Y', strtotime($user['created_at'])) ?>
                        </td>
                        <td class="text-end pe-0">
                            <?php if (session()->get('role') === 'admin'): ?>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= base_url('admin/users/edit/' . $user['id']) ?>">
                                            <i class="bi bi-pencil me-2 text-primary"></i> Edit Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="<?= base_url('admin/users/delete/' . $user['id']) ?>" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash me-2"></i> Delete Account
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
