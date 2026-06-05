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
                        <h6 class="mb-1" style="color: #64748b; font-size: 0.875rem; font-weight: 600;">Total Users</h6>
                        <h3 class="mb-0" style="color: #1e3a8a; font-weight: 800;"></h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="<?= base_url('admin/researchers/update-status') ?>" class="text-decoration-none">
            <div class="card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <i class="bi bi-file-earmark-text fs-4" style="color: #1e3a8a;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: #64748b; font-size: 0.875rem; font-weight: 600;">Pending Reviews</h6>
                        <h3 class="mb-0" style="color: #1e3a8a; font-weight: 800;"><?= $pendingReviews ?></h3>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                    <i class="bi bi-clock-history fs-4" style="color: #1e3a8a;"></i>
                </div>
                <div>
                    <h6 class="mb-1" style="color: #64748b; font-size: 0.875rem; font-weight: 600;">System Health</h6>
                    <h3 class="mb-0" style="color: #1e3a8a; font-weight: 800;"><?= $systemHealth ?>%</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="<?= base_url('admin/uploads') ?>" class="text-decoration-none">
            <div class="card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <i class="bi bi-cloud-arrow-up fs-4" style="color: #1e3a8a;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: #64748b; font-size: 0.875rem; font-weight: 600;">Upload Data</h6>
                        <h3 class="mb-0" style="color: #1e3a8a; font-weight: 800;"><i class="bi bi-arrow-up-right-circle"></i></h3>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="card h-100 p-4">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 800;">Quick Actions</h5>
            <div class="row g-3">
                <div class="col-12">
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary fw-bold py-3 w-100">
                        <i class="bi bi-person-plus-fill me-2"></i> Add Role
                    </a>
                </div>
                <div class="col-12">
                    <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-success fw-bold py-3 w-100">
                        <i class="bi bi-file-earmark-plus-fill me-2"></i> Add Researcher
                    </a>
                </div>
                <div class="col-12">
                    <a href="<?= base_url('admin/researchers') ?>" class="btn btn-info fw-bold py-3 w-100">
                        <i class="bi bi-folder-plus-fill me-2"></i> New Project
                    </a>
                </div>
                <div class="col-12">
                    <a href="<?= base_url('admin/uploads') ?>" class="btn btn-warning fw-bold py-3 w-100" style="color: #1e3a8a;">
                        <i class="bi bi-cloud-upload-fill me-2"></i> Upload File
                    </a>
                </div>
                <div class="col-12">
                    <a href="<?= base_url('admin/researchers/update-status') ?>" class="btn btn-secondary fw-bold py-3 w-100">
                        <i class="bi bi-check-circle-fill me-2"></i> Review Pending
                    </a>
                </div>
                <div class="col-12">
                    <a href="<?= base_url('admin/audit-logs') ?>" class="btn btn-dark fw-bold py-3 w-100">
                        <i class="bi bi-clock-history me-2"></i> View Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0" style="color: #1e3a8a; font-weight: 800;">Recent Activities</h5>
                <a href="<?= base_url('admin/audit-logs') ?>" class="btn btn-sm btn-outline-secondary" style="font-weight: 700;">View All</a>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 py-4 border-0 border-bottom" style="border-color: #e2e8f0;">
                    <div class="d-flex gap-3">
                        <div class="p-2 rounded-circle" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                            <i class="bi bi-person-plus" style="color: #1e3a8a;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="color: #1e3a8a; font-weight: 700;">New researcher registered</p>
                            <small style="color: #64748b; font-weight: 500;">Dr. Sarah Johnson joined the team • 2 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-0 py-4 border-0 border-bottom" style="border-color: #e2e8f0;">
                    <div class="d-flex gap-3">
                        <div class="p-2 rounded-circle" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                            <i class="bi bi-file-earmark-check" style="color: #15803d;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="color: #1e3a8a; font-weight: 700;">Project proposal approved</p>
                            <small style="color: #64748b; font-weight: 500;">Quantum Computing Phase 1 • 5 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-0 py-4 border-0">
                    <div class="d-flex gap-3">
                        <div class="p-2 rounded-circle" style="background: linear-gradient(135deg, #fef9c3, #facc15);">
                            <i class="bi bi-exclamation-triangle" style="color: #1e3a8a;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="color: #1e3a8a; font-weight: 700;">Database maintenance scheduled</p>
                            <small style="color: #64748b; font-weight: 500;">System will be offline for 15 mins • Yesterday</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 pt-4">
                <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <p class="mb-0" style="color: #1e3a8a; font-weight: 600;">Storage Used</p>
                    <div class="progress mt-3 mb-2" style="height: 10px; background-color: #bfdbfe; border-radius: 9999px;">
                        <div class="progress-bar rounded-3" style="width: 65%; background: linear-gradient(90deg, #1e3a8a, #facc15);"></div>
                    </div>
                    <small style="color: #1e40af; font-weight: 600;">12.5 GB of 20 GB</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
