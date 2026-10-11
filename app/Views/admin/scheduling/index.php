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
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Schedule title hearings, pre-oral and final defenses, and manage research events.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/activities') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-calendar-check me-1"></i> Calendar of Activities
            </a>
            <a href="<?= base_url('admin/scheduling/calendar') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-calendar4-week me-1"></i> Calendar View
            </a>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                <i class="bi bi-plus-lg me-1"></i> Add Schedule
            </button>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-1">Start Date</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="<?= $startDate ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">End Date</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $endDate ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="title_hearing" <?= $type === 'title_hearing' ? 'selected' : '' ?>>Title Hearing</option>
                    <option value="pre_oral" <?= $type === 'pre_oral' ? 'selected' : '' ?>>Pre-Oral Defense</option>
                    <option value="final_defense" <?= $type === 'final_defense' ? 'selected' : '' ?>>Final Defense</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Search Student/Researcher</label>
                <input type="text" name="student_search" class="form-control form-control-sm" placeholder="Search names..." value="<?= $studentSearch ?? '' ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100" type="submit">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-0">Date & Time</th>
                        <th>Type</th>
                        <th>Title / Event</th>
                        <th>Student / Researcher</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th class="text-end pe-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($schedules) === 0): ?>
                        <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-calendar-x"></i> No schedules found for selected range.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($schedules as $s): 
                        $typeBadge = [
                            'title_hearing' => 'bg-info-subtle text-info',
                            'pre_oral' => 'bg-warning-subtle text-warning',
                            'final_defense' => 'bg-success-subtle text-success',
                        ];
                        $typeLabel = [
                            'title_hearing' => 'Title Hearing',
                            'pre_oral' => 'Pre-Oral',
                            'final_defense' => 'Final Defense',
                        ];
                        $statusBadge = [
                            'scheduled' => 'bg-primary-subtle text-primary',
                            'completed' => 'bg-success-subtle text-success',
                            'cancelled' => 'bg-danger-subtle text-danger',
                            'rescheduled' => 'bg-warning-subtle text-warning',
                        ];
                    ?>
                        <tr>
                            <td class="ps-0 small">
                                <div class="fw-semibold"><?= date('M d, Y', strtotime($s['schedule_date'])) ?></div>
                                <div class="text-muted"><?= date('h:i A', strtotime($s['schedule_time'])) ?></div>
                            </td>
                            <td><span class="badge rounded-pill <?= $typeBadge[$s['schedule_type']] ?>"><?= $typeLabel[$s['schedule_type']] ?></span></td>
                            <td class="fw-semibold small"><?= esc($s['schedule_title']) ?></td>
                            <td class="small">
                                <?php if (!empty($s['student_name'])): ?>
                                    <span class="fw-semibold"><?= esc($s['student_name']) ?></span>
                                    <div class="text-muted text-xs">Instr. <?= esc($s['instructor_name'] ?? 'N/A') ?></div>
                                <?php else: ?>
                                    <span class="fw-semibold"><?= esc($s['researcher_name'] ?? 'N/A') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= esc($s['venue']) ?></td>
                            <td><span class="badge rounded-pill <?= $statusBadge[$s['status']] ?>"><?= ucfirst($s['status']) ?></span></td>
                            <td class="text-end pe-0">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-light border btn-sm view-event-btn" data-id="<?= $s['id'] ?>" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="<?= base_url('admin/scheduling/edit/' . $s['id']) ?>" class="btn btn-light border btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-light border btn-sm change-committee-btn" data-id="<?= $s['id'] ?>" data-panelists="<?= esc($s['panelists'] ?? '', 'attr') ?>" title="Change Guidance Committee">
                                        <i class="bi bi-people"></i>
                                    </button>
                                    <a href="<?= base_url('admin/scheduling/delete/' . $s['id']) ?>" class="btn btn-light border btn-sm text-danger" title="Delete" onclick="return confirm('Delete schedule?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Change Guidance Committee Modal -->
