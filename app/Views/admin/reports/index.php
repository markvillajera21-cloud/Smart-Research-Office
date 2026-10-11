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
            <h4 class="mb-1 fw-bold"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Generate research schedule reports, status reports, researcher/student reports, and completion analytics.</p>
        </div>
        <a href="<?= base_url('admin/reports/history') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clock-history me-1"></i> Report History
        </a>
    </div>

    <div class="row g-4 mb-4">
        <!-- 1. Researcher & Student Reports -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="bg-info-subtle text-info-emphasis p-3 rounded-circle mb-3 d-inline-flex">
                            <i class="bi bi-person-lines-fill fs-2"></i>
                        </div>
                        <h5 class="mb-2 fw-semibold">Researcher Reports</h5>
                        <p class="text-muted small mb-3">Comprehensive report of students and researchers with courses, titles, and academic years.</p>
                    </div>
                    <div class="card bg-light border-0 p-3 text-start mt-auto">
                        <form method="get" action="<?= base_url('admin/reports/researchers') ?>" class="row g-2">
                            <div class="col-12">
                                <label class="small fw-semibold mb-1 d-block">Course / Program</label>
                                <select name="course_id" class="form-select form-select-sm">
                                    <option value="">All Courses / Programs</option>
                                    <?php if (!empty($courses)): ?>
                                        <?php foreach ($courses as $crs): ?>
                                            <option value="<?= $crs['id'] ?>"><?= esc($crs['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="small fw-semibold mb-1 d-block">Academic Year</label>
                                <select name="school_year_id" class="form-select form-select-sm">
                                    <option value="">All Academic Years</option>
                                    <?php if (!empty($schoolYears)): ?>
                                        <?php foreach ($schoolYears as $sy): ?>
                                            <option value="<?= $sy['id'] ?>"><?= esc($sy['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-info text-dark btn-sm w-100 fw-semibold">
                                    <i class="bi bi-file-earmark-person me-1"></i> Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Schedule Reports -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle mb-3 d-inline-flex">
                            <i class="bi bi-calendar4-week fs-2"></i>
                        </div>
                        <h5 class="mb-2 fw-semibold">Schedule Reports</h5>
                        <p class="text-muted small mb-3">Defense schedules, title hearings, pre-orals, and panel venues in a date range.</p>
                    </div>
                    <div class="card bg-light border-0 p-3 text-start mt-auto">
                        <form method="get" action="<?= base_url('admin/reports/schedule') ?>" class="row g-2">
                            <div class="col-6">
                                <label class="small fw-semibold mb-1 d-block">Start Date</label>
                                <input type="date" name="start_date" class="form-control form-control-sm" value="<?= date('Y-m-01') ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="small fw-semibold mb-1 d-block">End Date</label>
                                <input type="date" name="end_date" class="form-control form-control-sm" value="<?= date('Y-m-t') ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="small fw-semibold mb-1 d-block">Schedule Type</label>
                                <select name="schedule_type" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    <option value="title_hearing">Title Hearing</option>
                                    <option value="pre_oral">Pre-Oral Defense</option>
                                    <option value="final_defense">Final Defense</option>
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-primary btn-sm w-100 fw-semibold">
                                    <i class="bi bi-file-earmark-text me-1"></i> Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Status Reports -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle mb-3 d-inline-flex">
                            <i class="bi bi-bar-chart-steps fs-2"></i>
                        </div>
                        <h5 class="mb-2 fw-semibold">Status Reports</h5>
                        <p class="text-muted small mb-3">Status distribution of all research titles with approval and revision metrics.</p>
                    </div>
                    <div class="card bg-light border-0 p-3 text-start mt-auto">
                        <form method="get" action="<?= base_url('admin/reports/status') ?>" class="row g-2">
                            <div class="col-12">
                                <label class="small fw-semibold mb-1 d-block">Strand / Program</label>
                                <select name="strand_id" class="form-select form-select-sm">
                                    <option value="">All Strands</option>
                                    <?php foreach ($strands as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="small fw-semibold mb-1 d-block">Category</label>
                                <select name="category_id" class="form-select form-select-sm">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-warning btn-sm text-white w-100 fw-semibold">
                                    <i class="bi bi-file-earmark-text me-1"></i> Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Completion Reports -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="bg-success-subtle text-success p-3 rounded-circle mb-3 d-inline-flex">
                            <i class="bi bi-trophy fs-2"></i>
                        </div>
                        <h5 class="mb-2 fw-semibold">Completion Reports</h5>
                        <p class="text-muted small mb-3">Overall completion rates, stuck researches, and per-strand milestones.</p>
                    </div>
                    <div class="card bg-light border-0 p-3 text-start mt-auto">
                        <form method="get" action="<?= base_url('admin/reports/completion') ?>" class="row g-2">
                            <div class="col-12">
                                <label class="small fw-semibold mb-1 d-block">Academic Year</label>
                                <input type="text" name="school_year" class="form-control form-control-sm" placeholder="e.g. 2025-2026">
                            </div>
                            <div class="col-12 mt-2">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>📊 Rate Calc</span>
                                    <span>⚠️ Stuck Alert</span>
                                </div>
                                <button class="btn btn-success btn-sm w-100 fw-semibold">
                                    <i class="bi bi-file-earmark-text me-1"></i> Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h6 class="mb-3 fw-bold"><i class="bi bi-download me-2 text-primary"></i>Quick Exports (CSV)</h6>
                <a href="<?= base_url('admin/reports/export?type=researchers') ?>" class="btn btn-outline-info text-dark btn-sm w-100 mb-2 text-start">
                    <i class="bi bi-filetype-csv me-2"></i> Export Researcher Data
                </a>
                <a href="<?= base_url('admin/reports/export?type=schedule') ?>" class="btn btn-outline-primary btn-sm w-100 mb-2 text-start">
                    <i class="bi bi-filetype-csv me-2"></i> Export Schedule Data
                </a>
                <a href="<?= base_url('admin/reports/export?type=status') ?>" class="btn btn-outline-warning btn-sm w-100 mb-2 text-start">
                    <i class="bi bi-filetype-csv me-2"></i> Export Title Status
                </a>
                <a href="<?= base_url('admin/reports/export?type=completion') ?>" class="btn btn-outline-success btn-sm w-100 text-start">
                    <i class="bi bi-filetype-csv me-2"></i> Export Progress Data
                </a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h6 class="mb-3 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Recently Generated Reports</h6>
                <?php if (count($history) === 0): ?>
                    <p class="small text-muted mb-0">No reports generated yet.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead>
                            <tr><th>Type</th><th>Parameters</th><th>Generated By</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h):
                                $icons = [
                                    'researchers' => 'bi-person-lines-fill text-info',
                                    'schedule'    => 'bi-calendar4-week text-primary', 
                                    'status'      => 'bi-bar-chart-steps text-warning', 
                                    'completion'  => 'bi-trophy text-success'
                                ];
                                $labels = [
                                    'researchers' => 'Researchers',
                                    'schedule'    => 'Schedule', 
                                    'status'      => 'Status', 
                                    'completion'  => 'Completion'
                                ];
                            ?>
                                <tr>
                                    <td><i class="bi <?= $icons[$h['report_type']] ?? 'bi-file-earmark' ?> me-2"></i><span class="fw-semibold"><?= $labels[$h['report_type']] ?? ucfirst($h['report_type']) ?></span></td>
                                    <td class="text-muted text-xs">
                                        <?php $p = json_decode($h['report_params'], true); if ($p) {
                                            $cleanParams = array_filter($p, fn($v) => !empty($v));
                                            echo !empty($cleanParams) ? implode(', ', array_map(fn($k, $v) => "$k: $v", array_keys($cleanParams), array_values($cleanParams))) : 'All records';
                                        } ?>
                                    </td>
                                    <td class="small"><?= esc($h['generator_name'] ?? 'System') ?></td>
                                    <td class="small text-muted"><?= date('M d, Y h:i A', strtotime($h['generated_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
