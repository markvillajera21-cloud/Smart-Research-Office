<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h5 class="mb-1">Examiner Registration Pool</h5>
            <p class="text-muted small mb-0">Register advisers, critics, and panel members for research evaluations and defenses.</p>
        </div>
        <a href="<?= base_url('admin/examiners/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-3 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add Examiner
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($poolMissing)): ?>
        <div class="alert alert-warning border-0">Run database migrations to create the examiner pool tables.</div>
    <?php endif; ?>

    <form method="get" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label small mb-1">Role</label>
            <select name="role" class="form-select form-select-sm">
                <option value="">All roles</option>
                <?php foreach (\App\Models\ExaminerModel::roleLabels() as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= ($roleFilter ?? '') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All statuses</option>
                <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($statusFilter ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary btn-sm w-100" type="submit">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-0">Examiner</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="text-end pe-0">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($examiners)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">No examiners registered yet.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($examiners as $examiner): ?>
                    <tr>
                        <td class="ps-0 fw-semibold"><?= esc($examiner['name']) ?></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">
                                <?= esc(\App\Models\ExaminerModel::roleLabel($examiner['examiner_role'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ((int) ($examiner['is_active'] ?? 0) === 1): ?>
                                <span class="badge bg-success-subtle text-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= !empty($examiner['created_at']) ? date('M d, Y', strtotime($examiner['created_at'])) : 'N/A' ?>
                        </td>
                        <td class="text-end pe-0">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= base_url('admin/examiners/edit/' . $examiner['id']) ?>">
                                            <i class="bi bi-pencil me-2 text-primary"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= base_url('admin/examiners/toggle/' . $examiner['id']) ?>">
                                            <i class="bi bi-slash-circle me-2"></i>
                                            <?= (int) ($examiner['is_active'] ?? 0) === 1 ? 'Deactivate' : 'Reactivate' ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="<?= base_url('admin/examiners/delete/' . $examiner['id']) ?>" onclick="return confirm('Remove this examiner from the pool?')">
                                            <i class="bi bi-trash me-2"></i> Remove
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
