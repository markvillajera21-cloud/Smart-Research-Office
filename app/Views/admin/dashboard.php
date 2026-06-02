<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="<?= base_url('admin/users') ?>" class="text-decoration-none">
            <div class="card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #fef9c3, #facc15);">
                        <i class="bi bi-people-fill fs-4" style="color: #1e3a8a;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: #475569; font-size: 0.875rem; font-weight: 500;">Total Users</h6>
                        <h3 class="mb-0" style="color: #0f172a; font-weight: 700;"></h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="<?= base_url('admin/researchers/update-status') ?>" class="text-decoration-none">
            <div class="card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                        <i class="bi bi-file-earmark-text fs-4" style="color: #1e40af;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: #475569; font-size: 0.875rem; font-weight: 500;">Pending Reviews</h6>
                        <h3 class="mb-0" style="color: #0f172a; font-weight: 700;"><?= $pendingReviews ?></h3>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <i class="bi bi-clock-history fs-4" style="color: #1e40af;"></i>
                </div>
                <div>
                    <h6 class="mb-1" style="color: #475569; font-size: 0.875rem; font-weight: 500;">System Health</h6>
                    <h3 class="mb-0" style="color: #0f172a; font-weight: 700;"><?= $systemHealth ?>%</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="<?= base_url('admin/uploads') ?>" class="text-decoration-none">
            <div class="card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                        <i class="bi bi-cloud-arrow-up fs-4" style="color: #1e40af;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: #475569; font-size: 0.875rem; font-weight: 500;">Upload Data</h6>
                        <h3 class="mb-0" style="color: #0f172a; font-weight: 700;"><i class="bi bi-arrow-up-right-circle"></i></h3>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0" style="color: #0f172a; font-weight: 700;">Recent Activities</h5>
                <a href="<?= base_url('admin/audit-logs') ?>" class="btn btn-sm btn-outline-secondary" style="font-weight: 600;">View All</a>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 py-4 border-0 border-bottom" style="border-color: #e2e8f0;">
                    <div class="d-flex gap-3">
                        <div class="p-2 rounded-circle" style="background-color: #eff6ff;">
                            <i class="bi bi-person-plus" style="color: #1e40af;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="color: #0f172a; font-weight: 600;">New researcher registered</p>
                            <small style="color: #64748b;">Dr. Sarah Johnson joined the team • 2 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-0 py-4 border-0 border-bottom" style="border-color: #e2e8f0;">
                    <div class="d-flex gap-3">
                        <div class="p-2 rounded-circle" style="background-color: #f0fdf4;">
                            <i class="bi bi-file-earmark-check" style="color: #166534;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="color: #0f172a; font-weight: 600;">Project proposal approved</p>
                            <small style="color: #64748b;">Quantum Computing Phase 1 • 5 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-0 py-4 border-0">
                    <div class="d-flex gap-3">
                        <div class="p-2 rounded-circle" style="background-color: #fef9c3;">
                            <i class="bi bi-exclamation-triangle" style="color: #854d0e;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="color: #0f172a; font-weight: 600;">Database maintenance scheduled</p>
                            <small style="color: #64748b;">System will be offline for 15 mins • Yesterday</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card h-100 p-4">
            <h5 class="mb-4" style="color: #0f172a; font-weight: 700;">Quick Actions</h5>
            <div class="d-grid gap-3">
                <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary fw-semibold py-3 text-start px-4">
                    <i class="bi bi-person-plus-fill me-2"></i> Add Role
                </a>
            </div>
            <div class="mt-auto pt-4">
                <div class="p-4 rounded-3" style="background-color: #f8fafc;">
                    <p class="small mb-0" style="color: #475569; font-weight: 500;">Storage Used</p>
                    <div class="progress mt-3 mb-2" style="height: 8px; background-color: #e2e8f0;">
                        <div class="progress-bar" style="width: 65%; background: linear-gradient(90deg, #1e40af, #1e3a8a);"></div>
                    </div>
                    <small style="color: #64748b; font-weight: 500;">12.5 GB of 20 GB</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
