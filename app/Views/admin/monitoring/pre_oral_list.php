<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold"><i class="bi bi-mic-fill text-warning me-2"></i><?= $page_title ?></h4>
            <p class="text-muted small mb-0">
                Research titles scheduled for or undergoing Pre-Oral Defense, updated from existing schedule records.
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <!-- Nav tabs matching existing monitoring pages -->
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url('admin/monitoring') ?>?view=board" class="btn btn-light border">
                    <i class="bi bi-kanban me-1"></i> Board
                </a>
                <a href="<?= base_url('admin/monitoring/completed') ?>" class="btn btn-light border">
                    <i class="bi bi-check2-circle me-1"></i> Completed
                </a>
                <a href="<?= base_url('admin/monitoring/incomplete') ?>" class="btn btn-light border">
                    <i class="bi bi-clock-history me-1"></i> Incomplete
                </a>
                <a href="<?= base_url('admin/monitoring/pre-oral-summary') ?>" class="btn btn-light border">
                    <i class="bi bi-bar-chart me-1"></i> Pre-Oral Summary
                </a>
                <a href="<?= base_url('admin/monitoring/pre-oral-list') ?>" class="btn btn-warning text-dark border-warning">
                    <i class="bi bi-list-ul me-1"></i> Pre-Oral List
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Pre-Oral (All Time)</p>
                    <h2 class="mb-0 text-primary fw-bold"><?= $totalAll ?></h2>
                    <p class="text-xs text-muted mt-1 mb-0">All statuses</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-warning-subtle">
                <div class="card-body">
                    <p class="text-warning-emphasis small mb-1">Scheduled</p>
                    <h2 class="mb-0 text-warning fw-bold"><?= $totalScheduled ?></h2>
                    <p class="text-xs text-muted mt-1 mb-0">Upcoming / Pending</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-success-subtle">
                <div class="card-body">
                    <p class="text-success small mb-1">Completed</p>
                    <h2 class="mb-0 text-success fw-bold"><?= $totalCompleted ?></h2>
                    <p class="text-xs text-muted mt-1 mb-0">Defense conducted</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body">
                    <p class="text-muted small mb-1">Showing</p>
                    <h2 class="mb-0 fw-bold"><?= count($preOralTitles) ?></h2>
                    <p class="text-xs text-muted mt-1 mb-0">
                        <?= !empty($statusFilter) ? ucfirst($statusFilter) : 'All statuses' ?>
                        <?= !empty($search) ? ', filtered' : '' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="get" action="<?= base_url('admin/monitoring/pre-oral-list') ?>" class="row g-2 align-items-end">
                <div class="col-sm-6 col-md-5">
                    <label class="form-label small fw-semibold mb-1">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Researcher name, title..."
                            value="<?= esc($search) ?>"
                        >
                    </div>
                </div>
                <div class="col-sm-4 col-md-3">
                    <label class="form-label small fw-semibold mb-1">Schedule Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="scheduled"   <?= $statusFilter === 'scheduled'   ? 'selected' : '' ?>>Scheduled</option>
                        <option value="completed"   <?= $statusFilter === 'completed'   ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled"   <?= $statusFilter === 'cancelled'   ? 'selected' : '' ?>>Cancelled</option>
                        <option value="rescheduled" <?= $statusFilter === 'rescheduled' ? 'selected' : '' ?>>Rescheduled</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm text-dark">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <?php if (!empty($search) || !empty($statusFilter)): ?>
                        <a href="<?= base_url('admin/monitoring/pre-oral-list') ?>" class="btn btn-light border btn-sm">
                            <i class="bi bi-x me-1"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Pre-Oral Research Titles Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning-subtle border-bottom py-3 d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-warning-emphasis">
                <i class="bi bi-mic-fill me-2"></i> Pre-Oral Defense Research Titles
            </span>
            <span class="badge bg-warning text-dark rounded-pill"><?= count($preOralTitles) ?> record<?= count($preOralTitles) !== 1 ? 's' : '' ?></span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($preOralTitles)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-mic fs-1 d-block mb-3 opacity-25"></i>
                    <p class="mb-0">
                        <?php if (!empty($search) || !empty($statusFilter)): ?>
                            No Pre-Oral schedules matched your filter. <a href="<?= base_url('admin/monitoring/pre-oral-list') ?>">Clear filter</a>
                        <?php else: ?>
                            No Pre-Oral Defense schedules found. Add schedules in
                            <a href="<?= base_url('admin/scheduling') ?>">Defense Scheduling</a>.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Researcher / Author</th>
                                <th>Research Title</th>
                                <th>Strand / Course</th>
                                <th>Pre-Oral Schedule</th>
                                <th>Venue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $statusBadge = [
                                'scheduled'   => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                'completed'   => 'bg-success-subtle text-success border border-success-subtle',
                                'cancelled'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                                'rescheduled' => 'bg-info-subtle text-info border border-info-subtle',
                            ];
                            $statusIcon = [
                                'scheduled'   => 'bi-calendar-event',
                                'completed'   => 'bi-check-circle-fill',
                                'cancelled'   => 'bi-x-circle-fill',
                                'rescheduled' => 'bi-arrow-repeat',
                            ];
                            foreach ($preOralTitles as $i => $row):
                                $author      = $row['researcher_name'] ?: ($row['student_name'] ?: 'N/A');
                                $titleText   = !empty($row['research_title']) ? $row['research_title'] : $row['schedule_title'];
                                $strandCourse = array_filter([
                                    $row['strand_name']  ?? '',
                                    $row['course_name']  ?? ($row['strand_degree_program'] ?? ''),
                                ]);
                                $sc = !empty($strandCourse) ? implode(' / ', $strandCourse) : '—';
                                $badgeClass = $statusBadge[$row['status']] ?? 'bg-secondary-subtle text-secondary';
                                $iconClass  = $statusIcon[$row['status']] ?? 'bi-dash-circle';
                            ?>
                            <tr>
                                <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                                <td>
                                    <div class="fw-semibold"><?= esc($author) ?></div>
                                    <?php if (!empty($row['researcher_name']) && !empty($row['student_name'])): ?>
                                        <div class="text-xs text-muted">Student: <?= esc($row['student_name']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 280px;">
                                    <div class="fw-semibold text-wrap"><?= esc($titleText) ?></div>
                                    <?php if (!empty($row['research_title']) && $row['research_title'] !== $row['schedule_title']): ?>
                                        <div class="text-xs text-muted mt-1"><i class="bi bi-calendar3 me-1"></i>Defense: <?= esc($row['schedule_title']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($sc !== '—'): ?>
                                        <span class="badge bg-light text-dark border"><?= esc($sc) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= date('M d, Y', strtotime($row['schedule_date'])) ?></div>
                                    <div class="text-muted text-xs">
                                        <i class="bi bi-clock me-1"></i><?= date('h:i A', strtotime($row['schedule_time'])) ?>
                                    </div>
                                    <?php
                                    $today = date('Y-m-d');
                                    $schedDate = $row['schedule_date'];
                                    $diffDays = (int) ((strtotime($schedDate) - strtotime($today)) / 86400);
                                    if ($row['status'] === 'scheduled'):
                                        if ($diffDays === 0):
                                    ?>
                                        <span class="badge bg-danger text-white text-xs mt-1">Today</span>
                                    <?php elseif ($diffDays > 0 && $diffDays <= 7): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis text-xs mt-1">in <?= $diffDays ?>d</span>
                                    <?php elseif ($diffDays < 0): ?>
                                        <span class="badge bg-secondary-subtle text-secondary text-xs mt-1"><?= abs($diffDays) ?>d ago</span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?= !empty($row['venue']) ? esc($row['venue']) : '—' ?></td>
                                <td>
                                    <span class="badge rounded-pill <?= $badgeClass ?>">
                                        <i class="bi <?= $iconClass ?> me-1"></i><?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($preOralTitles)): ?>
        <div class="card-footer bg-transparent border-top text-muted text-xs py-2 px-3">
            Showing <?= count($preOralTitles) ?> Pre-Oral Defense record<?= count($preOralTitles) !== 1 ? 's' : '' ?>.
            Data sourced from <a href="<?= base_url('admin/scheduling') ?>">Defense Scheduling</a> and
            <a href="<?= base_url('admin/researchers') ?>">Research Profiles</a>.
        </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
