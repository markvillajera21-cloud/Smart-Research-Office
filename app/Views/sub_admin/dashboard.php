<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .subadmin-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: #fff;
        border-radius: 12px;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }
    .subadmin-banner::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .kpi-card {
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
    .quick-action-card {
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        text-decoration: none !important;
        color: inherit;
    }
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        border-color: #3b82f6 !important;
    }
</style>

<div class="container-fluid py-4 space-y-4">
    <!-- Sub-Admin Role Header Banner -->
    <div class="subadmin-banner shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-primary fw-bold px-3 py-1 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">
                        <i class="bi bi-shield-check me-1"></i> Sub-Admin Portal
                    </span>
                    <span class="badge bg-info-subtle text-info-emphasis border border-info border-opacity-25 px-2 py-1 small">
                        Research Office Management
                    </span>
                </div>
                <h3 class="mb-1 fw-bold text-white">Welcome, <?= esc(session()->get('username')) ?>!</h3>
                <p class="mb-0 text-white-50 small">
                    You have administrative access to oversee research scheduling, title approvals, student monitoring, defense evaluations, expert pool, and reports.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('admin/scheduling') ?>" class="btn btn-light btn-sm fw-semibold shadow-sm">
                    <i class="bi bi-calendar-plus me-1 text-primary"></i> Add Schedule
                </a>
                <a href="<?= base_url('admin/research-titles') ?>" class="btn btn-outline-light btn-sm fw-semibold">
                    <i class="bi bi-journal-text me-1"></i> Title Approvals
                </a>
            </div>
        </div>
    </div>

    <!-- Alert / Notice -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card p-3 border-0 shadow-sm bg-primary-subtle text-primary kpi-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Total Researches</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($totalResearch) ?></h2>
                        <span class="small text-muted">Active and archived studies</span>
                    </div>
                    <i class="bi bi-journal-bookmark-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-3 border-0 shadow-sm bg-warning-subtle text-warning-emphasis kpi-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Pending Titles</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($pendingTitles) ?></h2>
                        <a href="<?= base_url('admin/research-titles') ?>" class="small text-warning-emphasis text-decoration-none fw-semibold">
                            Review submissions &rarr;
                        </a>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-3 border-0 shadow-sm bg-success-subtle text-success kpi-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Approved & Completed</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($approvedTitles + $completedTitles) ?></h2>
                        <span class="small text-muted"><?= $approvedTitles ?> Approved &bull; <?= $completedTitles ?> Completed</span>
                    </div>
                    <i class="bi bi-check-circle-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-3 border-0 shadow-sm bg-info-subtle text-info-emphasis kpi-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Expert Pool</p>
                        <h2 class="mb-0 fw-bold"><?= number_format($totalExperts) ?></h2>
                        <a href="<?= base_url('admin/experts') ?>" class="small text-info-emphasis text-decoration-none fw-semibold">
                            View registered experts &rarr;
                        </a>
                    </div>
                    <i class="bi bi-person-lines-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Hub -->
    <div class="card p-4 border-0 shadow-sm mb-4">
        <h5 class="mb-3 fw-bold text-dark"><i class="bi bi-grid-fill me-2 text-primary"></i>Authorized Research Office Modules</h5>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/activities') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-calendar-check-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Calendar & Schedule</h6>
                            <p class="text-muted small mb-0">Defense schedules & calendar of activities</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/research-titles') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning-emphasis rounded-3">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Research Titles</h6>
                            <p class="text-muted small mb-0">Approve, reject & duplicate title checking</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/monitoring') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                            <i class="bi bi-speedometer2 fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Progress Monitoring</h6>
                            <p class="text-muted small mb-0">Track research milestones and completion</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/evaluations') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                            <i class="bi bi-clipboard-check-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Evaluations & Remarks</h6>
                            <p class="text-muted small mb-0">Post-defense panel remarks & compliance</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/experts') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                            <i class="bi bi-person-lines-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Expert Pool</h6>
                            <p class="text-muted small mb-0">Advisers, grammarians & statisticians</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/researchers') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-3">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Researchers Directory</h6>
                            <p class="text-muted small mb-0">Manage researcher records & status</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/reports/researchers') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-file-earmark-person fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Researcher Reports</h6>
                            <p class="text-muted small mb-0">Generate reports by course & academic year</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="<?= base_url('admin/audit-logs') ?>" class="card p-3 border shadow-sm quick-action-card h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-dark bg-opacity-10 text-dark rounded-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Activity Audit Trail</h6>
                            <p class="text-muted small mb-0">View system action history and audit logs</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Department & Personnel Overview & Recent Logs -->
    <div class="row g-4 mb-4">
        <!-- Personnel Metrics -->
        <div class="col-lg-5">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-person-badge me-2 text-primary"></i>Personnel & Department Stats</h5>
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span><i class="bi bi-mortarboard me-2 text-primary"></i>College Department Researches</span>
                        <span class="badge bg-primary rounded-pill"><?= $totalCollege ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span><i class="bi bi-building me-2 text-info"></i>High School Department Researches</span>
                        <span class="badge bg-info rounded-pill"><?= $totalHighSchool ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span><i class="bi bi-person-workspace me-2 text-success"></i>Registered Research Advisers</span>
                        <span class="badge bg-success rounded-pill"><?= $totalAdvisers ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span><i class="bi bi-translate me-2 text-warning"></i>Registered Grammarians</span>
                        <span class="badge bg-warning rounded-pill text-dark"><?= $totalGrammarians ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span><i class="bi bi-bar-chart me-2 text-secondary"></i>Registered Statisticians</span>
                        <span class="badge bg-secondary rounded-pill"><?= $totalStatisticians ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span><i class="bi bi-person-video3 me-2 text-dark"></i>Research Teachers</span>
                        <span class="badge bg-dark rounded-pill"><?= $totalResearchTeachers ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Audit Trail Logs -->
        <div class="col-lg-7">
            <div class="card p-4 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Recent System Activity</h5>
                    <a href="<?= base_url('admin/audit-logs') ?>" class="btn btn-sm btn-outline-primary">View Full Logs</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 small">
                        <thead class="table-light text-uppercase text-muted">
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentLogs)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No recent activities recorded.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentLogs as $log): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= esc($log['action'] ?? 'SYSTEM_EVENT') ?>
                                            </span>
                                        </td>
                                        <td><?= esc($log['username'] ?? 'System') ?></td>
                                        <td class="text-muted"><?= !empty($log['created_at']) ? date('M d, H:i', strtotime($log['created_at'])) : 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