<div class="modal fade" id="changeCommitteeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" id="changeCommitteeForm">
                <div class="modal-header">
                    <h5 class="modal-title">Change Guidance Committee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="committeePanelists" class="form-label small">Committee members</label>
                    <textarea name="panelists" id="committeePanelists" rows="4" class="form-control" required placeholder="Enter committee members, separated by commas"></textarea>
                    <div class="form-text">The existing recommendation and schedule record will remain unchanged.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Save Committee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" action="<?= base_url(isset($schedule['id']) ? 'admin/scheduling/update/' . $schedule['id'] : 'admin/scheduling/store') ?>" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title"><?= isset($schedule['id']) ? 'Edit Research Schedule' : 'Add New Research Schedule' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Schedule / Defense Type <span class="text-danger">*</span></label>
                            <select name="schedule_type" id="scheduleTypeSelect" class="form-select form-select-sm" required>
                                <option value="">-- Select Type --</option>
                                <option value="title_hearing" <?= (isset($schedule['schedule_type']) && $schedule['schedule_type'] === 'title_hearing') ? 'selected' : '' ?>>Title Hearing</option>
                                <option value="pre_oral" <?= (isset($schedule['schedule_type']) && $schedule['schedule_type'] === 'pre_oral') ? 'selected' : '' ?>>Pre-Oral Defense</option>
                                <option value="final_defense" <?= (isset($schedule['schedule_type']) && $schedule['schedule_type'] === 'final_defense') ? 'selected' : '' ?>>Final Defense</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Selection of Researcher <span class="text-danger">*</span></label>
                            <select name="researcher_id" id="schedulingResearcherSelect" class="form-select form-select-sm">
                                <option value="">-- Select Researcher --</option>
                                <?php foreach ($researchers as $r): 
                                    $displayName = $r['fullname'] ?: ($r['surname'] . ', ' . $r['first_name']);
                                    $appTitle = $r['approved_research_title'] ?: '';
                                    $isSelected = (isset($schedule['researcher_id']) && $schedule['researcher_id'] == $r['id']);
                                ?>
                                    <option value="<?= $r['id'] ?>" data-title="<?= esc($appTitle, 'attr') ?>" data-name="<?= esc($displayName, 'attr') ?>" <?= $isSelected ? 'selected' : '' ?>>
                                        <?= esc($displayName) ?><?= $appTitle ? ' &mdash; ' . esc(mb_strimwidth($appTitle, 0, 40, '...')) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Research Title Display -->
                        <div class="col-md-12">
                            <label class="form-label small mb-1 fw-semibold">Research Title</label>
                            <input type="text" id="schedulingResearchTitleDisplay" class="form-control form-control-sm bg-light" placeholder="Auto-populated upon selecting researcher..." readonly>
                        </div>

                        <!-- Defense / Schedule Title -->
                        <div class="col-md-12">
                            <label class="form-label small mb-1 fw-semibold">Defense / Schedule Title <span class="text-danger">*</span></label>
                            <input type="text" name="schedule_title" id="scheduleTitleInput" class="form-control form-control-sm" required placeholder="e.g. Title Hearing: AI-Powered Research Management" value="<?= isset($schedule['schedule_title']) ? esc($schedule['schedule_title']) : '' ?>">
                        </div>

                        <!-- Dynamic Approved Calendar of Activities Notice -->
                        <div class="col-12" id="activityWindowBox" style="display: none;">
                            <div class="alert alert-info py-2 px-3 mb-0 small border-0" id="activityWindowText">
                                <i class="bi bi-info-circle me-1"></i> <span id="activityWindowMsg"></span>
                            </div>
                        </div>

                        <!-- Defense Date (From Calendar of Activities) -->
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">
                                Defense Date <span class="text-danger">*</span>
                                <span class="text-muted fw-normal" style="font-size: 11px;">(from Calendar of Activities)</span>
                            </label>
                            <select name="schedule_date" id="scheduleDateDropdown" class="form-select form-select-sm" required>
                                <option value="">-- Select Approved Calendar Date --</option>
                            </select>
                            <!-- Hidden fallback input to retain existing date on edit if needed -->
                            <input type="hidden" id="currentScheduleDateVal" value="<?= isset($schedule['schedule_date']) ? esc($schedule['schedule_date']) : '' ?>">
                            <div class="form-text">Only dates included in the approved Calendar of Activities appear.</div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small mb-1 fw-semibold">Time <span class="text-danger">*</span></label>
                            <input type="time" name="schedule_time" class="form-control form-control-sm" required value="<?= isset($schedule['schedule_time']) ? $schedule['schedule_time'] : '09:00' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1 fw-semibold">Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue" class="form-control form-control-sm" required placeholder="Room / Zoom Link" value="<?= isset($schedule['venue']) ? esc($schedule['venue']) : 'Conference Room' ?>">
                        </div>

                        <!-- Guidance Committee / Examiners Selection -->
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Adviser</label>
                            <select name="examiner_adviser_id" class="form-select form-select-sm">
                                <?php if (empty($adviserExaminers)): ?>
                                    <option value="">No Registered Advisers Available</option>
                                <?php else: ?>
                                    <option value="">-- Select Registered Adviser --</option>
                                    <?php foreach ($adviserExaminers as $adv): ?>
                                        <option value="<?= $adv['id'] ?>" <?= (isset($selectedAdviserId) && (string)$selectedAdviserId === (string)$adv['id']) ? 'selected' : '' ?>>
                                            <?= esc($adv['name']) ?>
                                        </option>
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
                                        <option value="<?= $crt['id'] ?>" <?= (isset($selectedCriticId) && (string)$selectedCriticId === (string)$crt['id']) ? 'selected' : '' ?>>
                                            <?= esc($crt['name']) ?>
                                        </option>
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
                            <div id="panelMemberChips" class="d-flex flex-wrap gap-1 mb-2 p-2 bg-light rounded border align-items-center" style="min-height: 42px;">
                                <span class="text-muted small empty-chips-placeholder" id="emptyPanelChipsText">No panel members selected yet. Check items below to add.</span>
                            </div>

                            <!-- Search & Checkbox List for Multi-Selection -->
                            <div class="card border shadow-none p-2 bg-white">
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="panelMemberSearchInput" class="form-control border-start-0" placeholder="Search panel member by name..." autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearPanelSearchBtn" title="Clear Search"><i class="bi bi-x"></i></button>
                                </div>

                                <div id="panelMemberListContainer" class="overflow-auto border rounded p-2 bg-light bg-opacity-25" style="max-height: 140px;">
                                    <?php if (empty($panelExaminers)): ?>
                                        <div class="text-muted small text-center py-2">No Registered Panel Members Available</div>
                                    <?php else: ?>
                                        <?php 
                                        $selectedPanelsArr = array_map('intval', (array)($selectedPanelIds ?? []));
                                        foreach ($panelExaminers as $pnl): 
                                            $isPnlChecked = in_array((int)$pnl['id'], $selectedPanelsArr, true);
                                        ?>
                                            <div class="form-check py-1 panel-member-item" data-name="<?= esc(strtolower($pnl['name']), 'attr') ?>">
                                                <input class="form-check-input panel-checkbox" 
                                                       type="checkbox" 
                                                       name="examiner_panel_ids[]" 
                                                       value="<?= $pnl['id'] ?>" 
                                                       id="sched_panel_cb_<?= $pnl['id'] ?>"
                                                       data-id="<?= $pnl['id'] ?>"
                                                       data-name="<?= esc($pnl['name'], 'attr') ?>"
                                                       <?= $isPnlChecked ? 'checked' : '' ?>>
                                                <label class="form-check-label small fw-medium text-dark cursor-pointer user-select-none" for="sched_panel_cb_<?= $pnl['id'] ?>">
                                                    <?= esc($pnl['name']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-text">Check members to add to the panel. Click <i class="bi bi-x-circle text-danger"></i> on a badge to remove.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="scheduled" <?= (isset($schedule['status']) && $schedule['status'] === 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                                <option value="completed" <?= (isset($schedule['status']) && $schedule['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= (isset($schedule['status']) && $schedule['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                <option value="rescheduled" <?= (isset($schedule['status']) && $schedule['status'] === 'rescheduled') ? 'selected' : '' ?>>Rescheduled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1 fw-semibold">Additional Panelists / Notes</label>
                            <input type="text" name="panelists" class="form-control form-control-sm" placeholder="Additional committee notes..." value="<?= isset($schedule['panelists']) ? esc($schedule['panelists']) : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="schedulingSubmitBtn">
                        <i class="bi bi-check2-circle me-1"></i> <?= isset($schedule['id']) ? 'Update Schedule' : 'Save Schedule' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Event Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4" id="viewEventBody">
            </div>
        </div>
    </div>
</div>

<?php if (isset($schedule['id'])): ?>
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal(document.getElementById('addScheduleModal')).show());</script>
<?php endif; ?>

