<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="mb-1 fw-semibold"><i class="bi bi-journal-text me-2 text-primary"></i>Assigned Research Titles</h2>
            <p class="text-muted mb-0">Review, manage, and update the approval and defense progress status of research titles assigned to you.</p>
        </div>
        <span class="badge bg-light text-dark border px-3 py-2">
            Total Assigned: <?= count($titles ?? []) ?>
        </span>
    </div>

    <!-- Feedback Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Summary Metric Cards -->
    <?php
    $pendingCount = count(array_filter($titles ?? [], fn($t) => ($t['status'] ?? '') === 'pending'));
    $approvedCount = count(array_filter($titles ?? [], fn($t) => ($t['status'] ?? '') === 'approved'));
    $revisionCount = count(array_filter($titles ?? [], fn($t) => ($t['status'] ?? '') === 'for_revision'));
    $completedCount = count(array_filter($titles ?? [], fn($t) => ($t['status'] ?? '') === 'completed'));
    $rejectedCount = count(array_filter($titles ?? [], fn($t) => ($t['status'] ?? '') === 'rejected'));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <span class="text-muted small">Pending</span>
                    <h4 class="fw-bold mb-0 text-warning"><?= $pendingCount ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <span class="text-muted small">Approved</span>
                    <h4 class="fw-bold mb-0 text-primary"><?= $approvedCount ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <span class="text-muted small">For Revision</span>
                    <h4 class="fw-bold mb-0 text-info"><?= $revisionCount ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <span class="text-muted small">Completed</span>
                    <h4 class="fw-bold mb-0 text-success"><?= $completedCount ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <span class="text-muted small">Rejected</span>
                    <h4 class="fw-bold mb-0 text-danger"><?= $rejectedCount ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <span class="text-muted small">Total Titles</span>
                    <h4 class="fw-bold mb-0 text-dark"><?= count($titles ?? []) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Titles Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($titles)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x display-4 mb-3 d-block"></i>
                    <p class="mb-0">No research titles are currently assigned to you.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-4" style="min-width: 280px;">Research Title</th>
                                <th style="min-width: 180px;">Student / Author</th>
                                <th style="min-width: 160px;">Program / Track</th>
                                <th style="min-width: 140px;">Current Status</th>
                                <th style="min-width: 200px;">Remarks / Notes</th>
                                <th class="text-end pe-4" style="min-width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($titles as $titleRow): ?>
                                <?php
                                $status = $titleRow['status'] ?? 'pending';
                                $badgeClass = match ($status) {
                                    'approved'     => 'bg-primary-subtle text-primary border border-primary border-opacity-25',
                                    'completed'    => 'bg-success-subtle text-success border border-success border-opacity-25',
                                    'for_revision' => 'bg-info-subtle text-info-emphasis border border-info border-opacity-25',
                                    'rejected'     => 'bg-danger-subtle text-danger border border-danger border-opacity-25',
                                    default        => 'bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25',
                                };
                                $statusLabel = match ($status) {
                                    'approved'     => 'Approved',
                                    'completed'    => 'Completed',
                                    'for_revision' => 'For Revision',
                                    'rejected'     => 'Rejected',
                                    default        => 'Pending Approval',
                                };
                                $currentNotes = !empty($titleRow['revision_notes']) ? $titleRow['revision_notes'] : (!empty($titleRow['rejection_reason']) ? $titleRow['rejection_reason'] : '');
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark"><?= esc($titleRow['title'] ?? 'N/A') ?></div>
                                        <?php if (!empty($titleRow['school_year_name'])): ?>
                                            <span class="badge bg-light text-muted border text-xs mt-1">S.Y. <?= esc($titleRow['school_year_name']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">
                                            <?= esc($titleRow['student_name'] ?: ($titleRow['researcher_name'] ?? 'N/A')) ?>
                                        </div>
                                        <?php if (!empty($titleRow['student_name'])): ?>
                                            <small class="text-muted"><i class="bi bi-person-badge me-1"></i><?= esc($titleRow['student_name']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                                            <?= esc($titleRow['strand_name'] ?? 'General') ?>
                                        </span>
                                        <?php if (!empty($titleRow['category_name'])): ?>
                                            <div class="text-xs text-muted mt-1"><?= esc($titleRow['category_name']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $badgeClass ?> px-3 py-2 fw-semibold">
                                            <?= esc($statusLabel) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($currentNotes)): ?>
                                            <div class="small text-muted text-truncate" style="max-width: 220px;" title="<?= esc($currentNotes) ?>">
                                                <?= esc($currentNotes) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" 
                                                class="btn btn-sm btn-primary shadow-sm update-status-btn d-inline-flex align-items-center gap-1"
                                                data-id="<?= $titleRow['id'] ?>"
                                                data-title="<?= esc($titleRow['title'], 'attr') ?>"
                                                data-student="<?= esc($titleRow['student_name'] ?: ($titleRow['researcher_name'] ?? 'Student'), 'attr') ?>"
                                                data-status="<?= esc($status, 'attr') ?>"
                                                data-notes="<?= esc($currentNotes, 'attr') ?>">
                                            <i class="bi bi-arrow-repeat"></i> Update Status
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="updateStatusForm" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-semibold" id="updateStatusModalLabel">
                        <i class="bi bi-arrow-repeat me-2"></i>Update Research Title Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 p-3 bg-light rounded border">
                        <div class="small text-muted mb-1">Research Title:</div>
                        <div class="fw-bold text-dark" id="modalTitleText"></div>
                        <div class="small text-muted mt-2">Student Author: <span class="fw-semibold text-dark" id="modalStudentText"></span></div>
                    </div>

                    <div class="mb-3">
                        <label for="statusSelect" class="form-label fw-semibold">Select Status <span class="text-danger">*</span></label>
                        <select name="status" id="statusSelect" class="form-select shadow-sm" required>
                            <option value="pending">Pending Approval</option>
                            <option value="approved">Approved</option>
                            <option value="for_revision">For Revision</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                        <div class="form-text small">Select the current progress or decision status for this title.</div>
                    </div>

                    <div class="mb-3">
                        <label for="statusNotes" class="form-label fw-semibold">Teacher Remarks / Revision Notes</label>
                        <textarea name="notes" id="statusNotes" class="form-control" rows="3" placeholder="Enter comments, revision instructions, or approval remarks..."></textarea>
                        <div class="form-text small">These notes will be recorded in the system audit log and accessible to the student.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Save Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('updateStatusModal');
    const updateModal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('updateStatusForm');
    const titleText = document.getElementById('modalTitleText');
    const studentText = document.getElementById('modalStudentText');
    const statusSelect = document.getElementById('statusSelect');
    const statusNotes = document.getElementById('statusNotes');

    document.querySelectorAll('.update-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const student = this.getAttribute('data-student');
            const currentStatus = this.getAttribute('data-status');
            const notes = this.getAttribute('data-notes') || '';

            form.action = '<?= base_url('teacher/titles/update-status') ?>/' + id;
            titleText.textContent = title;
            studentText.textContent = student;
            statusSelect.value = currentStatus;
            statusNotes.value = notes;

            updateModal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
