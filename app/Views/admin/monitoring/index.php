<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Track research progress, completed/incomplete researches, and defense status summary.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url('admin/monitoring') ?>?view=board" class="btn btn-light border <?= $view === 'board' ? 'btn-primary text-white border-primary' : '' ?>"><i class="bi bi-kanban me-1"></i> Board</a>
                <a href="<?= base_url('admin/monitoring/completed') ?>" class="btn btn-light border <?= $view === 'completed' ? 'btn-primary text-white border-primary' : '' ?>"><i class="bi bi-check2-circle me-1"></i> Completed</a>
                <a href="<?= base_url('admin/monitoring/incomplete') ?>" class="btn btn-light border <?= $view === 'incomplete' ? 'btn-primary text-white border-primary' : '' ?>"><i class="bi bi-clock-history me-1"></i> Incomplete</a>
                <a href="<?= base_url('admin/monitoring/pre-oral-summary') ?>" class="btn btn-light border <?= $view === 'pre_oral' ? 'btn-primary text-white border-primary' : '' ?>"><i class="bi bi-bar-chart me-1"></i> Pre-Oral</a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Completion Rate</p>
                            <h3 class="mb-0 text-primary"><?= $completionRate ?>%</h3>
                        </div>
                        <div class="bg-primary-subtle text-primary p-2 rounded-2"><i class="bi bi-graph-up fs-4"></i></div>
                    </div>
                    <div class="progress mt-3" style="height:6px;">
                        <div class="progress-bar bg-primary" style="width:<?= $completionRate ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Total Researches</p>
                            <h3 class="mb-0 text-secondary"><?= $totalCount ?></h3>
                        </div>
                        <div class="bg-secondary-subtle text-secondary p-2 rounded-2"><i class="bi bi-journal-text fs-4"></i></div>
                    </div>
                    <div class="mt-2 small d-flex justify-content-between">
                        <span class="text-success"><i class="bi bi-check2 me-1"></i>Completed: <?= $completedCount ?></span>
                        <span class="text-warning">Active: <?= $incompleteCount ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Upcoming Pre-Orals (30d)</p>
                            <h3 class="mb-0 text-warning"><?= $upcomingPreOrals ?></h3>
                        </div>
                        <div class="bg-warning-subtle text-warning p-2 rounded-2"><i class="bi bi-calendar-event fs-4"></i></div>
                    </div>
                    <div class="mt-2 small d-flex justify-content-between">
                        <span class="badge bg-info-subtle text-info">Pre-Oral: <?= $preOralSummary['total_scheduled'] ?></span>
                        <span class="badge bg-success-subtle text-success">Done: <?= $preOralSummary['total_completed'] ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Stuck (>60 days)</p>
                            <h3 class="mb-0 text-danger"><?= $stuckCount ?></h3>
                        </div>
                        <div class="bg-danger-subtle text-danger p-2 rounded-2"><i class="bi bi-exclamation-triangle fs-4"></i></div>
                    </div>
                    <div class="mt-2 small d-flex justify-content-between">
                        <span class="text-success">Passed: <?= $evalStats['passed'] ?></span>
                        <span class="text-danger">Failed: <?= $evalStats['failed'] ?></span>
                        <span class="text-info">4Comp: <?= $evalStats['for_compliance'] ?></span>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-kanban text-primary me-2"></i> Research Progress Tracking Board</h5>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStageModal"><i class="bi bi-pencil-square me-1"></i> Update Stage</button>
        </div>
        <div class="row g-3">
            <?php
                $stages = array_keys($stageLabels);
                $colSize = 12 / min(4, count($stages));
                $byStage = [];
                foreach ($stages as $st) $byStage[$st] = [];
                foreach ($progressData as $p) {
                    $byStage[$p['stage']][] = $p;
                }
            ?>
            <?php foreach ($byStage as $stageKey => $items): ?>
                <?php
                $percentFor = 100 / 4;
                $badgeColors = [
                    'title_proposal' => 'border-warning',
                    'title_approved' => 'border-info',
                    'pre_oral_scheduled' => 'border-primary',
                    'pre_oral_passed' => 'border-success',
                    'final_defense_scheduled' => 'border-dark',
                    'final_defense_passed' => 'border-success',
                    'completed' => 'border-success',
                ];
                $bgColors = [
                    'title_proposal' => 'bg-warning-subtle',
                    'title_approved' => 'bg-info-subtle',
                    'pre_oral_scheduled' => 'bg-primary-subtle',
                    'pre_oral_passed' => 'bg-success-subtle',
                    'final_defense_scheduled' => 'bg-dark-subtle',
                    'final_defense_passed' => 'bg-success-subtle',
                    'completed' => 'bg-success-subtle',
                ];
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 border-2 border-top-4 <?= $badgeColors[$stageKey] ?? 'border-secondary' ?> rounded-3 shadow-sm">
                        <div class="card-header border-0 <?= $bgColors[$stageKey] ?> pb-1 pt-3">
                            <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold small text-dark"><?= $stageLabels[$stageKey] ?></span>
                            <span class="badge bg-white text-dark rounded-pill"><?= count($items) ?></span>
                        </div>
                        </div>
                        <div class="card-body p-2 overflow-auto" style="max-height:450px;">
                            <?php if (count($items) === 0): ?>
                                <div class="text-center text-muted small py-3"><i class="bi bi-inbox"></i><br>No items</div>
                            <?php endif; ?>
                            <?php foreach ($items as $item): ?>
                                <div class="card mb-2 border-0 bg-white shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="small fw-semibold text-truncate" title="<?= esc($item['researcher_name'] ?? 'N/A') ?>">
                                            <?= esc($item['researcher_name'] ?? 'N/A') ?>
                                        </div>
                                        <div class="text-muted small">
                                            <span class="badge bg-light text-dark"><?= esc($item['strand_name'] ?? 'N/A') ?></span>
                                            <span class="badge bg-light text-dark"><?= esc($item['category_name'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <div class="progress" style="width: 70%; height: 5px;">
                                                <div class="progress-bar" style="width:<?= $item['progress_percentage'] ?>%"></div>
                                            </div>
                                            <span class="small text-muted"><?= $item['progress_percentage'] ?>%</span>
                                        </div>
                                        <div class="mt-1 text-xs text-muted"><i class="bi bi-clock-history"></i> <?= date('M d', strtotime($item['last_updated'])) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($stuckCount > 0): ?>
    <div class="card border-danger p-4">
        <h6 class="mb-3 text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Researches Stuck (> 60 days idle)</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Researcher</th><th>Current Stage</th><th>Last Update</th><th>Idle Days</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($stuckData as $s):
                        $days = floor((time() - strtotime($s['last_updated'])) / 86400);
                    ?>
                    <tr>
                        <td class="small fw-semibold"><?= esc($s['researcher_name'] ?? 'N/A') ?>
                            <?php if (!empty($s['student_name'])): ?><div class="text-muted text-xs"><?= esc($s['student_name']) ?></div><?php endif; ?>
                        </td>
                        <td><span class="badge bg-warning-subtle text-warning"><?= $stageLabels[$s['stage']] ?? $s['stage'] ?></span></td>
                        <td class="small text-muted"><?= date('M d, Y', strtotime($s['last_updated'])) ?></td>
                        <td><span class="badge bg-danger text-white"><?= floor($days) ?> days</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Update Stage Modal -->
<div class="modal fade" id="updateStageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" action="<?= base_url('admin/monitoring/update-stage') ?>">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title">Update Research Progress Stage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small mb-1">Select Researcher *</label>
                            <select name="researcher_id" class="form-select form-select-sm" required>
                                <option value="">-- Select Researcher --</option>
                                <?php foreach ($researchers as $r):
                                    $fullname = $r['fullname'] ?? ($r['surname'] ?? '' . ', ' . $r['first_name'] ?? '');
                                ?>
                                    <option value="<?= $r['id'] ?>"><?= esc($fullname) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small mb-1">New Stage *</label>
                            <select name="stage" class="form-select form-select-sm" required>
                                <?php foreach ($stageLabels as $k => $v): ?>
                                    <option value="<?= $k ?>"><?= $v ?> (<?= \App\Models\ResearchProgressModel::$stagePercentages[$k] ?? 0 ?>%)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small mb-1">Stage Notes</label>
                            <textarea name="stage_notes" rows="2" class="form-control" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Update Progress</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