<script>
document.querySelectorAll('.view-event-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        fetch('<?= base_url('admin/scheduling/event/') ?>' + id, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(r => r.json())
            .then(data => {
                if (!data) return;
                const typeLabel = {title_hearing: 'Title Hearing', pre_oral: 'Pre-Oral Defense', final_defense: 'Final Defense'};
                const body = document.getElementById('viewEventBody');
                body.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-0">${data.schedule_title || 'Schedule Details'}</h5>
                            <span class="badge bg-info-subtle text-info mt-1">${typeLabel[data.schedule_type] || data.schedule_type}</span>
                        </div>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="row g-2 small">
                        <div class="col-6"><div class="text-muted">Date</div><div class="fw-semibold">${new Date(data.schedule_date).toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'})}</div></div>
                        <div class="col-6"><div class="text-muted">Time</div><div class="fw-semibold">${data.schedule_time || 'N/A'}</div></div>
                        <div class="col-12"><div class="text-muted">Venue</div><div class="fw-semibold">${data.venue || 'N/A'}</div></div>
                        <div class="col-6"><div class="text-muted">Student</div><div class="fw-semibold">${data.student_name || 'N/A'}</div></div>
                        <div class="col-6"><div class="text-muted">Instructor</div><div class="fw-semibold">${data.instructor_name || 'N/A'}</div></div>
                        <div class="col-12"><div class="text-muted">Panelists</div><div class="fw-semibold">${data.panelists || 'N/A'}</div></div>
                    </div>
                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <a href="<?= base_url('admin/scheduling/edit/') ?>${data.id}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
                        <button class="btn btn-light border btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('viewEventModal')).show();
            });
    });
});

