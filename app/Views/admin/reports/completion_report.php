<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Overall research completion performance report with aging analysis.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
            <a href="<?= base_url('admin/reports/export?type=completion') ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i> Export CSV</a>
            <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-5 text-center bg-primary-subtle text-primary">
                <p class="mb-2 small">Overall Completion Rate</p>
                <h1 class="mb-0 fw-bold display-5"><?= $completionRate ?>%</h1>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-primary" style="width:<?= $completionRate ?>%"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-5 text-center bg-success-subtle text-success">
                <p class="mb-2 small">Completed</p>
                <h1 class="mb-0 fw-bold display-5"><?= count($completed) ?></h1>
                <p class="text-xs mt-3 mb-0">of total <?= $totalResearch ?> researches</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-5 text-center bg-warning-subtle text-warning">
                <p class="mb-2 small">Incomplete / Ongoing</p>
                <h1 class="mb-0 fw-bold display-5"><?= count($incomplete) ?></h1>
                <p class="text-xs mt-3 mb-0">of total <?= $totalResearch ?> researches</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm p-5 text-center bg-danger-subtle text-danger">
                <p class="mb-2 small">Stuck (> 90 days idle)</p>
                <h1 class="mb-0 fw-bold display-5"><?= count($stuck90) ?></h1>
                <p class="text-xs mt-3 mb-0">>60d: <?= count($stuck60) ?> | >30d: <?= count($stuck30) ?></p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h5 class="mb-3"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Completion Rate by Strand</h5>
                <?php if (count($strandStats) === 0): ?>
                    <p class="small text-muted">No data yet.</p>
                <?php else: ?>
                <?php foreach ($strandStats as $ss):
                    $pct = $ss['total'] > 0 ? round(($ss['completed'] / $ss['total']) * 100, 1) : 0;
                ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="fw-semibold"><?= esc($ss['strand']) ?></span>
                            <span><?= $ss['completed'] ?> / <?= $ss['total'] ?> (<?= $pct ?>%)</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $pct ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 border-danger border-0 shadow-sm h-100">
                <h5 class="mb-3 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Stuck Research Aging</h5>
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <div class="bg-warning-subtle p-3 rounded text-center text-warning small">
                            <div class="fw-bold display-6 mb-0"><?= count($stuck30) ?></div>
                            <div>>30 days</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-orange-subtle p-3 rounded text-center text-warning-emphasis small" style="background-color: #fff3cd;">
                            <div class="fw-bold display-6 mb-0"><?= count($stuck60) ?></div>
                            <div>>60 days</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-danger-subtle p-3 rounded text-center text-danger small">
                            <div class="fw-bold display-6 mb-0"><?= count($stuck90) ?></div>
                            <div>>90 days</div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle small mb-0">
                        <thead><tr><th>Researcher</th><th>Stage</th><th>Days Idle</th></tr></thead>
                        <tbody>
                            <?php $show = array_slice($stuck90, 0, 5); ?>
                            <?php if (count($show) === 0): ?>
                                <tr><td colspan="3" class="text-center py-3 text-muted">No researches stuck > 90 days.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($show as $s):
                                $days = floor((time() - strtotime($s['last_updated'])) / 86400);
                            ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($s['researcher_name'] ?? 'N/A') ?></td>
                                    <td><?= \App\Models\ResearchProgressModel::$stageLabels[$s['stage']] ?? $s['stage'] ?></td>
                                    <td><span class="badge bg-danger text-white"><?= $days ?>d</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h5 class="mb-3">All Progress Details (<?= $totalResearch ?> researches)</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0 small">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Researcher</th>
                        <th>Strand / Category</th>
                        <th>Current Stage</th>
                        <th>Progress %</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $all = array_merge($completed, $incomplete);
                        $cnt = 0;
                        $labels = \App\Models\ResearchProgressModel::$stageLabels;
                        foreach ($all as $p): $cnt++;
                    ?>
                    <tr>
                        <td><?= $cnt ?></td>
                        <td class="fw-semibold"><?= esc($p['researcher_name'] ?? 'N/A') ?>
                            <?php if (!empty($p['student_name'])): ?><div class="text-xs text-muted"><?= esc($p['student_name']) ?></div><?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark"><?= esc($p['strand_name'] ?? 'N/A') ?></span>
                            <span class="badge bg-light text-dark"><?= esc($p['category_name'] ?? 'N/A') ?></span>
                        </td>
                        <td><span class="badge bg-info-subtle text-info"><?= $labels[$p['stage']] ?? $p['stage'] ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width: 100px; height: 6px;">
                                    <div class="progress-bar" style="width:<?= $p['progress_percentage'] ?>%"></div>
                                </div>
                                <span class="fw-semibold"><?= $p['progress_percentage'] ?>%</span>
                            </div>
                        </td>
                        <td class="text-muted"><?= date('M d, Y', strtotime($p['last_updated'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
