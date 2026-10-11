<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1 fw-bold text-dark"><i class="bi bi-journal-text me-2 text-primary"></i>Student Titles</h3>
            <p class="text-muted small mb-0">View-only record of your registered research title, assigned researchers, academic strand, and approval status.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-3 py-2">
            <i class="bi bi-shield-check me-1"></i> Student View-Only Access
        </span>
    </div>

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

    <!-- Overview Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card p-3 border-0 shadow-sm bg-primary-subtle text-primary h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">My Registered Titles</p>
                        <h3 class="mb-0 fw-bold"><?= count($myTitles ?? []) ?></h3>
                    </div>
                    <i class="bi bi-journal-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card p-3 border-0 shadow-sm bg-success-subtle text-success h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Approved / Completed</p>
                        <h3 class="mb-0 fw-bold">
                            <?php 
                            $approvedOrCompleted = array_filter($myTitles ?? [], function($t) {
                                $s = strtolower($t['status'] ?? '');
                                return $s === 'approved' || $s === 'completed';
                            });
                            echo count($approvedOrCompleted);
                            ?>
                        </h3>
                    </div>
                    <i class="bi bi-check2-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card p-3 border-0 shadow-sm bg-warning-subtle text-warning-emphasis h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small text-uppercase fw-semibold">Pending / In Review</p>
                        <h3 class="mb-0 fw-bold">
                            <?php 
                            $pendingTitles = array_filter($myTitles ?? [], function($t) {
                                $s = strtolower($t['status'] ?? '');
                                return $s === 'pending' || $s === 'for_revision';
                            });
                            echo count($pendingTitles);
                            ?>
                        </h3>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Titles Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-semibold text-dark">
                <i class="bi bi-list-check me-2 text-primary"></i>My Research Titles List
            </h5>
            <span class="badge bg-light text-muted border">
                Total: <?= count($myTitles ?? []) ?> record(s)
            </span>
        </div>

        <div class="card-body p-0">
            <?php if (empty($myTitles)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x display-4 mb-3 d-block text-secondary opacity-50"></i>
                    <h6 class="fw-semibold">No Research Titles Registered</h6>
                    <p class="small mb-0">You currently do not have any registered research titles. Contact your Research Teacher or Coordinator if this is unexpected.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-4" style="width: 45px;">#</th>
                                <th>Research Title</th>
                                <th>Researcher / Author</th>
                                <th>Course / Strand</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th>Dates</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $cnt = 0;
                            foreach ($myTitles as $t): 
                                $cnt++;
                                $title = !empty($t['title']) ? $t['title'] : 'Untitled Research Project';
                                
                                // Researcher name resolution
                                $author = !empty($t['researcher_fullname']) ? $t['researcher_fullname'] : (!empty($t['researcher_author']) ? $t['researcher_author'] : (session()->get('name') ?? session()->get('username') ?? 'Self'));
                                
                                // Course/Strand resolution
                                $course = !empty($t['course_name']) ? $t['course_name'] : (!empty($t['strand_name']) ? $t['strand_name'] : (!empty($t['strand_degree_program']) ? $t['strand_degree_program'] : '—'));
                                
                                // Academic Year resolution
                                $sy = !empty($t['school_year_name']) ? $t['school_year_name'] : '—';
                                
                                // Status styling
                                $rawStatus = strtolower($t['status'] ?? 'pending');
                                $statusBadge = 'bg-secondary-subtle text-secondary';
                                $statusIcon = 'bi-circle';
                                $statusLabel = ucwords(str_replace('_', ' ', $rawStatus));

                                if ($rawStatus === 'approved') {
                                    $statusBadge = 'bg-success-subtle text-success border border-success border-opacity-25';
                                    $statusIcon = 'bi-check-circle-fill';
                                    $statusLabel = 'Approved';
                                } elseif ($rawStatus === 'completed') {
                                    $statusBadge = 'bg-primary-subtle text-primary border border-primary border-opacity-25';
                                    $statusIcon = 'bi-trophy-fill';
                                    $statusLabel = 'Completed';
                                } elseif ($rawStatus === 'pending') {
                                    $statusBadge = 'bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25';
                                    $statusIcon = 'bi-hourglass-split';
                                    $statusLabel = 'Pending Approval';
                                } elseif ($rawStatus === 'for_revision') {
                                    $statusBadge = 'bg-info-subtle text-info-emphasis border border-info border-opacity-25';
                                    $statusIcon = 'bi-arrow-repeat';
                                    $statusLabel = 'For Revision';
                                } elseif ($rawStatus === 'rejected') {
                                    $statusBadge = 'bg-danger-subtle text-danger border border-danger border-opacity-25';
                                    $statusIcon = 'bi-x-circle-fill';
                                    $statusLabel = 'Rejected';
                                }

                                $submittedDate = !empty($t['submitted_at']) ? date('M d, Y', strtotime($t['submitted_at'])) : (!empty($t['created_at']) ? date('M d, Y', strtotime($t['created_at'])) : '—');
                                $approvedDate = !empty($t['approved_at']) ? date('M d, Y', strtotime($t['approved_at'])) : null;
                                $abstractText = trim($t['abstract'] ?? '');
                            ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $cnt ?></td>
                                <td style="max-width: 320px;">
                                    <div class="fw-semibold text-dark text-break">
                                        <?= esc($title) ?>
                                    </div>
                                    <?php if (!empty($t['teacher_name'])): ?>
                                        <div class="text-xs text-muted mt-1">
                                            <i class="bi bi-person-workspace me-1"></i>Instructor: <?= esc($t['teacher_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small fw-medium text-dark">
                                        <i class="bi bi-person me-1 text-muted"></i><?= esc($author) ?>
                                    </div>
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
                                <td>
                                    <span class="badge <?= $statusBadge ?> px-3 py-2">
                                        <i class="bi <?= $statusIcon ?> me-1"></i><?= esc($statusLabel) ?>
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <div><i class="bi bi-calendar-plus me-1"></i>Sub: <?= esc($submittedDate) ?></div>
                                    <?php if ($approvedDate): ?>
                                        <div class="text-success"><i class="bi bi-calendar-check me-1"></i>App: <?= esc($approvedDate) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary px-3 view-title-details-btn"
                                            data-title="<?= esc($title, 'attr') ?>"
                                            data-author="<?= esc($author, 'attr') ?>"
                                            data-course="<?= esc($course, 'attr') ?>"
                                            data-sy="<?= esc($sy, 'attr') ?>"
                                            data-status="<?= esc($statusLabel, 'attr') ?>"
                                            data-submitted="<?= esc($submittedDate, 'attr') ?>"
                                            data-approved="<?= esc($approvedDate ?? 'N/A', 'attr') ?>"
                                            data-abstract="<?= esc($abstractText, 'attr') ?>">
                                        <i class="bi bi-eye me-1"></i> View Details
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

<!-- View-Only Title Details Modal -->
<div class="modal fade" id="studentTitleDetailsModal" tabindex="-1" aria-labelledby="studentTitleDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold" id="studentTitleDetailsModalLabel">
                    <i class="bi bi-journal-text me-2 text-primary"></i>Research Title Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="small text-muted text-uppercase fw-bold mb-1">Research Title</label>
                    <h5 class="fw-bold text-dark" id="modalTitle">—</h5>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Researcher / Author</label>
                        <p class="fw-semibold text-dark mb-0" id="modalAuthor">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Course / Strand</label>
                        <p class="fw-semibold text-dark mb-0" id="modalCourse">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Academic Year</label>
                        <p class="fw-semibold text-dark mb-0" id="modalSY">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Research Status</label>
                        <p class="fw-semibold text-dark mb-0" id="modalStatus">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Submitted Date</label>
                        <p class="text-muted mb-0" id="modalSubmitted">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Approved Date</label>
                        <p class="text-muted mb-0" id="modalApproved">—</p>
                    </div>
                </div>

                <div>
                    <label class="small text-muted text-uppercase fw-bold mb-1">Research Abstract / Summary</label>
                    <div class="p-3 bg-light rounded border text-muted small" id="modalAbstract" style="min-height: 100px; white-space: pre-wrap;">
                        No abstract provided for this research title.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-title-details-btn');
    const modalEl = document.getElementById('studentTitleDetailsModal');
    
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalEl);

        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalTitle').textContent = this.getAttribute('data-title') || '—';
                document.getElementById('modalAuthor').textContent = this.getAttribute('data-author') || '—';
                document.getElementById('modalCourse').textContent = this.getAttribute('data-course') || '—';
                document.getElementById('modalSY').textContent = this.getAttribute('data-sy') || '—';
                document.getElementById('modalStatus').textContent = this.getAttribute('data-status') || '—';
                document.getElementById('modalSubmitted').textContent = this.getAttribute('data-submitted') || '—';
                document.getElementById('modalApproved').textContent = this.getAttribute('data-approved') || '—';

                const abstract = this.getAttribute('data-abstract');
                const absEl = document.getElementById('modalAbstract');
                if (abstract && abstract.trim().length > 0) {
                    absEl.textContent = abstract.trim();
                    absEl.classList.remove('text-muted');
                    absEl.classList.add('text-dark');
                } else {
                    absEl.textContent = 'No abstract provided for this research title.';
                    absEl.classList.remove('text-dark');
                    absEl.classList.add('text-muted');
                }

                modal.show();
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
