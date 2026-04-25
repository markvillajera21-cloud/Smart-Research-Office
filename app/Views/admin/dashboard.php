<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-subtle text-primary p-3 rounded-3">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Users</h6>
                    <h4 class="mb-0">12</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success-subtle text-success p-3 rounded-3">
                    <i class="bi bi-journal-check fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Active Projects</h6>
                    <h4 class="mb-0">8</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info-subtle text-info p-3 rounded-3">
                    <i class="bi bi-file-earmark-text fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Pending Reviews</h6>
                    <h4 class="mb-0">5</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning-subtle text-warning p-3 rounded-3">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">System Health</h6>
                    <h4 class="mb-0">98%</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Recent Activities</h5>
                <button class="btn btn-sm btn-outline-secondary">View All</button>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 py-3 border-0 border-bottom">
                    <div class="d-flex gap-3">
                        <div class="bg-light p-2 rounded-circle h-100">
                            <i class="bi bi-person-plus text-primary"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-medium">New researcher registered</p>
                            <small class="text-muted">Dr. Sarah Johnson joined the team • 2 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-0 py-3 border-0 border-bottom">
                    <div class="d-flex gap-3">
                        <div class="bg-light p-2 rounded-circle h-100">
                            <i class="bi bi-file-earmark-check text-success"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-medium">Project proposal approved</p>
                            <small class="text-muted">Quantum Computing Phase 1 • 5 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="list-group-item px-0 py-3 border-0">
                    <div class="d-flex gap-3">
                        <div class="bg-light p-2 rounded-circle h-100">
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-medium">Database maintenance scheduled</p>
                            <small class="text-muted">System will be offline for 15 mins • Yesterday</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card h-100 p-4 bg-primary text-white">
            <h5 class="mb-4">Quick Actions</h5>
            <div class="d-grid gap-3">
                <a href="<?= base_url('admin/users/create') ?>" class="btn btn-light text-primary fw-semibold py-3 text-start px-4">
                    <i class="bi bi-person-plus-fill me-2"></i> Add New Researcher
                </a>
                <button class="btn btn-primary border border-white fw-semibold py-3 text-start px-4">
                    <i class="bi bi-journal-plus me-2"></i> Create Project
                </button>
                <button class="btn btn-primary border border-white fw-semibold py-3 text-start px-4">
                    <i class="bi bi-cloud-arrow-up me-2"></i> Upload Data
                </button>
            </div>
            <div class="mt-auto pt-4">
                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                    <p class="small mb-0 opacity-75">Storage Used</p>
                    <div class="progress mt-2 mb-1" style="height: 6px;">
                        <div class="progress-bar bg-white" style="width: 65%"></div>
                    </div>
                    <small class="opacity-75">12.5 GB of 20 GB</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
