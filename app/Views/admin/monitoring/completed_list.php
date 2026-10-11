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
            <p class="text-muted small mb-0">List of all completed research studies.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url('admin/monitoring') ?>?view=board" class="btn btn-light border"><i class="bi bi-kanban me-1"></i> Board</a>
                <a href="<?= base_url('admin/monitoring/completed') ?>" class="btn btn-primary text-white border-primary"><i class="bi bi-check2-circle me-1"></i> Completed</a>
                <a href="<?= base_url('admin/monitoring/incomplete') ?>" class="btn btn-light border"><i class="bi bi-clock-history me-1"></i> Incomplete</a>
                <a href="<?= base_url('admin/monitoring/pre-oral-summary') ?>" class="btn btn-light border"><i class="bi bi-bar-chart me-1"></i> Pre-Oral</a>
            </div>
            <a href="<?= base_url('admin/reports/export?type=completion') ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i> Export CSV</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-success-subtle text-success p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small">Total Completed</p>
                        <h2 class="mb-0 fw-bold"><?= count($completedData) ?></h2>
                    </div>
                    <i class="bi bi-trophy-fill fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4 border-0 shadow-sm">
                <h6 class="mb-2 small">Filter Completed Researches</h6>
                <form method="get" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search researcher name...">
                    </div>
                    <div class="col-md-3">
                        <select name="strand_id" class="form-select form-select-sm">
                            <option value="">All Strands</option>
                            <?php foreach ($strands as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
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

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-0">Researcher / Author</th>
                        <th>Strand</th>
                        <th>Category</th>
                        <th>Progress</th>
                        <th>Date Completed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($completedData) === 0): ?>
                        <tr><td colspan="5" class="text-center text-muted py-5"><i class="bi bi-trophy"></i> No completed researches yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($completedData as $c): ?>
                        <tr>
                            <td class="ps-0 small">
                                <div class="fw-semibold"><?= esc($c['researcher_name'] ?? 'N/A') ?></div>
                                <?php if (!empty($c['student_name'])): ?><div class="text-muted text-xs">Student: <?= esc($c['student_name']) ?></div><?php endif; ?>
                                <?php if (!empty($c['email'])): ?><div class="text-muted text-xs"><?= esc($c['email']) ?></div><?php endif; ?>
                            </td>
                            <td><span class="badge bg-light text-dark rounded-pill"><?= esc($c['strand_name'] ?? 'N/A') ?></span></td>
                            <td><span class="badge bg-light text-dark rounded-pill"><?= esc($c['category_name'] ?? 'N/A') ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width: 100px; height:6px;">
                                        <div class="progress-bar bg-success" style="width:100%"></div>
                                    </div>
                                    <span class="small fw-semibold text-success">100%</span>
                                </div>
                            </td>
                            <td class="small text-muted"><?= date('M d, Y', strtotime($c['last_updated'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
