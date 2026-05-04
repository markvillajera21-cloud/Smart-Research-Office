<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Left Column: Portfolio & Milestones -->
    <div class="col-lg-8">
        <!-- Productivity Overview (Placeholder for Heatmap) -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Productivity Heatmap</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Last 12 Months
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                            <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Heatmap Placeholder -->
                <div class="bg-light rounded p-4 text-center border">
                    <div class="d-flex justify-content-center gap-1 mb-2">
                        <?php for($i=0; $i<15; $i++): ?>
                            <div class="bg-success opacity-<?= rand(25, 100) ?>" style="width: 15px; height: 15px; border-radius: 2px;"></div>
                        <?php endfor; ?>
                    </div>
                    <p class="text-muted small mb-0">System automatically tracks your milestones to visualize your career growth.</p>
                </div>
            </div>
        </div>

        <!-- Research History Timeline -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Research History</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($milestones)): ?>
                        <?php foreach ($milestones as $milestone): ?>
                            <div class="list-group-item p-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex gap-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2 h-100">
                                            <i class="bi <?= match($milestone['category']) {
                                                'PROJECT' => 'bi-journal-check',
                                                'GRANT'   => 'bi-cash-coin',
                                                'DATA'    => 'bi-database-up',
                                                default   => 'bi-star'
                                            } ?> fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?= $milestone['event_name'] ?></h6>
                                            <p class="text-muted small mb-1"><?= $milestone['description'] ?></p>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-light text-dark border small"><?= $milestone['category'] ?></span>
                                                <?php 
                                                    $metadata = json_decode($milestone['metadata'], true);
                                                    if (isset($metadata['school_year'])): 
                                                ?>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 small">
                                                        S.Y. <?= $metadata['school_year'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <span class="text-muted small"><i class="bi bi-clock me-1"></i> <?= date('M d, Y', strtotime($milestone['achieved_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-primary fw-bold small">+<?= $milestone['impact_score'] ?> Impact</div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-5 text-center">
                            <i class="bi bi-rocket-takeoff text-muted fs-1 mb-3 d-block"></i>
                            <p class="text-muted">No milestones recorded yet. Start contributing to build your portfolio!</p>
                            <button class="btn btn-primary btn-sm mt-2">New Contribution</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Profile & Stats -->
    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card mb-4 bg-primary text-white border-0">
            <div class="card-body">
                <h6 class="text-white-50 small text-uppercase fw-bold mb-3">Total Impact Score</h6>
                <div class="d-flex align-items-end gap-2">
                    <h2 class="mb-0 fw-bold"><?= array_sum(array_column($milestones, 'impact_score')) ?></h2>
                    <span class="text-white-50 mb-1">points</span>
                </div>
                <hr class="bg-white-50">
                <div class="row g-0">
                    <div class="col-6">
                        <div class="small text-white-50">Contributions</div>
                        <div class="fw-bold"><?= count($milestones) ?></div>
                    </div>
                    <div class="col-6">
                        <div class="small text-white-50">Rank</div>
                        <div class="fw-bold">Researcher</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Export -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Professional Portfolio</h6>
                <p class="text-muted small">Generate a verified PDF of your research achievements and milestones.</p>
                <button class="btn btn-outline-primary w-100">
                    <i class="bi bi-file-earmark-pdf me-2"></i> Export Portfolio
                </button>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person text-primary fs-1"></i>
                    </div>
                    <h5 class="mb-0 fw-bold"><?= session()->get('username') ?></h5>
                    <p class="text-muted small"><?= session()->get('email') ?></p>
                </div>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('user/profile') ?>" class="btn btn-light btn-sm text-start"><i class="bi bi-pencil-square me-2"></i> Edit Profile</a>
                    <a href="<?= base_url('user/settings') ?>" class="btn btn-light btn-sm text-start"><i class="bi bi-shield-lock me-2"></i> Account Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
