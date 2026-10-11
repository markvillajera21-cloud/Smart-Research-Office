<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
@media print {
    .no-print, nav, .sidebar, .header, .btn, .card-header-actions, .filter-box {
        display: none !important;
    }
    body {
        background-color: #fff !important;
        color: #000 !important;
        font-size: 12pt;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    .table th, .table td {
        border: 1px solid #333 !important;
        padding: 6px 8px !important;
    }
    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 20px;
    }
}
.print-header {
    display: none;
}
</style>

<div class="space-y-4">
    <!-- Action Bar & Title -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 no-print">
        <div>
            <h4 class="mb-1 fw-bold"><i class="bi bi-file-earmark-person me-2 text-primary"></i><?= esc($page_title) ?></h4>
            <p class="text-muted small mb-0">Official summary report of registered researchers, academic programs, titles, and project statuses.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-light border btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Reports Center
            </a>
            <a href="<?= base_url('admin/reports/export?type=researchers&' . http_build_query([
                'strand_id'      => $params['strand_id']      ?? '',
                'school_year_id' => $params['school_year_id'] ?? '',
                'status_text'    => $params['status_text']    ?? '',
                'search'         => $params['search']         ?? '',
            ])) ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-filetype-csv me-1"></i> Export CSV
            </a>
            <button class="btn btn-primary btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Print Report
            </button>
        </div>
    </div>

    <div class="print-header">
        <h3 class="mb-0 fw-bold">KABANKALAN CATHOLIC COLLEGE</h3>
        <h5 class="mb-1 text-uppercase">Research &amp; Planning Office</h5>
        <h4 class="fw-bold mt-2 mb-1">RESEARCHER &amp; STUDENT DIRECTORY REPORT</h4>
        <p class="small text-muted mb-0">Generated on <?= date('F d, Y h:i A') ?></p>
        <?php
        $filterParts = [];
        if (!empty($selectedCourseName))  $filterParts[] = 'Course: ' . esc($selectedCourseName);
        if (!empty($selectedYearName))    $filterParts[] = 'Academic Year: ' . esc($selectedYearName);
        if (!empty($selectedStatusName))  $filterParts[] = 'Status: ' . esc($selectedStatusName);
        if (!empty($search))              $filterParts[] = 'Keyword: "' . esc($search) . '"';
        ?>
        <?php if (!empty($filterParts)): ?>
            <p class="small fw-semibold mt-1">Filters Applied: <?= implode(' | ', $filterParts) ?></p>
        <?php else: ?>
            <p class="small fw-semibold mt-1">Filters Applied: All Records (No Filter)</p>
        <?php endif; ?>
        <hr class="my-3">
    </div>

    <!-- Filter Control Card -->
    <div class="card p-4 border-0 shadow-sm mb-4 filter-box no-print">
        <form method="get" action="<?= base_url('admin/reports/researchers') ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Filter by Course / Program</label>
                <select name="strand_id" class="form-select form-select-sm">
                    <option value="">All Courses / Programs</option>
                    <?php if (!empty($strands)): ?>
                        <?php foreach ($strands as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($selectedStrand ?? '') == $s['id'] ? 'selected' : '' ?>>
                                <?= esc($s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Filter by Academic Year</label>
                <select name="school_year_id" class="form-select form-select-sm">
                    <option value="">All Academic Years</option>
                    <?php if (!empty($schoolYears)): ?>
                        <?php foreach ($schoolYears as $sy): ?>
                            <option value="<?= $sy['id'] ?>" <?= ($selectedSchoolYear ?? '') == $sy['id'] ? 'selected' : '' ?>>
                                <?= esc($sy['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Status <span class="text-muted fw-normal">(optional)</span></label>
                <select name="status_text" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <?php if (!empty($distinctStatuses)): ?>
                        <?php foreach ($distinctStatuses as $st): ?>
                            <option value="<?= esc($st) ?>" <?= strtolower($selectedStatus ?? '') === strtolower($st) ? 'selected' : '' ?>>
                                <?= esc(ucwords(str_replace('_', ' ', $st))) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Search Keyword <span class="text-muted fw-normal">(optional)</span></label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, Title..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel-fill me-1"></i> Generate Report
                </button>
                <?php if (!empty($selectedStrand) || !empty($selectedSchoolYear) || !empty($selectedStatus) || !empty($search)): ?>
                    <a href="<?= base_url('admin/reports/researchers') ?>" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>


    <!-- Summary Metrics KPI Cards -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-md-3 col-sm-6">
            <div class="card p-3 border-0 shadow-sm bg-primary-subtle text-primary h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Total Researchers</p>
                        <h2 class="mb-0 fw-bold"><?= $totalCount ?></h2>
                    </div>
                    <i class="bi bi-people-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card p-3 border-0 shadow-sm bg-info-subtle text-info-emphasis h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Programs Represented</p>
                        <h2 class="mb-0 fw-bold"><?= count($courseStats) ?></h2>
                    </div>
                    <i class="bi bi-mortarboard-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card p-3 border-0 shadow-sm bg-success-subtle text-success h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Academic Years</p>
                        <h2 class="mb-0 fw-bold"><?= count($yearStats) ?></h2>
                    </div>
                    <i class="bi bi-calendar-check-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card p-3 border-0 shadow-sm bg-warning-subtle text-warning-emphasis h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Status Categories</p>
                        <h2 class="mb-0 fw-bold"><?= count($statusStats) ?></h2>
                    </div>
                    <i class="bi bi-pie-chart-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Report Table -->
    <div class="card p-4 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-table me-2 text-primary"></i>Researcher Records (<?= $totalCount ?>)
            </h5>
            <span class="badge bg-light text-dark border no-print">
                Real-time active database query
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0 small">
                <thead class="table-light text-uppercase text-muted">
                    <tr>
                        <th class="ps-3" style="width: 45px;">#</th>
                        <th>Researcher Name</th>
                        <th>Course / Program</th>
                        <th>Academic Year</th>
                        <th>Research Title</th>
                        <th>Research Status</th>
                        <th class="text-end pe-3 no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($researchers)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-secondary opacity-50"></i>
                                No researcher records found matching the selected filter criteria.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php 
                    $cnt = 0; 
                    foreach ($researchers as $r): 
                        $cnt++;
                        // Researcher name resolution
                        $name = !empty($r['fullname']) ? $r['fullname'] : (!empty($r['author']) ? $r['author'] : trim(($r['surname'] ?? '') . ', ' . ($r['first_name'] ?? '')));
                        if (empty($name)) $name = $r['username'] ?? 'Unnamed Researcher';

                        // Course resolution
                        $course = !empty($r['course_name']) ? $r['course_name'] : (!empty($r['strand_name']) ? $r['strand_name'] : (!empty($r['strand_degree_program']) ? $r['strand_degree_program'] : '—'));

                        // Academic year resolution
                        $sy = !empty($r['school_year_name']) ? $r['school_year_name'] : (!empty($r['school_year']) ? $r['school_year'] : '—');

                        // Title resolution
                        $title = !empty($r['approved_research_title']) ? $r['approved_research_title'] : '—';

                        // Status resolution & badge
                        $rawStatus = !empty($r['status_name']) ? $r['status_name'] : (!empty($r['status']) ? ucfirst($r['status']) : 'Active');
                        $statusLower = strtolower($rawStatus);
                        $badgeClass = 'bg-secondary-subtle text-secondary';
                        if (str_contains($statusLower, 'complete') || str_contains($statusLower, 'approved')) {
                            $badgeClass = 'bg-success-subtle text-success border border-success border-opacity-25';
                        } elseif (str_contains($statusLower, 'defense') || str_contains($statusLower, 'progress') || str_contains($statusLower, 'active')) {
                            $badgeClass = 'bg-primary-subtle text-primary border border-primary border-opacity-25';
                        } elseif (str_contains($statusLower, 'revision') || str_contains($statusLower, 'pending') || str_contains($statusLower, 'not')) {
                            $badgeClass = 'bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25';
                        } elseif (str_contains($statusLower, 'reject')) {
                            $badgeClass = 'bg-danger-subtle text-danger border border-danger border-opacity-25';
                        }
                    ?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $cnt ?></td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    <?= esc($name) ?>
                                </div>
                                <?php if (!empty($r['username'])): ?>
                                    <div class="text-xs text-muted">
                                        <i class="bi bi-person-badge me-1"></i><?= esc($r['username']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-mortarboard me-1 text-primary"></i><?= esc($course) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    <i class="bi bi-calendar-event me-1"></i><?= esc($sy) ?>
                                </span>
                            </td>
                            <td style="max-width: 320px;">
                                <div class="fw-medium text-dark text-break">
                                    <?= esc($title) ?>
                                </div>
                                <?php if (!empty($r['research_teacher_name'])): ?>
                                    <div class="text-xs text-muted mt-1">
                                        <i class="bi bi-person-workspace me-1"></i>Teacher: <?= esc($r['research_teacher_name']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $badgeClass ?> px-2 py-1">
                                    <?= esc($rawStatus) ?>
                                </span>
                            </td>
                            <td class="text-end pe-3 no-print">
                                <a href="<?= base_url('admin/researchers/edit/' . $r['id']) ?>" class="btn btn-sm btn-light border" title="View Researcher Record">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Print Footer Sign-off -->
        <div class="print-header mt-5 pt-4">
            <div class="row text-center">
                <div class="col-4">
                    <p class="mb-4">Prepared by:</p>
                    <p class="fw-bold mb-0">___________________________</p>
                    <p class="small text-muted">Research Staff / Encoder</p>
                </div>
                <div class="col-4">
                    <p class="mb-4">Verified by:</p>
                    <p class="fw-bold mb-0">___________________________</p>
                    <p class="small text-muted">Research Coordinator</p>
                </div>
                <div class="col-4">
                    <p class="mb-4">Approved by:</p>
                    <p class="fw-bold mb-0">___________________________</p>
                    <p class="small text-muted">Office of the Research Director</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
