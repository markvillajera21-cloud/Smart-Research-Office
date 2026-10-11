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
            <h4 class="mb-1"><?= $page_title ?? 'Calendar of Activities & Defense Schedules' ?></h4>
            <p class="text-muted small mb-0">Monthly schedule of approved activity windows and individual research defense schedules.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form method="get" class="d-flex gap-2 align-items-center">
                <select name="month" class="form-select form-select-sm">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="form-select form-select-sm">
                    <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <?php foreach (\App\Models\CalendarActivityModel::typeLabels() as $val => $lbl): ?>
                        <option value="<?= esc($val) ?>" <?= ($type ?? '') === $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-outline-primary btn-sm" type="submit">Go</button>
            </form>
            <a href="<?= base_url('admin/activities') ?>" class="btn btn-light border btn-sm">
                <i class="bi bi-list-ul me-1"></i> Activities List
            </a>
            <a href="<?= base_url('admin/scheduling') ?>" class="btn btn-light border btn-sm">
                <i class="bi bi-journal-text me-1"></i> Schedules List
            </a>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                <i class="bi bi-calendar-plus me-1"></i> Add Activity Window
            </button>
            <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleDefenseModal">
                <i class="bi bi-plus-lg me-1"></i> Schedule Defense
            </button>
        </div>
    </div>

    <!-- Calendar Card -->
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0 text-primary fw-bold"><?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></h5>
            <div class="d-flex gap-3 small flex-wrap align-items-center">
                <span class="text-muted fw-semibold">Defense Activities:</span>
                <span><span class="badge bg-info-subtle text-info-emphasis rounded-pill">●</span> Title Hearing</span>
                <span><span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">●</span> Pre-Oral Defense</span>
                <span><span class="badge bg-success-subtle text-success-emphasis rounded-pill">●</span> Final Defense</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered border-1" style="table-layout:fixed;">
                <thead>
                    <tr class="table-light">
                        <th class="text-center p-2 small" style="width:14.28%">Sunday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Monday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Tuesday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Wednesday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Thursday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Friday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $firstDay    = date('w', mktime(0, 0, 0, $month, 1, $year));
                    $today       = date('Y-m-d');
                    $dayCounter  = 1;
                    for ($row = 0; $row < 6; $row++): ?>
                        <tr>
                            <?php for ($col = 0; $col < 7; $col++): ?>
                                <td class="p-1 align-top small" style="min-height: 120px; height: 120px;">
                                    <?php
                                    if (($row == 0 && $col < $firstDay) || $dayCounter > $daysInMonth):
                                        echo '&nbsp;';
                                    else:
                                        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $dayCounter);
                                        $isToday = ($dateStr === $today);
                                        $hasActivities = !empty($monthEvents[$dayCounter]);
                                        $hasDefenses   = !empty($monthDefenses[$dayCounter]);
                                        $bg = $isToday ? 'bg-primary-subtle' : '';
                                    ?>
                                        <div class="d-flex justify-content-between align-items-center px-1 mb-1">
                                            <span class="fw-bold small <?= $isToday ? 'text-primary' : '' ?>"><?= $dayCounter ?></span>
                                            <?php if ($hasDefenses): ?>
                                                <span class="badge bg-primary text-white rounded-pill" style="font-size: 9px;" title="Scheduled Defenses">
                                                    <?= count($monthDefenses[$dayCounter]) ?> defense<?= count($monthDefenses[$dayCounter]) > 1 ? 's' : '' ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="<?= $bg ?> rounded p-1" style="max-height: 95px; overflow-y: auto;">
                                            <!-- Approved Activity Window Banners -->
                                            <?php if ($hasActivities): ?>
                                                <?php foreach ($monthEvents[$dayCounter] as $act):
                                                    $actBadges = [
                                                        'title_hearing' => 'border border-info text-info-emphasis bg-info-subtle',
                                                        'pre_oral'      => 'border border-warning text-warning-emphasis bg-warning-subtle',
                                                        'final_defense' => 'border border-success text-success-emphasis bg-success-subtle',
                                                    ];
                                                ?>
                                                    <div class="p-1 mb-1 rounded-1 <?= $actBadges[$act['activity_type']] ?? 'bg-light' ?>" style="font-size: 9.5px; line-height: 1.15;">
                                                        <i class="bi bi-calendar-check me-1"></i><strong><?= esc($act['title']) ?></strong>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <!-- Scheduled Defenses -->
                                            <?php if ($hasDefenses): ?>
                                                <?php foreach ($monthDefenses[$dayCounter] as $def):
                                                    $defBadges = [
                                                        'title_hearing' => 'bg-info text-dark',
                                                        'pre_oral'      => 'bg-warning text-dark',
                                                        'final_defense' => 'bg-success text-white',
                                                    ];
                                                ?>
                                                    <div class="btn p-1 mb-1 w-100 text-start <?= $defBadges[$def['schedule_type']] ?? 'bg-secondary text-white' ?> rounded-1 shadow-sm view-def-btn"
                                                         data-id="<?= $def['id'] ?>"
                                                         data-title="<?= esc($def['schedule_title'], 'attr') ?>"
                                                         data-type="<?= esc(\App\Models\CalendarActivityModel::typeLabel($def['schedule_type']), 'attr') ?>"
                                                         data-schedule-type="<?= esc($def['schedule_type'], 'attr') ?>"
                                                         data-date="<?= date('M d, Y', strtotime($def['schedule_date'])) ?>"
                                                         data-time="<?= date('h:i A', strtotime($def['schedule_time'])) ?>"
                                                         data-researcher="<?= esc($def['researcher_name'] ?? $def['student_name'] ?? 'N/A', 'attr') ?>"
                                                         data-research-title="<?= esc($def['research_title'] ?? $def['researcher_approved_title'] ?? $def['schedule_title'] ?? '', 'attr') ?>"
                                                         data-venue="<?= esc($def['venue'], 'attr') ?>"
                                                         data-panelists="<?= esc($def['panelists'] ?? 'N/A', 'attr') ?>"
                                                         style="font-size: 10px; line-height: 1.2; cursor: pointer; border: none;">
                                                        <div class="fw-bold"><?= date('h:i A', strtotime($def['schedule_time'])) ?></div>
                                                        <div class="text-truncate fw-semibold"><?= esc($def['researcher_name'] ?? $def['student_name'] ?? $def['schedule_title']) ?></div>
                                                        <?php $calResearchTitle = $def['research_title'] ?? $def['researcher_approved_title'] ?? ''; if ($calResearchTitle): ?>
                                                        <div class="text-truncate text-xs opacity-80" style="font-style: italic;"><?= esc(mb_strimwidth($calResearchTitle, 0, 40, '...')) ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php if (!$hasActivities && !$hasDefenses): ?>
                                                <div class="text-muted text-center" style="font-size: 10px; opacity: 0.4;">&mdash;</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php
                                        $dayCounter++;
                                    endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php
                        if ($dayCounter > $daysInMonth) break;
                    endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================
     1. Schedule Defense Modal (Integrated)
