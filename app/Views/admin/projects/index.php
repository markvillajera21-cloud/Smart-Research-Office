<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark">Projects</h4>
        <p class="text-muted mb-0">Create and manage projects.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="<?= base_url('admin/projects') ?>" method="get" class="d-flex gap-2">
            <div class="input-group shadow-sm border rounded" style="width: 320px;">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search projects..." value="<?= $search ?? '' ?>">
            </div>
            <?php if (($search ?? '') !== ''): ?>
                <a href="<?= base_url('admin/projects') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
        <a href="<?= base_url('admin/projects/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-3">
            <i class="bi bi-journal-plus me-2"></i> Create Project
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                    <th class="fw-semibold">Title</th>
                    <th class="fw-semibold" style="width: 140px;">Status</th>
                    <th class="fw-semibold" style="width: 160px;">Created</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $p): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= esc($p['title'] ?? '') ?></div>
                                <?php if (!empty($p['description'])): ?>
                                    <div class="small text-muted" style="max-width: 720px;">
                                        <?= esc(strlen($p['description']) > 140 ? substr($p['description'], 0, 140) . '...' : $p['description']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $status = $p['status'] ?? 'draft';
                                    $badge = match ($status) {
                                        'active' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                        'completed' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
                                        default => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                    };
                                ?>
                                <span class="badge <?= $badge ?> small text-uppercase"><?= esc($status) ?></span>
                            </td>
                            <td class="small text-muted">
                                <?= !empty($p['created_at']) ? date('M d, Y', strtotime($p['created_at'])) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                            No projects yet. Create your first project.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