// Calendar of Activities integration map
const allowedDateRangesMap      = <?= json_encode($allowedDateRangesMap ?? []) ?>;
const allowedIndividualDatesMap = <?= json_encode($allowedIndividualDatesMap ?? []) ?>;

const scheduleTypeSelect             = document.getElementById('scheduleTypeSelect');
const schedulingResearcherSelect     = document.getElementById('schedulingResearcherSelect');
const schedulingResearchTitleDisplay = document.getElementById('schedulingResearchTitleDisplay');
const scheduleTitleInput             = document.getElementById('scheduleTitleInput');
const scheduleDateDropdown           = document.getElementById('scheduleDateDropdown');
const currentScheduleDateVal         = document.getElementById('currentScheduleDateVal') ? document.getElementById('currentScheduleDateVal').value : '';
const activityWindowBox              = document.getElementById('activityWindowBox');
const activityWindowText             = document.getElementById('activityWindowText');
const activityWindowMsg              = document.getElementById('activityWindowMsg');
const schedulingSubmitBtn            = document.getElementById('schedulingSubmitBtn');

// Auto-fill research title and suggest schedule title
if (schedulingResearcherSelect) {
    schedulingResearcherSelect.addEventListener('change', function () {
        const selectedOpt = this.options[this.selectedIndex];
        const researchTitle = selectedOpt ? (selectedOpt.dataset.title || '') : '';
        const researcherName = selectedOpt ? (selectedOpt.dataset.name || '') : '';
        schedulingResearchTitleDisplay.value = researchTitle || '(No approved research title recorded)';

        const defType = scheduleTypeSelect ? scheduleTypeSelect.value : '';
        const typeLabels = {
            title_hearing: 'Title Hearing',
            pre_oral: 'Pre-Oral Defense',
            final_defense: 'Final Defense'
        };
        const prefix = typeLabels[defType] || 'Defense';

        if (researchTitle && (!scheduleTitleInput.value || scheduleTitleInput.value.startsWith('Title Hearing:') || scheduleTitleInput.value.startsWith('Pre-Oral') || scheduleTitleInput.value.startsWith('Final Defense'))) {
            scheduleTitleInput.value = `${prefix}: ${researchTitle}`;
        } else if (researcherName && !scheduleTitleInput.value) {
            scheduleTitleInput.value = `${prefix} — ${researcherName}`;
        }
    });
}

