<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-1 fw-bold text-xs">
                    <i class="bi bi-calendar2-range me-1"></i> Pre-Oral Defense
                </span>
            </div>
            <h4 class="mb-1 fw-bold text-dark mt-1"><?= esc($page_title ?? 'Pre-Oral Defense Scheduling') ?></h4>
            <p class="text-muted small mb-0">Manage and assign Pre-Oral Defense schedules for approved research titles using approved Calendar of Activities dates.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/activities') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="bi bi-calendar-check me-1"></i> Calendar of Activities
            </a>
            <a href="<?= base_url('admin/monitoring/pre-oral-summary') ?>" class="btn btn-warning text-dark btn-sm shadow-sm">
                <i class="bi bi-clipboard-data me-1"></i> Pre-Oral Summary Report
            </a>
            <a href="<?= base_url('admin/scheduling/calendar') ?>" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="bi bi-calendar4-week me-1"></i> Calendar View
            </a>
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 me-3">
                        <i class="bi bi-journal-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Approved Titles</div>
                        <h4 class="mb-0 fw-bold text-dark"><?= (int)$totalApproved ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-3 bg-warning-subtle text-warning p-3 me-3">
                        <i class="bi bi-calendar-event fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Scheduled Pre-Orals</div>
                        <h4 class="mb-0 fw-bold text-dark"><?= (int)$totalScheduled ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-3 bg-danger-subtle text-danger p-3 me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Pending Scheduling</div>
                        <h4 class="mb-0 fw-bold text-dark"><?= (int)$totalUnscheduled ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-3 bg-success-subtle text-success p-3 me-3">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Completed Defenses</div>
                        <h4 class="mb-0 fw-bold text-dark"><?= (int)$totalCompleted ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved Calendar Window Alert -->
    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar-range-fill text-primary fs-5"></i>
                <div>
                    <span class="fw-bold text-dark small">Pre-Oral Defense Calendar Window:</span>
                    <?php if (!empty($allowedDateRanges)): ?>
                        <?php foreach ($allowedDateRanges as $rng): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1">
                                <?= date('M d, Y', strtotime($rng['start_date'])) ?> – <?= date('M d, Y', strtotime($rng['end_date'])) ?>
                                <?php if (!empty($rng['title'])): ?> (<?= esc($rng['title']) ?>)<?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1">
                            No approved Pre-Oral activity window configured yet.
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?= base_url('admin/activities/create') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Calendar Window
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
        <form method="get" action="<?= base_url('admin/pre-oral-scheduling') ?>" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Strand / Track</label>
                <select name="strand_id" class="form-select form-select-sm">
                    <option value="">All Strands / Tracks</option>
                    <?php foreach ($strands as $str): ?>
                        <option value="<?= $str['id'] ?>" <?= ($strandFilter == $str['id']) ? 'selected' : '' ?>><?= esc($str['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Schedule Status</label>
                <select name="schedule_status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="scheduled" <?= ($statusFilter === 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                    <option value="unscheduled" <?= ($statusFilter === 'unscheduled') ? 'selected' : '' ?>>Unscheduled / Pending</option>
                    <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Search Keyword</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search title, author, student..." value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary btn-sm w-100" type="submit">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="<?= base_url('admin/pre-oral-scheduling') ?>" class="btn btn-light border btn-sm" title="Clear Filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Approved Research Titles Table -->
    <div class="card border-0 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bi bi-journal-text text-primary me-2"></i> Approved Research Titles & Pre-Oral Schedules
            </h6>
            <span class="badge bg-light text-dark border">
                Showing <?= count($approvedTitles) ?> Record<?= count($approvedTitles) !== 1 ? 's' : '' ?>
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 40px;">#</th>
                            <th style="width: 30%;">Research Title</th>
                            <th style="width: 20%;">Researcher / Author</th>
                            <th style="width: 15%;">Course / Strand</th>
                            <th style="width: 20%;">Pre-Oral Schedule</th>
                            <th class="text-end pe-3" style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($approvedTitles)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-25"></i>
                                    <div class="fw-semibold">No approved research titles found.</div>
                                    <p class="text-muted small mb-0">Approve research titles in <a href="<?= base_url('admin/research-titles') ?>">Research Titles</a> to schedule them for Pre-Oral Defense.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($approvedTitles as $idx => $item): 
                                $statusBadge = [
                                    'scheduled'   => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                    'completed'   => 'bg-success-subtle text-success border border-success-subtle',
                                    'cancelled'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    'rescheduled' => 'bg-info-subtle text-info border border-info-subtle',
                                    'unscheduled' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                ];
                                $currentStatus = $item['schedule_status'] ?? 'unscheduled';
                                $badgeClass = $statusBadge[$currentStatus] ?? 'bg-secondary-subtle text-secondary';
                            ?>
                                <tr>
                                    <td class="ps-3 text-muted fw-semibold"><?= $idx + 1 ?></td>
                                    <td>
                                        <div class="fw-bold text-dark text-wrap" style="line-height: 1.4;">
                                            <?= esc($item['research_title']) ?>
                                        </div>
                                        <?php if (!empty($item['category_name'])): ?>
                                            <span class="badge bg-light text-muted border text-xs mt-1"><?= esc($item['category_name']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($item['approved_at'])): ?>
                                            <span class="text-muted text-xs ms-1">Approved: <?= date('M d, Y', strtotime($item['approved_at'])) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= esc($item['author_name']) ?></div>
                                        <?php if (!empty($item['student_name']) && $item['student_name'] !== $item['author_name']): ?>
                                            <div class="text-xs text-muted"><i class="bi bi-person me-1"></i><?= esc($item['student_name']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($item['instructor_name'])): ?>
                                            <div class="text-xs text-muted"><i class="bi bi-mortarboard me-1"></i><?= esc($item['instructor_name']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= esc($item['course_strand']) ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['schedule_id']) && !empty($item['schedule_date'])): ?>
                                            <div>
                                                <span class="badge rounded-pill <?= $badgeClass ?> mb-1">
                                                    <i class="bi bi-calendar-check me-1"></i><?= ucfirst($currentStatus) ?>
                                                </span>
                                            </div>
                                            <div class="fw-semibold text-dark">
                                                <i class="bi bi-calendar3 me-1 text-primary"></i> <?= date('M d, Y', strtotime($item['schedule_date'])) ?>
                                            </div>
                                            <div class="text-muted text-xs">
                                                <i class="bi bi-clock me-1"></i> <?= date('h:i A', strtotime($item['schedule_time'])) ?>
                                                <?php if (!empty($item['schedule_venue'])): ?>
                                                    &bull; <i class="bi bi-geo-alt me-1"></i> <?= esc($item['schedule_venue']) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                                <i class="bi bi-clock-history me-1"></i> Not Scheduled
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <?php if (!empty($item['schedule_id'])): ?>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-warning text-dark btn-sm schedule-btn" 
                                                    data-mode="edit"
                                                    data-schedule-id="<?= $item['schedule_id'] ?>"
                                                    data-title-id="<?= $item['title_id'] ?? '' ?>"
                                                    data-researcher-id="<?= $item['researcher_id'] ?? '' ?>"
                                                    data-student-id="<?= $item['student_id'] ?? '' ?>"
                                                    data-instructor-id="<?= $item['instructor_id'] ?? '' ?>"
                                                    data-research-title="<?= esc($item['research_title']) ?>"
                                                    data-author="<?= esc($item['author_name']) ?>"
                                                    data-schedule-title="<?= esc($item['schedule_title'] ?? 'Pre-Oral Defense: ' . $item['research_title']) ?>"
                                                    data-date="<?= $item['schedule_date'] ?>"
                                                    data-time="<?= $item['schedule_time'] ?>"
                                                    data-venue="<?= esc($item['schedule_venue'] ?? '') ?>"
                                                    data-status="<?= $item['schedule_status'] ?? 'scheduled' ?>"
                                                    data-panelists="<?= esc($item['panelists'] ?? '') ?>"
                                                    title="Reschedule / Edit Defense Schedule">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </button>
                                                <a href="<?= base_url('admin/pre-oral-scheduling/delete/' . $item['schedule_id']) ?>" 
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to remove this Pre-Oral Defense schedule?');"
                                                   title="Cancel / Delete Schedule">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-primary btn-sm schedule-btn shadow-sm"
                                                data-mode="create"
                                                data-schedule-id=""
                                                data-title-id="<?= $item['title_id'] ?? '' ?>"
                                                data-researcher-id="<?= $item['researcher_id'] ?? '' ?>"
                                                data-student-id="<?= $item['student_id'] ?? '' ?>"
                                                data-instructor-id="<?= $item['instructor_id'] ?? '' ?>"
                                                data-research-title="<?= esc($item['research_title']) ?>"
                                                data-author="<?= esc($item['author_name']) ?>"
                                                data-schedule-title="Pre-Oral Defense: <?= esc($item['research_title']) ?>"
                                                data-date=""
                                                data-time="09:00:00"
                                                data-venue="Research Office / Zoom"
                                                data-status="scheduled"
                                                data-panelists=""
                                                title="Schedule Pre-Oral Defense">
                                                <i class="bi bi-calendar-plus me-1"></i> Schedule
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     Schedule Pre-Oral Defense Modal
========================================================= -->
<div class="modal fade" id="preOralScheduleModal" tabindex="-1" aria-labelledby="preOralScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="preOralScheduleForm" method="post" action="<?= base_url('admin/pre-oral-scheduling/store') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="researcher_id" id="modalResearcherId" value="">
                <input type="hidden" name="student_id" id="modalStudentId" value="">
                <input type="hidden" name="instructor_id" id="modalInstructorId" value="">
                
                <div class="modal-header bg-warning text-dark border-bottom-0">
                    <h5 class="modal-title fw-bold" id="preOralScheduleModalLabel">
                        <i class="bi bi-calendar2-plus-fill me-2"></i> <span id="modalHeaderAction">Schedule</span> Pre-Oral Defense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Research Title Display -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Research Title</label>
                            <div class="p-3 bg-light rounded border text-dark fw-bold" id="modalDisplayTitle">
                                —
                            </div>
                        </div>

                        <!-- Researcher / Author Display -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Researcher / Author</label>
                            <div class="p-2 bg-light rounded border text-dark small" id="modalDisplayAuthor">
                                —
                            </div>
                        </div>

                        <!-- Defense / Schedule Title -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Defense Title <span class="text-danger">*</span></label>
                            <input type="text" name="schedule_title" id="modalScheduleTitle" class="form-control" required placeholder="e.g. Pre-Oral Defense: Title...">
                        </div>

                        <!-- Approved Calendar Notice -->
                        <div class="col-12">
                            <div class="alert alert-info py-2 px-3 mb-0 small border-0 d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Calendar of Activities Enforcement:</strong> Defense dates are restricted exclusively to approved schedule windows.
                                </div>
                            </div>
                        </div>

                        <!-- Defense Date (Strictly from Calendar of Activities) -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-semibold text-muted mb-0">
                                    Defense Date <span class="text-danger">*</span>
                                    <span class="text-muted fw-normal" style="font-size: 11px;">(Approved Calendar)</span>
                                </label>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fw-semibold" id="btnOpenAddCalendarSchedule" style="font-size: 11px;">
                                    <i class="bi bi-plus-lg me-1"></i> + Add Calendar Schedule
                                </button>
                            </div>
                            <div class="input-group">
                                <select name="schedule_date" id="modalScheduleDate" class="form-select" required>
                                    <option value="">-- Select Approved Calendar Date --</option>
                                    <?php if (!empty($allowedPreOralDates)): ?>
                                        <?php foreach ($allowedPreOralDates as $dItem): ?>
                                            <option value="<?= esc($dItem['date']) ?>">
                                                <?= esc($dItem['formatted']) ?> <?php if (!empty($dItem['activity_title'])): ?> — <?= esc($dItem['activity_title']) ?><?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No approved Pre-Oral dates found in Calendar of Activities</option>
                                    <?php endif; ?>
                                </select>
                                <button type="button" class="btn btn-outline-secondary" id="btnRefreshAllowedDates" title="Refresh Approved Dates">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <div id="dateRefreshNotice" class="text-success small mt-1 d-none">
                                <i class="bi bi-check-circle-fill me-1"></i> <span id="dateRefreshNoticeText">Calendar date added and selected!</span>
                            </div>
                            <div class="form-text small">Only dates included in the approved Calendar of Activities appear here.</div>
                        </div>

                        <!-- Defense Time -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Time <span class="text-danger">*</span></label>
                            <input type="time" name="schedule_time" id="modalScheduleTime" class="form-control" required value="09:00">
                        </div>

                        <!-- Defense Venue -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue" id="modalVenue" class="form-control" required placeholder="Conference Room / Zoom" value="Research Office">
                        </div>

                        <!-- Guidance Committee / Examiners Selection -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted mb-1">Adviser</label>
                            <select name="examiner_adviser_id" id="modalAdviserId" class="form-select">
                                <option value="">-- Select Registered Adviser --</option>
                                <?php foreach (($adviserExaminers ?? []) as $adv): ?>
                                    <option value="<?= $adv['id'] ?>"><?= esc($adv['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted mb-1">Critic</label>
                            <select name="examiner_critic_id" id="modalCriticId" class="form-select">
                                <option value="">-- Select Registered Critic --</option>
                                <?php foreach (($criticExaminers ?? []) as $crt): ?>
                                    <option value="<?= $crt['id'] ?>"><?= esc($crt['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Panel Members</label>
                            <select name="examiner_panel_ids[]" id="modalPanelIds" class="form-select" multiple size="3">
                                <?php foreach (($panelExaminers ?? []) as $pnl): ?>
                                    <option value="<?= $pnl['id'] ?>"><?= esc($pnl['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text small">Hold Ctrl (Windows) or Command (Mac) to select multiple panel members.</div>
                        </div>

                        <!-- Schedule Status -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted mb-1">Status <span class="text-danger">*</span></label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="scheduled">Scheduled</option>
                                <option value="completed">Completed</option>
                                <option value="rescheduled">Rescheduled</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <!-- Additional Panelists / Remarks -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted mb-1">Additional Committee / Notes</label>
                            <input type="text" name="panelists" id="modalPanelists" class="form-control" placeholder="Additional notes or external panelists...">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-top-0 py-3 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm" id="modalSubmitBtn">
                        <i class="bi bi-check2-circle me-1"></i> Save Pre-Oral Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================
     + Add Calendar Schedule Modal
========================================================= -->
<div class="modal fade" id="addCalendarScheduleModal" tabindex="-1" aria-labelledby="addCalendarScheduleModalLabel" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="quickAddCalendarForm">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white border-bottom-0">
                    <h6 class="modal-title fw-bold" id="addCalendarScheduleModalLabel">
                        <i class="bi bi-calendar-plus me-2"></i> Add Approved Pre-Oral Schedule to Calendar
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="quickCalendarAlert" class="alert d-none py-2 px-3 small border-0 mb-3"></div>
                    <p class="text-muted small mb-3">Add a new approved defense window to the Calendar of Activities. This date will immediately be available in the Pre-Oral defense schedule dropdown.</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Defense Date <span class="text-danger">*</span></label>
                            <input type="date" name="calendar_date" id="quickCalendarDate" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted mb-1">Start Time</label>
                            <input type="time" name="calendar_start_time" id="quickCalendarStartTime" class="form-control" value="09:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted mb-1">End Time</label>
                            <input type="time" name="calendar_end_time" id="quickCalendarEndTime" class="form-control" value="17:00">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Activity Title (Optional)</label>
                            <input type="text" name="calendar_title" id="quickCalendarTitle" class="form-control" placeholder="e.g. Pre-Oral Defense Window">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">Academic Year</label>
                            <input type="text" name="academic_year" id="quickCalendarAY" class="form-control" placeholder="e.g. <?= date('Y') ?>-<?= date('Y') + 1 ?>" value="<?= date('Y') ?>-<?= date('Y') + 1 ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-3 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm" id="btnSaveQuickCalendar">
                        <i class="bi bi-check2-circle me-1"></i> Save & Select Date
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('preOralScheduleModal');
    const scheduleModal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('preOralScheduleForm');

    const addCalendarModalEl = document.getElementById('addCalendarScheduleModal');
    const addCalendarModal = new bootstrap.Modal(addCalendarModalEl);
    const quickAddCalendarForm = document.getElementById('quickAddCalendarForm');
    const btnOpenAddCalendarSchedule = document.getElementById('btnOpenAddCalendarSchedule');
    const btnRefreshAllowedDates = document.getElementById('btnRefreshAllowedDates');
    const quickCalendarAlert = document.getElementById('quickCalendarAlert');
    const btnSaveQuickCalendar = document.getElementById('btnSaveQuickCalendar');
    const dateRefreshNotice = document.getElementById('dateRefreshNotice');
    const dateRefreshNoticeText = document.getElementById('dateRefreshNoticeText');

    const headerAction = document.getElementById('modalHeaderAction');
    const displayTitle = document.getElementById('modalDisplayTitle');
    const displayAuthor = document.getElementById('modalDisplayAuthor');
    const inputResearcherId = document.getElementById('modalResearcherId');
    const inputStudentId = document.getElementById('modalStudentId');
    const inputInstructorId = document.getElementById('modalInstructorId');
    const inputScheduleTitle = document.getElementById('modalScheduleTitle');
    const selectScheduleDate = document.getElementById('modalScheduleDate');
    const inputScheduleTime = document.getElementById('modalScheduleTime');
    const inputVenue = document.getElementById('modalVenue');
    const selectStatus = document.getElementById('modalStatus');
    const inputPanelists = document.getElementById('modalPanelists');
    const selectAdviser = document.getElementById('modalAdviserId');
    const selectCritic = document.getElementById('modalCriticId');
    const selectPanels = document.getElementById('modalPanelIds');

    // Helper to refresh dropdown options with allowed dates list
    function populateAllowedDates(datesList, selectedDate = null) {
        selectScheduleDate.innerHTML = '<option value="">-- Select Approved Calendar Date --</option>';
        if (datesList && datesList.length > 0) {
            datesList.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.date;
                opt.textContent = item.formatted + (item.activity_title ? ' — ' + item.activity_title : '');
                if (selectedDate && item.date === selectedDate) {
                    opt.selected = true;
                }
                selectScheduleDate.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'No approved Pre-Oral dates found in Calendar of Activities';
            opt.disabled = true;
            selectScheduleDate.appendChild(opt);
        }
    }

    // Refresh Allowed Dates AJAX
    function refreshDatesAjax(callback) {
        fetch('<?= base_url('admin/pre-oral-scheduling/allowed-dates') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.allowed_dates) {
                populateAllowedDates(data.allowed_dates, selectScheduleDate.value);
                if (typeof callback === 'function') {
                    callback(data.allowed_dates);
                }
            }
        })
        .catch(err => console.error('Error fetching dates:', err));
    }

    if (btnRefreshAllowedDates) {
        btnRefreshAllowedDates.addEventListener('click', function () {
            btnRefreshAllowedDates.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            refreshDatesAjax(() => {
                btnRefreshAllowedDates.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
                dateRefreshNotice.classList.remove('d-none');
                dateRefreshNoticeText.textContent = 'Approved dates refreshed from Calendar of Activities.';
                setTimeout(() => dateRefreshNotice.classList.add('d-none'), 3000);
            });
        });
    }

    // Open Quick Add Calendar Schedule Modal
    if (btnOpenAddCalendarSchedule) {
        btnOpenAddCalendarSchedule.addEventListener('click', function () {
            quickCalendarAlert.classList.add('d-none');
            addCalendarModal.show();
        });
    }

    // Submit Quick Add Calendar Schedule
    if (quickAddCalendarForm) {
        quickAddCalendarForm.addEventListener('submit', function (e) {
            e.preventDefault();
            btnSaveQuickCalendar.disabled = true;
            btnSaveQuickCalendar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

            const formData = new FormData(quickAddCalendarForm);

            fetch('<?= base_url('admin/pre-oral-scheduling/add-calendar-schedule') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(res => {
                btnSaveQuickCalendar.disabled = false;
                btnSaveQuickCalendar.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Save & Select Date';

                if (res.success) {
                    // Update dropdown and select newly added date
                    populateAllowedDates(res.allowed_dates, res.new_date);
                    selectScheduleDate.value = res.new_date;

                    if (res.start_time) {
                        inputScheduleTime.value = res.start_time.substring(0, 5);
                    }

                    // Show success notice in main modal
                    dateRefreshNotice.classList.remove('d-none');
                    dateRefreshNoticeText.textContent = 'New approved schedule (' + res.new_date + ') added to Calendar and selected!';
                    setTimeout(() => dateRefreshNotice.classList.add('d-none'), 5000);

                    // Close the quick add modal
                    addCalendarModal.hide();
                    quickAddCalendarForm.reset();
                } else {
                    quickCalendarAlert.className = 'alert alert-danger py-2 px-3 small border-0 mb-3';
                    quickCalendarAlert.textContent = res.message || 'Failed to add calendar schedule.';
                    quickCalendarAlert.classList.remove('d-none');
                }
            })
            .catch(err => {
                btnSaveQuickCalendar.disabled = false;
                btnSaveQuickCalendar.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Save & Select Date';
                quickCalendarAlert.className = 'alert alert-danger py-2 px-3 small border-0 mb-3';
                quickCalendarAlert.textContent = 'Network error while saving calendar schedule.';
                quickCalendarAlert.classList.remove('d-none');
            });
        });
    }

    // Schedule / Edit button click handlers
    document.querySelectorAll('.schedule-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const mode = this.dataset.mode;
            const scheduleId = this.dataset.scheduleId;
            const researcherId = this.dataset.researcherId;
            const studentId = this.dataset.studentId;
            const instructorId = this.dataset.instructorId;
            const researchTitle = this.dataset.researchTitle;
            const author = this.dataset.author;
            const scheduleTitle = this.dataset.scheduleTitle;
            const date = this.dataset.date;
            const time = this.dataset.time || '09:00:00';
            const venue = this.dataset.venue || 'Research Office';
            const status = this.dataset.status || 'scheduled';
            const panelists = this.dataset.panelists || '';

            displayTitle.textContent = researchTitle || '—';
            displayAuthor.textContent = author || '—';
            inputResearcherId.value = researcherId || '';
            inputStudentId.value = studentId || '';
            inputInstructorId.value = instructorId || '';
            inputScheduleTitle.value = scheduleTitle || ('Pre-Oral Defense: ' + researchTitle);
            inputScheduleTime.value = time.substring(0, 5);
            inputVenue.value = venue;
            selectStatus.value = status;
            inputPanelists.value = panelists;
            dateRefreshNotice.classList.add('d-none');

            if (mode === 'edit' && scheduleId) {
                headerAction.textContent = 'Update';
                form.action = '<?= base_url('admin/pre-oral-scheduling/update/') ?>' + scheduleId;
            } else {
                headerAction.textContent = 'Schedule';
                form.action = '<?= base_url('admin/pre-oral-scheduling/store') ?>';
            }

            // Set date dropdown value
            if (date) {
                let foundOption = false;
                for (let i = 0; i < selectScheduleDate.options.length; i++) {
                    if (selectScheduleDate.options[i].value === date) {
                        selectScheduleDate.selectedIndex = i;
                        foundOption = true;
                        break;
                    }
                }
                // If date was not found in dropdown, temporarily inject it
                if (!foundOption) {
                    const opt = document.createElement('option');
                    opt.value = date;
                    opt.textContent = date + ' (Current Saved Date)';
                    opt.selected = true;
                    selectScheduleDate.appendChild(opt);
                }
            } else {
                selectScheduleDate.selectedIndex = 0;
            }

            scheduleModal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
