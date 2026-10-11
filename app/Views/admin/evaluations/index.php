<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Header & Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold text-dark"><i class="bi bi-clipboard-check-fill me-2 text-primary"></i><?= esc($page_title) ?></h4>
            <p class="text-muted small mb-0">Select research records, assign Remark Statuses (Passed, Failed, For Compliance), and manage evaluation feedback.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url('admin/evaluations') ?>?view=all" class="btn btn-light border <?= ($view === 'all' || $view === '') ? 'btn-dark text-white border-dark' : '' ?>">
                    <i class="bi bi-list me-1"></i> All Records
                </a>
                <a href="<?= base_url('admin/evaluations') ?>?view=passed" class="btn btn-light border <?= $view === 'passed' ? 'btn-success text-white border-success' : '' ?>">
                    <i class="bi bi-check2-circle me-1"></i> Passed
                </a>
                <a href="<?= base_url('admin/evaluations') ?>?view=failed" class="btn btn-light border <?= $view === 'failed' ? 'btn-danger text-white border-danger' : '' ?>">
                    <i class="bi bi-x-circle me-1"></i> Failed
                </a>
                <a href="<?= base_url('admin/evaluations') ?>?view=for_compliance" class="btn btn-light border <?= $view === 'for_compliance' ? 'btn-info text-white border-info' : '' ?>">
                    <i class="bi bi-exclamation-square me-1"></i> For Compliance
                </a>
                <a href="<?= base_url('admin/evaluations') ?>?view=pending" class="btn btn-light border <?= $view === 'pending' ? 'btn-warning text-dark border-warning' : '' ?>">
                    <i class="bi bi-hourglass-split me-1"></i> Pending
                </a>
            </div>
            <button type="button" class="btn btn-primary btn-sm shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#evalModal"
                    data-mode="new">
                <i class="bi bi-plus-lg me-1"></i> New Evaluation
            </button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('admin/evaluations') ?>?view=passed" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-success-subtle text-success p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small text-uppercase fw-semibold">Passed</p>
                            <h2 class="mb-0 fw-bold"><?= $stats['passed'] ?? 0 ?></h2>
                            <span class="small opacity-75">
                                <?= ($stats['total'] ?? 0) > 0 ? round((($stats['passed'] ?? 0) / $stats['total']) * 100, 1) : 0 ?>% of evaluated
                            </span>
                        </div>
                        <i class="bi bi-check2-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('admin/evaluations') ?>?view=failed" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-danger-subtle text-danger p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small text-uppercase fw-semibold">Failed</p>
                            <h2 class="mb-0 fw-bold"><?= $stats['failed'] ?? 0 ?></h2>
                            <span class="small opacity-75">
                                <?= ($stats['total'] ?? 0) > 0 ? round((($stats['failed'] ?? 0) / $stats['total']) * 100, 1) : 0 ?>% of evaluated
                            </span>
                        </div>
                        <i class="bi bi-x-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('admin/evaluations') ?>?view=for_compliance" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-info-subtle text-info-emphasis p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small text-uppercase fw-semibold">For Compliance</p>
                            <h2 class="mb-0 fw-bold"><?= $stats['for_compliance'] ?? 0 ?></h2>
                            <span class="small opacity-75">Compliance Tracking</span>
                        </div>
                        <i class="bi bi-exclamation-square fs-1 opacity-50"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="<?= base_url('admin/evaluations') ?>?view=all" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-secondary-subtle text-secondary-emphasis p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small text-uppercase fw-semibold">Total Evaluated</p>
                            <h2 class="mb-0 fw-bold"><?= $stats['total'] ?? 0 ?></h2>
                            <span class="small opacity-75">Pending: <?= $stats['pending_eval'] ?? 0 ?> record(s)</span>
                        </div>
                        <i class="bi bi-clipboard-data fs-1 opacity-50"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- MAIN RESEARCH RECORDS & EVALUATIONS TABLE -->
    <div class="card p-4 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold text-dark">
                <?php if ($view === 'passed'): ?>
                    <i class="bi bi-check2-circle text-success me-2"></i>Passed Research Evaluations
                <?php elseif ($view === 'failed'): ?>
                    <i class="bi bi-x-circle text-danger me-2"></i>Failed Research Evaluations
                <?php elseif ($view === 'for_compliance'): ?>
                    <i class="bi bi-exclamation-square text-info me-2"></i>For Compliance Research Records
                <?php elseif ($view === 'pending'): ?>
                    <i class="bi bi-hourglass-split text-warning me-2"></i>Pending Defense Evaluations
                <?php else: ?>
                    <i class="bi bi-list-ul text-primary me-2"></i>All Research Records & Defense Evaluations
                <?php endif; ?>
            </h5>
            <span class="badge bg-light text-secondary border px-2 py-1"><?= count($records) ?> Record(s)</span>
        </div>

        <?php if (empty($records)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-50"></i>
                <p class="mb-0">No research records found for this filter view.</p>
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#evalModal" data-mode="new">
                        <i class="bi bi-plus-lg me-1"></i> Add New Evaluation
                    </button>
                </div>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-muted small">
                    <tr>
                        <th class="ps-0">Research Title</th>
                        <th>Researcher</th>
                        <th>Defense Stage</th>
                        <th>Remark Status</th>
                        <th>Overall Score</th>
                        <th>Evaluated By</th>
                        <th>Date Evaluated</th>
                        <th class="text-end pe-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $typeLabel = ['title_hearing' => 'Title Hearing', 'pre_oral' => 'Pre-Oral Defense', 'final_defense' => 'Final Defense'];
                        $typeBadge = [
                            'title_hearing' => 'bg-info-subtle text-info border border-info border-opacity-25',
                            'pre_oral'      => 'bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25',
                            'final_defense' => 'bg-success-subtle text-success border border-success border-opacity-25',
                        ];
                    ?>
                    <?php foreach ($records as $r): ?>
                        <tr>
                            <td class="ps-0 small">
                                <div class="fw-bold text-dark"><?= esc($r['display_title']) ?></div>
                                <div class="text-muted text-xs">
                                    Schedule Date: <?= !empty($r['schedule_date']) ? date('M d, Y', strtotime($r['schedule_date'])) : 'Not set' ?>
                                    <?= !empty($r['venue']) ? ' &bull; ' . esc($r['venue']) : '' ?>
                                </div>
                            </td>
                            <td class="small fw-semibold text-dark"><?= esc($r['researcher_name']) ?></td>
                            <td>
                                <span class="badge rounded-pill <?= $typeBadge[$r['schedule_type']] ?? 'bg-secondary-subtle text-secondary' ?>">
                                    <?= $typeLabel[$r['schedule_type']] ?? ucwords(str_replace('_', ' ', $r['schedule_type'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($r['remark_status']) && $r['remark_status'] === 'passed'): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success border-opacity-25 px-3 py-1 fw-bold">
                                        <i class="bi bi-check2-circle me-1"></i> Passed
                                    </span>
                                <?php elseif (!empty($r['remark_status']) && $r['remark_status'] === 'failed'): ?>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger border-opacity-25 px-3 py-1 fw-bold">
                                        <i class="bi bi-x-circle me-1"></i> Failed
                                    </span>
                                <?php elseif (!empty($r['remark_status']) && $r['remark_status'] === 'for_compliance'): ?>
                                    <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info border-opacity-25 px-3 py-1 fw-bold">
                                        <i class="bi bi-exclamation-square me-1"></i> For Compliance
                                    </span>
                                    <div class="text-xs mt-1">
                                        <?php if (!empty($r['compliance_submitted']) && $r['compliance_submitted']): ?>
                                            <span class="badge bg-success text-white">Compliance Submitted</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning">
                                                Deadline: <?= !empty($r['compliance_deadline']) ? date('M d, Y', strtotime($r['compliance_deadline'])) : 'Not set' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25 px-3 py-1">
                                        <i class="bi bi-hourglass-split me-1"></i> Pending Evaluation
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="small fw-semibold">
                                <?= !empty($r['overall_score']) ? esc($r['overall_score']) : '<span class="text-muted">—</span>' ?>
                            </td>
                            <td class="small text-muted">
                                <?= esc($r['evaluator_name'] ?? '—') ?>
                            </td>
                            <td class="small text-muted">
                                <?= !empty($r['evaluated_at']) ? date('M d, Y h:i A', strtotime($r['evaluated_at'])) : '—' ?>
                            </td>
                            <td class="text-end pe-0">
                                <div class="d-flex gap-1 justify-content-end">
                                    <!-- View Details Button (if evaluated) -->
                                    <?php if (!empty($r['evaluation_id'])): ?>
                                        <button type="button" class="btn btn-sm btn-light border view-remarks"
                                                data-bs-toggle="modal" data-bs-target="#viewRemarksModal"
                                                data-remarks="<?= esc($r['remarks'] ?? '') ?>"
                                                data-score="<?= esc($r['overall_score'] ?? '') ?>"
                                                data-result="<?= esc($r['remark_status'] ?? '') ?>"
                                                data-evaluator="<?= esc($r['evaluator_name'] ?? 'N/A') ?>"
                                                data-evaluated-at="<?= !empty($r['evaluated_at']) ? date('F d, Y h:i A', strtotime($r['evaluated_at'])) : 'N/A' ?>"
                                                data-deadline="<?= !empty($r['compliance_deadline']) ? date('F d, Y', strtotime($r['compliance_deadline'])) : '' ?>"
                                                data-title="<?= esc($r['display_title'] ?? '') ?>"
                                                data-student="<?= esc($r['researcher_name'] ?? '') ?>"
                                                title="View Remarks">
                                            <i class="bi bi-eye text-primary"></i>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Evaluate / Update Remark Status Button -->
                                    <button type="button" class="btn btn-sm <?= empty($r['evaluation_id']) ? 'btn-primary px-3' : 'btn-light border text-success' ?> evaluate-btn"
                                            data-bs-toggle="modal" data-bs-target="#evalModal"
                                            data-mode="<?= empty($r['evaluation_id']) ? 'evaluate' : 'edit' ?>"
                                            data-schedule-id="<?= $r['schedule_id'] ?>"
                                            data-researcher-id="<?= $r['researcher_id'] ?? '' ?>"
                                            data-student-id="<?= $r['student_id'] ?? '' ?>"
                                            data-result="<?= esc($r['remark_status'] ?? '') ?>"
                                            data-score="<?= esc($r['overall_score'] ?? '') ?>"
                                            data-remarks="<?= esc($r['remarks'] ?? '') ?>"
                                            data-compliance-deadline="<?= esc($r['compliance_deadline'] ?? '') ?>"
                                            data-compliance-submitted="<?= $r['compliance_submitted'] ?? 0 ?>"
                                            data-type="<?= esc($r['schedule_type'] ?? '') ?>"
                                            data-title="<?= esc($r['display_title'] ?? '') ?>"
                                            data-student="<?= esc($r['researcher_name'] ?? '') ?>"
                                            title="<?= empty($r['evaluation_id']) ? 'Evaluate Research Record' : 'Edit / Reopen Evaluation' ?>">
                                        <?php if (empty($r['evaluation_id'])): ?>
                                            <i class="bi bi-pencil-square me-1"></i> Evaluate
                                        <?php else: ?>
                                            <i class="bi bi-pencil-square"></i>
                                        <?php endif; ?>
                                    </button>

                                    <?php if (!empty($r['remark_status']) && $r['remark_status'] === 'for_compliance' && empty($r['compliance_submitted'])): ?>
                                        <a href="<?= base_url('admin/evaluations/mark-compliance/' . $r['evaluation_id']) ?>" class="btn btn-sm btn-outline-success" title="Mark Compliance as Submitted">
                                            <i class="bi bi-check2"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($r['evaluation_id'])): ?>
                                        <a href="<?= base_url('admin/evaluations/delete/' . $r['evaluation_id']) ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Are you sure you want to delete this evaluation?')" title="Delete Evaluation">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Evaluate / Edit Modal -->
<div class="modal fade" id="evalModal" tabindex="-1" aria-labelledby="evalModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" action="<?= base_url('admin/evaluations/store') ?>" id="evalForm">
                <?= csrf_field() ?>
                <input type="hidden" name="schedule_id" id="evalScheduleId">
                <input type="hidden" name="researcher_id" id="evalResearcherId">
                <input type="hidden" name="student_id" id="evalStudentId">

                <div class="modal-header bg-light border-bottom">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="evalModalTitle">Research Evaluation Form</h5>
                        <div class="text-muted small mt-1" id="evalScheduleInfo">Select or review research record details</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Schedule Selector (shown when clicked from New Evaluation top button) -->
                        <div class="col-12 d-none" id="scheduleSelectorWrap">
                            <label class="form-label small fw-bold mb-1">Select Research Defense Record <span class="text-danger">*</span></label>
                            <select id="scheduleSelector" class="form-select form-select-sm">
                                <option value="">-- Select Research Defense Record --</option>
                                <?php if (!empty($allSchedules)): ?>
                                    <?php foreach ($allSchedules as $sch): ?>
                                        <option value="<?= $sch['id'] ?>"
                                                data-researcher-id="<?= $sch['researcher_id'] ?? '' ?>"
                                                data-student-id="<?= $sch['student_id'] ?? '' ?>"
                                                data-type="<?= $sch['schedule_type'] ?? '' ?>"
                                                data-title="<?= esc($sch['schedule_title'] ?? '') ?>"
                                                data-student="<?= esc($sch['student_name'] ?? '') ?>">
                                            <?= esc($sch['schedule_title']) ?> — <?= esc($sch['student_name'] ?? 'N/A') ?> (<?= date('M d, Y', strtotime($sch['schedule_date'])) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Remark Status Field with exactly Passed, Failed, For Compliance -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Remark Status <span class="text-danger">*</span></label>
                            <select name="result" id="evalResult" class="form-select" required onchange="toggleCompliance()">
                                <option value="">-- Select Remark Status --</option>
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                                <option value="for_compliance">For Compliance</option>
                            </select>
                            <div class="form-text small">Select the official outcome of this research evaluation.</div>
                        </div>

                        <!-- Overall Score -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Overall Score <span class="text-muted fw-normal">(Optional)</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="overall_score" id="evalScore" class="form-control" placeholder="e.g. 95.00">
                        </div>

                        <!-- Compliance Deadline (conditional for For Compliance) -->
                        <div class="col-md-6 d-none" id="complianceWrap">
                            <label class="form-label small fw-bold mb-1">Compliance Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="compliance_deadline" id="complianceDeadline" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                            <div class="form-text small text-info">Date by which compliance revisions must be submitted.</div>
                        </div>

                        <!-- Compliance Submitted Checkbox (for editing existing For Compliance) -->
                        <div class="col-md-6 d-none" id="complianceSubmittedWrap">
                            <label class="form-label small fw-bold mb-1">Compliance Status</label>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="compliance_submitted" value="1" id="complianceSubmitted">
                                <label class="form-check-label small" for="complianceSubmitted">
                                    Mark Compliance Output as Submitted
                                </label>
                            </div>
                        </div>

                        <!-- Remarks / Feedback Textarea -->
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Panel Remarks / Feedback <span class="text-danger">*</span></label>
                            <textarea name="remarks" id="evalRemarks" rows="4" class="form-control" required placeholder="Enter panel remarks, evaluation feedback, recommendations, or compliance instructions..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i> Save Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Remarks Modal -->
<div class="modal fade" id="viewRemarksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4" id="viewRemarksBody"></div>
        </div>
    </div>
</div>

<script>
function toggleCompliance() {
    const val = document.getElementById('evalResult') ? document.getElementById('evalResult').value : '';
    const wrap = document.getElementById('complianceWrap');
    const subWrap = document.getElementById('complianceSubmittedWrap');
    const d = document.getElementById('complianceDeadline');

    if (wrap && subWrap && d) {
        if (val === 'for_compliance') {
            wrap.classList.remove('d-none');
            subWrap.classList.remove('d-none');
            d.required = true;
        } else {
            wrap.classList.add('d-none');
            subWrap.classList.add('d-none');
            d.required = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const evalModalEl = document.getElementById('evalModal');
    if (evalModalEl) {
        evalModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const mode = button.dataset.mode || 'evaluate';
            const scheduleSelectorWrap = document.getElementById('scheduleSelectorWrap');
            const typeLabels = {title_hearing: 'Title Hearing', pre_oral: 'Pre-Oral Defense', final_defense: 'Final Defense'};

            if (mode === 'new') {
                if (scheduleSelectorWrap) scheduleSelectorWrap.classList.remove('d-none');
                const sel = document.getElementById('scheduleSelector');
                if (sel) sel.value = '';

                document.getElementById('evalScheduleId').value = '';
                document.getElementById('evalResearcherId').value = '';
                document.getElementById('evalStudentId').value = '';
                document.getElementById('evalModalTitle').textContent = 'New Research Defense Evaluation';
                document.getElementById('evalScheduleInfo').innerHTML = 'Select an existing defense record from the dropdown below';

                document.getElementById('evalResult').value = '';
                document.getElementById('evalScore').value = '';
                document.getElementById('evalRemarks').value = '';
                document.getElementById('complianceDeadline').value = '<?= date('Y-m-d', strtotime('+7 days')) ?>';
                document.getElementById('complianceSubmitted').checked = false;
            } else {
                if (scheduleSelectorWrap) scheduleSelectorWrap.classList.add('d-none');
                document.getElementById('evalScheduleId').value = button.dataset.scheduleId || '';
                document.getElementById('evalResearcherId').value = button.dataset.researcherId || '';
                document.getElementById('evalStudentId').value = button.dataset.studentId || '';

                const typeText = typeLabels[button.dataset.type] || button.dataset.type || 'Defense';
                document.getElementById('evalModalTitle').textContent = mode === 'edit' ? 'Edit Research Defense Evaluation' : 'Evaluate Research Defense';
                document.getElementById('evalScheduleInfo').innerHTML = `<strong>${button.dataset.title || 'Research'}</strong><br><span class="text-dark">Researcher:</span> ${button.dataset.student || 'N/A'} &bull; <span class="badge bg-secondary-subtle text-secondary">${typeText}</span>`;

                // Pre-populate saved Remark Status, score, deadline, and remarks
                document.getElementById('evalResult').value = button.dataset.result || '';
                document.getElementById('evalScore').value = button.dataset.score || '';
                document.getElementById('evalRemarks').value = button.dataset.remarks || '';
                if (button.dataset.complianceDeadline) {
                    document.getElementById('complianceDeadline').value = button.dataset.complianceDeadline;
                }
                document.getElementById('complianceSubmitted').checked = (button.dataset.complianceSubmitted == 1);
            }

            toggleCompliance();
        });
    }

    const scheduleSelector = document.getElementById('scheduleSelector');
    if (scheduleSelector) {
        scheduleSelector.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                document.getElementById('evalScheduleId').value = opt.value;
                document.getElementById('evalResearcherId').value = opt.dataset.researcherId || '';
                document.getElementById('evalStudentId').value = opt.dataset.studentId || '';
                const typeLabels = {title_hearing: 'Title Hearing', pre_oral: 'Pre-Oral Defense', final_defense: 'Final Defense'};
                const typeText = typeLabels[opt.dataset.type] || opt.dataset.type || 'Defense';
                document.getElementById('evalScheduleInfo').innerHTML = `<strong>${opt.dataset.title || ''}</strong><br><span class="text-dark">Researcher:</span> ${opt.dataset.student || 'N/A'} &bull; <span class="badge bg-secondary-subtle text-secondary">${typeText}</span>`;
            }
        });
    }

    const viewRemarksModalEl = document.getElementById('viewRemarksModal');
    if (viewRemarksModalEl) {
        viewRemarksModalEl.addEventListener('show.bs.modal', function (event) {
            const b = event.relatedTarget;
            if (!b) return;

            let badgeClass = 'bg-secondary text-white';
            let iconClass = 'bi-info-circle';
            let statusLabel = b.dataset.result || 'Pending';

            if (b.dataset.result === 'passed') {
                badgeClass = 'bg-success text-white';
                iconClass = 'bi-check2-circle';
                statusLabel = 'Passed';
            } else if (b.dataset.result === 'failed') {
                badgeClass = 'bg-danger text-white';
                iconClass = 'bi-x-circle';
                statusLabel = 'Failed';
            } else if (b.dataset.result === 'for_compliance') {
                badgeClass = 'bg-info text-white';
                iconClass = 'bi-exclamation-square';
                statusLabel = 'For Compliance';
            }

            let deadlineInfo = '';
            if (b.dataset.deadline) {
                deadlineInfo = `<div class="mt-2 small text-warning-emphasis fw-semibold"><i class="bi bi-calendar-event me-1"></i> Compliance Deadline: ${b.dataset.deadline}</div>`;
            }

            document.getElementById('viewRemarksBody').innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">${b.dataset.title || 'Research Evaluation'}</h5>
                        <p class="text-muted small mb-2">Researcher: <strong>${b.dataset.student || 'N/A'}</strong></p>
                        <span class="badge ${badgeClass} px-3 py-1 fs-6">
                            <i class="bi ${iconClass} me-1"></i> ${statusLabel}
                        </span>
                        ${b.dataset.score ? `<span class="badge bg-light text-dark border ms-2">Score: ${b.dataset.score}</span>` : ''}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                ${deadlineInfo}
                <div class="border rounded p-3 bg-light my-3">
                    <div class="text-muted small fw-bold mb-2"><i class="bi bi-chat-left-quote me-1 text-primary"></i> Panel Remarks & Feedback:</div>
                    <div class="text-dark small" style="white-space: pre-wrap; line-height: 1.6;">${b.dataset.remarks || 'No remarks provided.'}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <span>Evaluator: <strong>${b.dataset.evaluator || 'N/A'}</strong></span>
                    <span>Date: <strong>${b.dataset.evaluatedAt || 'N/A'}</strong></span>
                </div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            `;
        });
    }
});
</script>
<?= $this->endSection() ?>
