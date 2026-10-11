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
            <p class="text-muted small mb-0">Ongoing researches - track and update progress.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url('admin/monitoring') ?>?view=board" class="btn btn-light border"><i class="bi bi-kanban me-1"></i> Board</a>
                <a href="<?= base_url('admin/monitoring/completed') ?>" class="btn btn-light border"><i class="bi bi-check2-circle me-1"></i> Completed</a>
                <a href="<?= base_url('admin/monitoring/incomplete') ?>" class="btn btn-primary text-white border-primary"><i class="bi bi-clock-history me-1"></i> Incomplete</a>
                <a href="<?= base_url('admin/monitoring/pre-oral-summary') ?>" class="btn btn-light border"><i class="bi bi-bar-chart me-1"></i> Pre-Oral</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-warning-subtle text-warning p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small">Total Incomplete</p>
                        <h2 class="mb-0 fw-bold"><?= count($incompleteData) ?></h2>
                    </div>
                    <i class="bi bi-hourglass-split fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger-subtle text-danger p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small">Stuck > 60 days</p>
                        <h2 class="mb-0 fw-bold"><?= count($stuckData) ?></h2>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 border-0 shadow-sm">
                <h6 class="mb-2 small">Filter Incomplete Researches</h6>
                <form method="get" class="row g-2 align-items-end">
                    <div class="col-md-7">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search researcher name...">
                    </div>
                    <div class="col-md-4">
                        <select name="strand_id" class="form-select form-select-sm">
                            <option value="">All Strands</option>
                            <?php foreach ($strands as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-funnel"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (count($stuckData) > 0): ?>
    <div class="card border-danger p-4 mb-4">
        <h6 class="mb-3 text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> STUCK RESEARCHES (> 60 days without update)</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead>
                    <tr><th>Researcher</th><th>Stage</th><th>Progress %</th><th>Last Updated</th><th>Days Idle</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($stuckData as $s):
                        $days = floor((time() - strtotime($s['last_updated'])) / 86400);
                    ?>
                    <tr class="bg-danger-subtle">
                        <td class="fw-semibold ps-0"><?= esc($s['researcher_name'] ?? 'N/A') ?></td>
                        <td><span class="badge bg-warning text-dark"><?= $stageLabels[$s['stage']] ?? $s['stage'] ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="progress" style="width:60px;height:5px;"><div class="progress-bar" style="width:<?= $s['progress_percentage'] ?>%"></div></div>
                                <?= $s['progress_percentage'] ?>%
                            </div>
                        </td>
                        <td class="text-muted"><?= date('M d, Y', strtotime($s['last_updated'])) ?></td>
                        <td><span class="badge bg-danger text-white"><?= $days ?> days</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-0">Researcher</th>
                        <th>Strand</th>
                        <th>Current Stage</th>
                        <th>Progress</th>
                        <th>Last Update</th>
                        <th>Days Since Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($incompleteData) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">All researches completed!</td></tr>
                    <?php endif; ?>
                    <?php foreach ($incompleteData as $i):
                        $days = floor((time() - strtotime($i['last_updated'])) / 86400);
                        $daysBadge = $days > 60 ? 'bg-danger text-white' : ($days > 30 ? 'bg-warning text-dark' : 'bg-light text-muted');
                    ?>
                        <tr>
                            <td class="ps-0 small">
                                <div class="fw-semibold"><?= esc($i['researcher_name'] ?? 'N/A') ?></div>
                                <?php if (!empty($i['student_name'])): ?><div class="text-muted text-xs">Student: <?= esc($i['student_name']) ?></div><?php endif; ?>
                            </td>
                            <td><span class="badge bg-light text-dark rounded-pill"><?= esc($i['strand_name'] ?? 'N/A') ?></span></td>
                            <td><span class="badge bg-info-subtle text-info"><?= $stageLabels[$i['stage']] ?? $i['stage'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width: 100px; height:6px;">
                                        <div class="progress-bar" style="width:<?= $i['progress_percentage'] ?>%"></div>
                                    </div>
                                    <span class="small fw-semibold"><?= $i['progress_percentage'] ?>%</span>
                                </div>
                            </td>
                            <td class="small text-muted"><?= date('M d, Y', strtotime($i['last_updated'])) ?></td>
                            <td><span class="badge rounded-pill <?= $daysBadge ?>"><?= $days ?>d</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