=========================================== -->
<div class="modal fade" id="scheduleDefenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" action="<?= base_url('admin/scheduling/store') ?>" id="calScheduleDefenseForm">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="bi bi-calendar2-plus me-2"></i>Schedule Defense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Defense Type -->
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Defense Type <span class="text-danger">*</span></label>
                            <select name="schedule_type" id="calDefenseTypeSelect" class="form-select form-select-sm" required>
                                <option value="">-- Select Defense Type --</option>
                                <option value="title_hearing">Title Hearing</option>
                                <option value="pre_oral">Pre-Oral Defense</option>
                                <option value="final_defense">Final Defense</option>
                            </select>
                        </div>

                        <!-- Researcher Selection -->
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Selection of Researcher <span class="text-danger">*</span></label>
                            <select name="researcher_id" id="calResearcherSelect" class="form-select form-select-sm" required>
                                <option value="">-- Select Researcher --</option>
                                <?php foreach ($researchers as $r): 
                                    $displayName = $r['fullname'] ?: ($r['surname'] . ', ' . $r['first_name']);
                                    $appTitle = $r['approved_research_title'] ?: '';
                                ?>
                                    <option value="<?= $r['id'] ?>" data-title="<?= esc($appTitle, 'attr') ?>" data-name="<?= esc($displayName, 'attr') ?>">
                                        <?= esc($displayName) ?><?= $appTitle ? ' &mdash; ' . esc(mb_strimwidth($appTitle, 0, 45, '...')) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Research Title -->
                        <div class="col-md-12">
                            <label class="form-label small mb-1 fw-semibold">Research Title</label>
                            <input type="text" id="calResearchTitleDisplay" class="form-control form-control-sm bg-light" placeholder="Auto-populated upon selecting researcher..." readonly>
                        </div>

                        <!-- Defense / Schedule Title -->
                        <div class="col-md-12">
                            <label class="form-label small mb-1 fw-semibold">Defense Title <span class="text-danger">*</span></label>
                            <input type="text" name="schedule_title" id="calScheduleTitleInput" class="form-control form-control-sm" required placeholder="e.g. Title Hearing: AI-Assisted Research Management">
                        </div>

                        <!-- Approved Activity Window Banner -->
                        <div class="col-12" id="calActivityNoticeBox" style="display: none;">
                            <div class="alert alert-info py-2 px-3 mb-0 small border-0" id="calActivityNoticeText">
                                <i class="bi bi-info-circle me-1"></i> <span id="calActivityNoticeMsg"></span>
                            </div>
                        </div>

                        <!-- Defense Date (Strictly from Calendar of Activities) -->
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">
                                Defense Date <span class="text-danger">*</span>
                                <span class="text-muted fw-normal" style="font-size: 11px;">(from Calendar of Activities)</span>
                            </label>
                            <select name="schedule_date" id="calDefenseDateDropdown" class="form-select form-select-sm" required>
                                <option value="">-- Select Approved Calendar Date --</option>
                            </select>
                            <div class="form-text" id="calDateHelpText">Only dates included in the approved Calendar of Activities appear.</div>
                        </div>

                        <!-- Time -->
                        <div class="col-md-3">
                            <label class="form-label small mb-1 fw-semibold">Defense Time <span class="text-danger">*</span></label>
                            <input type="time" name="schedule_time" class="form-control form-control-sm" required value="09:00">
                        </div>

                        <!-- Venue -->
                        <div class="col-md-3">
                            <label class="form-label small mb-1 fw-semibold">Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue" class="form-control form-control-sm" required placeholder="Room / Zoom Link" value="Conference Hall">
                        </div>

                        <!-- Registered Examiners Committee Selection -->
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Adviser</label>
                            <select name="examiner_adviser_id" class="form-select form-select-sm">
                                <?php if (empty($adviserExaminers)): ?>
                                    <option value="">No Registered Advisers Available</option>
                                <?php else: ?>
                                    <option value="">-- Select Registered Adviser --</option>
                                    <?php foreach ($adviserExaminers as $adv): ?>
                                        <option value="<?= $adv['id'] ?>"><?= esc($adv['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Critic</label>
                            <select name="examiner_critic_id" class="form-select form-select-sm">
                                <?php if (empty($criticExaminers)): ?>
                                    <option value="">No Registered Critics Available</option>
                                <?php else: ?>
                                    <option value="">-- Select Registered Critic --</option>
                                    <?php foreach ($criticExaminers as $crt): ?>
                                        <option value="<?= $crt['id'] ?>"><?= esc($crt['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Working Multi-Select Panel Members Field -->
                        <div class="col-12">
                            <label class="form-label small mb-1 fw-semibold d-flex justify-content-between align-items-center">
                                <span>Panel Members</span>
                                <span class="text-muted fw-normal" style="font-size: 11px;">Select one or multiple panel members</span>
                            </label>
                            
                            <!-- Selected Panel Members Chips Container -->
                            <div id="calPanelMemberChips" class="d-flex flex-wrap gap-1 mb-2 p-2 bg-light rounded border align-items-center" style="min-height: 42px;">
                                <span class="text-muted small empty-chips-placeholder" id="calEmptyPanelChipsText">No panel members selected yet. Check items below to add.</span>
                            </div>

                            <!-- Search & Checkbox List for Multi-Selection -->
                            <div class="card border shadow-none p-2 bg-white">
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="calPanelMemberSearchInput" class="form-control border-start-0" placeholder="Search panel member by name..." autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="calClearPanelSearchBtn" title="Clear Search"><i class="bi bi-x"></i></button>
                                </div>

                                <div id="calPanelMemberListContainer" class="overflow-auto border rounded p-2 bg-light bg-opacity-25" style="max-height: 140px;">
                                    <?php if (empty($panelExaminers)): ?>
                                        <div class="text-muted small text-center py-2">No Registered Panel Members Available</div>
                                    <?php else: ?>
                                        <?php foreach ($panelExaminers as $pnl): ?>
                                            <div class="form-check py-1 cal-panel-member-item" data-name="<?= esc(strtolower($pnl['name']), 'attr') ?>">
                                                <input class="form-check-input cal-panel-checkbox" 
                                                       type="checkbox" 
                                                       name="examiner_panel_ids[]" 
                                                       value="<?= $pnl['id'] ?>" 
                                                       id="cal_panel_cb_<?= $pnl['id'] ?>"
                                                       data-id="<?= $pnl['id'] ?>"
                                                       data-name="<?= esc($pnl['name'], 'attr') ?>">
                                                <label class="form-check-label small fw-medium text-dark cursor-pointer user-select-none" for="cal_panel_cb_<?= $pnl['id'] ?>">
                                                    <?= esc($pnl['name']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-text">Check members to add to the panel. Click <i class="bi bi-x-circle text-danger"></i> on a badge to remove.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="calSubmitScheduleBtn">
                        <i class="bi bi-check2-circle me-1"></i> Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     2. Add Activity Window Modal
=========================================== -->
<div class="modal fade" id="addActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" action="<?= base_url('admin/activities/store') ?>">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="bi bi-calendar-plus me-2"></i>Add Calendar Activity Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Activity Type <span class="text-danger">*</span></label>
                            <select name="activity_type" class="form-select form-select-sm" required>
                                <option value="">-- Select Type --</option>
                                <?php foreach (\App\Models\CalendarActivityModel::typeLabels() as $val => $lbl): ?>
                                    <option value="<?= esc($val) ?>"><?= esc($lbl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Activity Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm" required placeholder="e.g. Title Hearing Period AY 2025-2026">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control form-control-sm" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control form-control-sm" required value="<?= date('Y-m-d', strtotime('+14 days')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Academic Year</label>
                            <input type="text" name="academic_year" class="form-control form-control-sm" placeholder="e.g. 2025-2026">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="approved" selected>Approved</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small mb-1 fw-semibold">Description</label>
                            <textarea name="description" rows="2" class="form-control form-control-sm" placeholder="Additional guidelines or details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Save Activity Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     3. View Defense Details Modal
=========================================== -->
<div class="modal fade" id="viewDefenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom pb-2">
                <div>
                    <h5 class="mb-0 fw-bold" id="vdTitle">Defense Details</h5>
                    <span class="badge mt-1" id="vdTypeBadge">Title Hearing</span>
                </div>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Research Title Banner -->
                <div class="p-3 mb-3 rounded-2 border" id="vdResearchTitleBox" style="display:none; background: #f8faff;">
                    <div class="text-muted small mb-1"><i class="bi bi-journal-text me-1"></i>Research Title</div>
                    <div class="fw-semibold text-dark" id="vdResearchTitle"></div>
                </div>
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <div class="text-muted fw-semibold small text-uppercase mb-1">Researcher / Author</div>
                        <div class="fw-semibold" id="vdResearcher"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted fw-semibold small text-uppercase mb-1">Activity Name</div>
                        <div class="fw-semibold" id="vdScheduleTitle"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted fw-semibold small text-uppercase mb-1">Date</div>
                        <div class="fw-semibold" id="vdDate"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted fw-semibold small text-uppercase mb-1">Time</div>
                        <div class="fw-semibold" id="vdTime"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted fw-semibold small text-uppercase mb-1">Venue</div>
                        <div class="fw-semibold" id="vdVenue"></div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted fw-semibold small text-uppercase mb-1">Guidance Committee / Panelists</div>
                        <div class="fw-semibold" id="vdPanelists"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top gap-2">
                <a href="#" class="btn btn-outline-primary btn-sm" id="vdEditLink"><i class="bi bi-pencil me-1"></i> Edit Schedule</a>
                <button class="btn btn-light border btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Approved dates map from CalendarActivityModel
const allowedDatesByType = <?= json_encode($allowedIndividualDatesMap ?? []) ?>;
const allowedRangesByType = <?= json_encode($allowedDateRangesMap ?? []) ?>;

const calDefenseTypeSelect     = document.getElementById('calDefenseTypeSelect');
const calResearcherSelect      = document.getElementById('calResearcherSelect');
const calResearchTitleDisplay  = document.getElementById('calResearchTitleDisplay');
const calScheduleTitleInput    = document.getElementById('calScheduleTitleInput');
const calDefenseDateDropdown   = document.getElementById('calDefenseDateDropdown');
const calActivityNoticeBox     = document.getElementById('calActivityNoticeBox');
const calActivityNoticeText    = document.getElementById('calActivityNoticeText');
const calActivityNoticeMsg     = document.getElementById('calActivityNoticeMsg');
const calSubmitScheduleBtn     = document.getElementById('calSubmitScheduleBtn');

// Auto-fill research title and suggest schedule title upon choosing researcher
if (calResearcherSelect) {
    calResearcherSelect.addEventListener('change', function () {
        const selectedOpt = this.options[this.selectedIndex];
        const researchTitle = selectedOpt.dataset.title || '';
        const researcherName = selectedOpt.dataset.name || '';
        calResearchTitleDisplay.value = researchTitle || '(No approved research title recorded)';

        const defType = calDefenseTypeSelect ? calDefenseTypeSelect.value : '';
        const typeLabels = {
            title_hearing: 'Title Hearing',
            pre_oral: 'Pre-Oral Defense',
            final_defense: 'Final Defense'
        };
        const prefix = typeLabels[defType] || 'Defense';

        if (researchTitle) {
            calScheduleTitleInput.value = `${prefix}: ${researchTitle}`;
        } else if (researcherName) {
            calScheduleTitleInput.value = `${prefix} &mdash; ${researcherName}`;
        }
    });
}

// Populate defense date dropdown with ONLY dates from the Calendar of Activities
function updateCalDefenseDates() {
    if (!calDefenseTypeSelect || !calDefenseDateDropdown) return;
    const selectedType = calDefenseTypeSelect.value;
    calDefenseDateDropdown.innerHTML = '';

    if (!selectedType) {
        calDefenseDateDropdown.innerHTML = '<option value="">-- Select Defense Type First --</option>';
        calDefenseDateDropdown.disabled = true;
        calActivityNoticeBox.style.display = 'none';
        calSubmitScheduleBtn.disabled = false;
        return;
    }

    const availableDates  = allowedDatesByType[selectedType] || [];
    const availableRanges = allowedRangesByType[selectedType] || [];

    if (availableDates.length === 0) {
        calDefenseDateDropdown.innerHTML = '<option value="">No approved defense date is available.</option>';
        calDefenseDateDropdown.disabled = true;
        calActivityNoticeBox.style.display = 'block';
        calActivityNoticeText.className = 'alert alert-danger py-2 px-3 mb-0 small border-0';
        calActivityNoticeMsg.innerHTML = '<i class="bi bi-exclamation-circle-fill me-1"></i><strong>No approved defense date is available.</strong> Please add an approved schedule in the Calendar of Activities first.';
        calSubmitScheduleBtn.disabled = true;
    } else {
        calDefenseDateDropdown.disabled = false;
        calDefenseDateDropdown.innerHTML = '<option value="">-- Select Approved Defense Date --</option>';
        calActivityNoticeBox.style.display = 'block';
        calActivityNoticeText.className = 'alert alert-success py-2 px-3 mb-0 small border-0';
        
        const rangeLabels = availableRanges.map(r => {
            const start = new Date(r.start_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
            const end   = new Date(r.end_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
            return `<strong>${start} &mdash; ${end}</strong> (${r.title})`;
        }).join('<br>');

        calActivityNoticeMsg.innerHTML = `<i class="bi bi-calendar-check me-1"></i><strong>Approved Calendar of Activities Schedule:</strong><br>${rangeLabels}`;
        calSubmitScheduleBtn.disabled = false;

        // Populate dropdown strictly with approved valid dates
        availableDates.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.date;
            opt.textContent = `${d.formatted} — ${d.activity_title}`;
            calDefenseDateDropdown.appendChild(opt);
        });

        // Default to first approved date
        if (!calDefenseDateDropdown.value && availableDates.length > 0) {
            calDefenseDateDropdown.value = availableDates[0].date;
        }
    }

    // Trigger title prefix update if researcher is already selected
    if (calResearcherSelect && calResearcherSelect.value) {
        calResearcherSelect.dispatchEvent(new Event('change'));
    }
}

if (calDefenseTypeSelect) {
    calDefenseTypeSelect.addEventListener('change', updateCalDefenseDates);
}

// Defense type badge color mapping
const defTypeBadgeClasses = {
    title_hearing: 'bg-info-subtle text-info-emphasis',
    pre_oral:      'bg-warning-subtle text-warning-emphasis',
    final_defense: 'bg-success-subtle text-success-emphasis',
};

// View defense details modal handlers
document.querySelectorAll('.view-def-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const schedType = btn.dataset.scheduleType || '';
        const researchTitle = btn.dataset.researchTitle || '';

        // Populate schedule title and type badge
        document.getElementById('vdTitle').textContent    = btn.dataset.title || 'Defense Details';
        document.getElementById('vdScheduleTitle').textContent = btn.dataset.title || '—';
        const typeBadge = document.getElementById('vdTypeBadge');
        typeBadge.textContent  = btn.dataset.type || '';
        typeBadge.className    = 'badge mt-1 ' + (defTypeBadgeClasses[schedType] || 'bg-secondary-subtle text-secondary');

        // Research Title Banner
        const rtBox  = document.getElementById('vdResearchTitleBox');
        const rtText = document.getElementById('vdResearchTitle');
        if (researchTitle && researchTitle.trim().length > 0) {
            rtText.textContent   = researchTitle;
            rtBox.style.display  = 'block';
        } else {
            rtBox.style.display  = 'none';
        }

        // Date, time, researcher, venue, panelists
        document.getElementById('vdDate').textContent       = btn.dataset.date || '—';
        document.getElementById('vdTime').textContent       = btn.dataset.time || '—';
        document.getElementById('vdResearcher').textContent = btn.dataset.researcher || '—';
        document.getElementById('vdVenue').textContent      = btn.dataset.venue || '—';
        document.getElementById('vdPanelists').textContent  = btn.dataset.panelists || 'N/A';
        document.getElementById('vdEditLink').href = '<?= base_url('admin/scheduling/edit/') ?>' + btn.dataset.id;

        new bootstrap.Modal(document.getElementById('viewDefenseModal')).show();
    });
// Interactive Panel Member Chips & Multi-Select Management (Calendar Modal)
function updateCalPanelMemberChips() {
    const chipsContainer = document.getElementById('calPanelMemberChips');
    const emptyText = document.getElementById('calEmptyPanelChipsText');
    const checkboxes = document.querySelectorAll('.cal-panel-checkbox');

    if (!chipsContainer) return;

    chipsContainer.querySelectorAll('.panel-chip-badge').forEach(el => el.remove());

    let checkedCount = 0;
    checkboxes.forEach(cb => {
        if (cb.checked) {
            checkedCount++;
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-2 py-1 d-inline-flex align-items-center gap-1 panel-chip-badge shadow-xs';
            chip.style.fontSize = '12px';
            chip.innerHTML = `<i class="bi bi-person-check-fill text-primary"></i> <span>${cb.dataset.name}</span> <button type="button" class="btn-close btn-close-sm ms-1" style="font-size: 8px;" aria-label="Remove"></button>`;

            chip.querySelector('.btn-close').addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                cb.checked = false;
                updateCalPanelMemberChips();
            });

            chipsContainer.appendChild(chip);
        }
    });

    if (emptyText) {
        emptyText.style.display = checkedCount === 0 ? 'inline-block' : 'none';
    }
}

const calPanelMemberSearchInput = document.getElementById('calPanelMemberSearchInput');
if (calPanelMemberSearchInput) {
    calPanelMemberSearchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.cal-panel-member-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(q) ? 'block' : 'none';
        });
    });
}

const calClearPanelSearchBtn = document.getElementById('calClearPanelSearchBtn');
if (calClearPanelSearchBtn) {
    calClearPanelSearchBtn.addEventListener('click', function () {
        if (calPanelMemberSearchInput) {
            calPanelMemberSearchInput.value = '';
            document.querySelectorAll('.cal-panel-member-item').forEach(item => item.style.display = 'block');
            calPanelMemberSearchInput.focus();
        }
    });
}

document.querySelectorAll('.cal-panel-checkbox').forEach(cb => {
    cb.addEventListener('change', updateCalPanelMemberChips);
});

document.addEventListener('DOMContentLoaded', updateCalPanelMemberChips);

const scheduleDefenseModalEl = document.getElementById('scheduleDefenseModal');
if (scheduleDefenseModalEl) {
    scheduleDefenseModalEl.addEventListener('shown.bs.modal', function () {
        updateCalDefenseDates();
        updateCalPanelMemberChips();
    });
}
</script>
<?= $this->endSection() ?>