function updateActivityWindowNotice() {
    if (!scheduleTypeSelect || !scheduleDateDropdown) return;
    const selectedType = scheduleTypeSelect.value;
    scheduleDateDropdown.innerHTML = '';

    if (!selectedType) {
        scheduleDateDropdown.innerHTML = '<option value="">-- Select Defense Type First --</option>';
        scheduleDateDropdown.disabled = true;
        activityWindowBox.style.display = 'none';
        if (schedulingSubmitBtn) schedulingSubmitBtn.disabled = false;
        return;
    }

    const availableDates  = allowedIndividualDatesMap[selectedType] || [];
    const availableRanges = allowedDateRangesMap[selectedType] || [];

    if (availableDates.length === 0) {
        scheduleDateDropdown.innerHTML = '<option value="">No approved defense date is available.</option>';
        scheduleDateDropdown.disabled = true;
        activityWindowBox.style.display = 'block';
        activityWindowText.className = 'alert alert-danger py-2 px-3 mb-0 small border-0';
        activityWindowMsg.innerHTML = '<i class="bi bi-exclamation-circle-fill me-1"></i><strong>No approved defense date is available.</strong> Please add an approved schedule in the Calendar of Activities first.';
        if (schedulingSubmitBtn) schedulingSubmitBtn.disabled = true;
    } else {
        scheduleDateDropdown.disabled = false;
        scheduleDateDropdown.innerHTML = '<option value="">-- Select Approved Defense Date --</option>';
        activityWindowBox.style.display = 'block';
        activityWindowText.className = 'alert alert-success py-2 px-3 mb-0 small border-0';

        const rangeLabels = availableRanges.map(r => {
            const start = new Date(r.start_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
            const end   = new Date(r.end_date).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
            return `<strong>${start} &mdash; ${end}</strong> (${r.title})`;
        }).join('<br>');

        activityWindowMsg.innerHTML = `<i class="bi bi-calendar-check me-1"></i><strong>Approved Calendar of Activities Schedule:</strong><br>${rangeLabels}`;
        if (schedulingSubmitBtn) schedulingSubmitBtn.disabled = false;

        // Populate dropdown with ONLY approved valid dates
        availableDates.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.date;
            opt.textContent = `${d.formatted} — ${d.activity_title}`;
            if (currentScheduleDateVal && currentScheduleDateVal === d.date) {
                opt.selected = true;
            }
            scheduleDateDropdown.appendChild(opt);
        });

        // Default to first approved date if none currently selected
        if (!scheduleDateDropdown.value && availableDates.length > 0) {
            scheduleDateDropdown.value = availableDates[0].date;
        }
    }

    // Trigger researcher title update
    if (schedulingResearcherSelect && schedulingResearcherSelect.value) {
        schedulingResearcherSelect.dispatchEvent(new Event('change'));
    }
}

if (scheduleTypeSelect) {
    scheduleTypeSelect.addEventListener('change', updateActivityWindowNotice);
    updateActivityWindowNotice();
}

// Interactive Panel Member Chips & Multi-Select Management
function updatePanelMemberChips() {
    const chipsContainer = document.getElementById('panelMemberChips');
    const emptyText = document.getElementById('emptyPanelChipsText');
    const checkboxes = document.querySelectorAll('.panel-checkbox');

    if (!chipsContainer) return;

    // Clear existing chips
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
                updatePanelMemberChips();
            });

            chipsContainer.appendChild(chip);
        }
    });

    if (emptyText) {
        emptyText.style.display = checkedCount === 0 ? 'inline-block' : 'none';
    }
}

// Search filter for panel members in list
const panelMemberSearchInput = document.getElementById('panelMemberSearchInput');
if (panelMemberSearchInput) {
    panelMemberSearchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.panel-member-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(q) ? 'block' : 'none';
        });
    });
}

const clearPanelSearchBtn = document.getElementById('clearPanelSearchBtn');
if (clearPanelSearchBtn) {
    clearPanelSearchBtn.addEventListener('click', function () {
        if (panelMemberSearchInput) {
            panelMemberSearchInput.value = '';
            document.querySelectorAll('.panel-member-item').forEach(item => item.style.display = 'block');
            panelMemberSearchInput.focus();
        }
    });
}

// Bind checkbox changes
document.querySelectorAll('.panel-checkbox').forEach(cb => {
    cb.addEventListener('change', updatePanelMemberChips);
});

// Initial update on page load & modal open
document.addEventListener('DOMContentLoaded', updatePanelMemberChips);
if (addScheduleModalEl) {
    addScheduleModalEl.addEventListener('shown.bs.modal', function () {
        updateActivityWindowNotice();
        updatePanelMemberChips();
        if (schedulingResearcherSelect && schedulingResearcherSelect.value) {
            schedulingResearcherSelect.dispatchEvent(new Event('change'));
        }
    });
}
</script>
<?= $this->endSection() ?>
