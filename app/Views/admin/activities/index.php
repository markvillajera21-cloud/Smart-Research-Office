<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
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

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?? 'Calendar of Activities' ?></h4>
            <p class="text-muted small mb-0">Set and manage authorized schedule windows for Title Hearings, Pre-Oral Defenses, and Final Defenses.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/activities/calendar') ?>" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                <i class="bi bi-calendar4-week me-1"></i> Calendar View
            </a>
            <a href="<?= base_url('admin/activities/create') ?>" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Activity Schedule
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card p-4 mb-4">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Activity / Defense Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <?php foreach (\App\Models\CalendarActivityModel::typeLabels() as $val => $lbl): ?>
                        <option value="<?= esc($val) ?>" <?= ($typeFilter ?? '') === $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <?php foreach (\App\Models\CalendarActivityModel::statusLabels() as $val => $lbl): ?>
                        <option value="<?= esc($val) ?>" <?= ($statusFilter ?? '') === $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Search Keyword</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Title, description, school year..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Activities Table Card -->
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-0">Activity Title</th>
                        <th>Type</th>
                        <th>Schedule Window</th>
                        <th>Academic Year</th>
                        <th>Scheduled Defenses</th>
                        <th>Status</th>
                        <th class="text-end pe-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x fs-4 d-block mb-2 text-secondary"></i>
                                No activity schedules found. Click <strong>Add Activity Schedule</strong> to establish approved defense dates.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($activities as $act): 
                        $typeBadge = [
                            'title_hearing' => 'bg-info-subtle text-info-emphasis',
                            'pre_oral'      => 'bg-warning-subtle text-warning-emphasis',
                            'final_defense' => 'bg-success-subtle text-success-emphasis',
                        ];
                        $statusBadge = [
                            'approved'  => 'bg-success-subtle text-success',
                            'pending'   => 'bg-warning-subtle text-warning',
                            'cancelled' => 'bg-danger-subtle text-danger',
                        ];
                    ?>
                        <tr>
                            <td class="ps-0">
                                <div class="fw-semibold text-primary"><?= esc($act['title']) ?></div>
                                <?php if (!empty($act['description'])): ?>
                                    <div class="text-muted text-xs text-truncate" style="max-width: 300px;"><?= esc($act['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $typeBadge[$act['activity_type']] ?? 'bg-secondary' ?>">
                                    <?= esc(\App\Models\CalendarActivityModel::typeLabel($act['activity_type'])) ?>
                                </span>
                            </td>
                            <td class="small">
                                <div class="fw-medium">
                                    <i class="bi bi-calendar-range me-1 text-muted"></i>
                                    <?= date('M d, Y', strtotime($act['start_date'])) ?> &mdash; <?= date('M d, Y', strtotime($act['end_date'])) ?>
                                </div>
                                <?php
                                    $start = new \DateTime($act['start_date']);
                                    $end   = new \DateTime($act['end_date']);
                                    $days  = $start->diff($end)->days + 1;
                                ?>
                                <span class="text-muted text-xs"><?= $days ?> <?= $days === 1 ? 'day' : 'days' ?> window</span>
                            </td>
                            <td class="small text-muted">
                                <?= esc($act['academic_year'] ?? 'N/A') ?>
                            </td>
                            <td>
                                <?php $defCount = $scheduleCountMap[$act['id']] ?? 0; ?>
                                <?php if ($defCount > 0): ?>
                                    <a href="<?= base_url('admin/scheduling?start_date=' . $act['start_date'] . '&end_date=' . $act['end_date'] . '&type=' . $act['activity_type']) ?>" class="badge rounded-pill bg-primary text-white text-decoration-none" title="View plotted defenses">
                                        <i class="bi bi-calendar-check me-1"></i><?= $defCount ?> plotted
                                    </a>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-light text-muted border">None</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $statusBadge[$act['status']] ?? 'bg-secondary' ?>">
                                    <?= esc(\App\Models\CalendarActivityModel::statusLabel($act['status'])) ?>
                                </span>
                            </td>
                            <td class="text-end pe-0">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item py-2" href="<?= base_url('admin/activities/edit/' . $act['id']) ?>">
                                                <i class="bi bi-pencil me-2 text-primary"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 text-danger" href="<?= base_url('admin/activities/delete/' . $act['id']) ?>" onclick="return confirm('Are you sure you want to remove this activity schedule?')">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
