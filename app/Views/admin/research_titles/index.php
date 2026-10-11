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
            <p class="text-muted small mb-0">Submit, review, approve, and manage research titles with automatic duplicate detection.</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTitleModal">
            <i class="bi bi-plus-lg me-1"></i> Submit New Title
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Pending Approval</p>
                            <h3 class="mb-0 text-warning"><?= $pendingCount ?></h3>
                        </div>
                        <div class="bg-warning-subtle text-warning p-2 rounded-2"><i class="bi bi-clock-history fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Approved</p>
                            <h3 class="mb-0 text-primary"><?= $approvedCount ?></h3>
                        </div>
                        <div class="bg-primary-subtle text-primary p-2 rounded-2"><i class="bi bi-check2-all fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Completed</p>
                            <h3 class="mb-0 text-success"><?= $completedCount ?></h3>
                        </div>
                        <div class="bg-success-subtle text-success p-2 rounded-2"><i class="bi bi-trophy fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 small text-muted">Rejected</p>
                            <h3 class="mb-0 text-danger"><?= $rejectedCount ?></h3>
                        </div>
                        <div class="bg-danger-subtle text-danger p-2 rounded-2"><i class="bi bi-x-circle fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4 mb-4">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="for_revision" <?= $status === 'for_revision' ? 'selected' : '' ?>>For Revision</option>
                    <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Strand</label>
                <select name="strand_id" class="form-select form-select-sm">
                    <option value="">All Strands</option>
                    <?php foreach ($strands as $st): ?>
                        <option value="<?= $st['id'] ?>" <?= $strandId == $st['id'] ? 'selected' : '' ?>><?= esc($st['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Category</label>
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $categoryId == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Search Title / Student</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?= $search ?? '' ?>">
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-0">Title</th>
                        <th>Student / Author</th>
                        <th>Strand / Category</th>
                        <th>Duplicate</th>
                        <th>Status</th>
                        <th class="text-end pe-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($titles) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-5"><i class="bi bi-book"></i> No titles found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($titles as $t):
                        $badges = [
                            'pending' => 'bg-warning-subtle text-warning',
                            'approved' => 'bg-primary-subtle text-primary',
                            'for_revision' => 'bg-info-subtle text-info',
                            'rejected' => 'bg-danger-subtle text-danger',
                            'completed' => 'bg-success-subtle text-success',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'for_revision' => 'For Revision',
                            'rejected' => 'Rejected',
                            'completed' => 'Completed',
                        ];
                        $dupBadge = $t['duplicate_check_score'] >= 70 ? 'bg-danger text-white' : ($t['duplicate_check_score'] >= 40 ? 'bg-warning text-dark' : 'bg-light text-muted');
                        
                        $hasAbstract = !empty(trim((string)($t['abstract'] ?? '')));
                        $finalDefensePassed = (isset($t['final_defense_status_id']) && (int)$t['final_defense_status_id'] === 2) 
                            || (isset($t['final_defense_status_name']) && strtolower($t['final_defense_status_name']) === 'passed');
                        $isCompleted = ($t['status'] === 'completed');
                    ?>
                        <tr>
                            <td class="ps-0 small">
                                <div class="fw-semibold"><?= esc($t['title']) ?></div>
                                <?php if (!empty($t['revision_notes'])): ?><div class="text-xs text-info mt-1"><i class="bi bi-info-circle"></i> Revision: <?= esc($t['revision_notes']) ?></div><?php endif; ?>
                                <?php if (!empty($t['rejection_reason'])): ?><div class="text-xs text-danger mt-1"><i class="bi bi-exclamation-triangle"></i> Rejected: <?= esc($t['rejection_reason']) ?></div><?php endif; ?>
                                <div class="text-muted text-xs mt-1">Submitted: <?= date('M d, Y h:i A', strtotime($t['submitted_at'] ?? $t['created_at'])) ?></div>
                            </td>
                            <td class="small">
                                <?php if (!empty($t['student_name'])): ?>
                                    <div class="fw-semibold"><?= esc($t['student_name']) ?></div>
                                <?php else: ?>
                                    <div class="fw-semibold"><?= esc($t['researcher_name'] ?? 'N/A') ?></div>
                                <?php endif; ?>
                                <div class="text-xs text-muted">Instr. <?= esc($t['instructor_name'] ?? 'N/A') ?></div>
                            </td>
                            <td class="small">
                                <span class="badge bg-light text-dark me-1"><?= esc($t['strand_name'] ?? 'N/A') ?></span>
                                <span class="badge bg-light text-dark"><?= esc($t['category_name'] ?? 'N/A') ?></span>
                            </td>
                            <td><span class="badge rounded-pill <?= $dupBadge ?>"><?= $t['duplicate_check_score'] ?>%</span></td>
                            <td>
                                <div class="d-flex flex-column gap-1 small" style="min-width: 175px;">
                                    <?php if ($finalDefensePassed): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle text-start">
                                            <i class="bi bi-check-circle-fill me-1"></i> Final Defense: Passed
                                        </span>
                                        
                                        <?php if ($hasAbstract): ?>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle text-start">
                                                <i class="bi bi-file-earmark-check-fill me-1"></i> Abstract: Submitted &#10003;
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle text-start">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Abstract: Required
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($isCompleted): ?>
                                            <span class="badge bg-success text-white text-start">
                                                <i class="bi bi-trophy-fill me-1"></i> Research Status: Completed
                                            </span>
                                        <?php elseif ($hasAbstract): ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-start">
                                                <i class="bi bi-arrow-right-circle me-1"></i> Ready for Completion
                                            </span>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <span class="badge rounded-pill <?= $badges[$t['status']] ?? 'bg-secondary' ?> text-start">
                                            <?= $statusLabels[$t['status']] ?? ucfirst($t['status']) ?>
                                        </span>
                                        <?php if ($hasAbstract): ?>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle text-start">
                                                <i class="bi bi-file-earmark-check-fill me-1"></i> Abstract: Submitted &#10003;
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                        <?php if ($t['status'] === 'pending'): ?>
                                            <li><a class="dropdown-item py-2" href="<?= base_url('admin/research-titles/approve/' . $t['id']) ?>"><i class="bi bi-check2-circle me-2 text-primary"></i> Approve</a></li>
                                            <li><a class="dropdown-item py-2 reject-btn" href="#" data-id="<?= $t['id'] ?>" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle me-2 text-danger"></i> Reject</a></li>
                                            <li><a class="dropdown-item py-2 revision-btn" href="#" data-id="<?= $t['id'] ?>" data-bs-toggle="modal" data-bs-target="#revisionModal"><i class="bi bi-pencil-square me-2 text-info"></i> For Revision</a></li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 assign-teacher-btn" href="#" data-id="<?= $t['id'] ?>" data-title="<?= esc($t['title'], 'attr') ?>" data-instructor-id="<?= $t['instructor_id'] ?? '' ?>"><i class="bi bi-person-video3 me-2 text-primary"></i> Assign Teacher/Research Instructor</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 title-view-abstract-btn" href="#" data-id="<?= $t['id'] ?>"><i class="bi bi-eye me-2 text-info"></i> View Abstract</a></li>
                                        <li><a class="dropdown-item py-2 title-submit-abstract-btn" href="#" data-id="<?= $t['id'] ?>" data-title="<?= esc($t['title'], 'attr') ?>" data-abstract="<?= esc($t['abstract'] ?? '', 'attr') ?>"><i class="bi bi-file-earmark-text me-2 text-primary"></i> <?= $hasAbstract ? 'Edit Abstract' : 'Submit Abstract' ?></a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <?php if ($t['status'] !== 'completed'): ?>
                                            <?php if ($hasAbstract): ?>
                                                <li><a class="dropdown-item py-2 text-success fw-bold" href="<?= base_url('admin/research-titles/mark-completed/' . $t['id']) ?>"><i class="bi bi-trophy me-2"></i> Mark Completed</a></li>
                                            <?php else: ?>
                                                <li><a class="dropdown-item py-2 text-muted" href="javascript:void(0)" onclick="alert('Cannot mark research as completed. An approved abstract must be submitted after passing the Final Defense before completing the research project.');"><i class="bi bi-lock me-2 text-muted"></i> Mark Completed (Abstract Required)</a></li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item py-2" href="<?= base_url('admin/research-titles/edit/' . $t['id']) ?>"><i class="bi bi-pencil me-2 text-secondary"></i> Edit Details</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 text-danger" href="<?= base_url('admin/research-titles/delete/' . $t['id']) ?>" onclick="return confirm('Delete title?')"><i class="bi bi-trash me-2"></i> Delete</a></li>
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

<!-- Add/Edit Title Modal -->
<div class="modal fade" id="addTitleModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" action="<?= base_url(isset(session()->getFlashdata('edit_title')['id']) ? 'admin/research-titles/update/' . session()->getFlashdata('edit_title')['id'] : 'admin/research-titles/store') ?>" id="addTitleForm">
                <?php $editTitle = session()->getFlashdata('edit_title'); ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title"><?= !empty($editTitle) ? 'Edit Research Title' : 'Submit New Research Title' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm small d-none" id="duplicateWarning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong class="dup-score"></strong>% similarity to existing titles! <a href="#" id="viewDupBtn" class="alert-link">View duplicates</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small mb-1">Research Title *</label>
                            <textarea id="titleInput" name="title" rows="2" class="form-control" required placeholder="Enter the full research title..."><?= !empty($editTitle['title']) ? esc($editTitle['title']) : '' ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small mb-1">Abstract</label>
                            <textarea name="abstract" rows="4" class="form-control" placeholder="Abstract / Short description..."><?= !empty($editTitle['abstract']) ? esc($editTitle['abstract']) : '' ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Student</label>
                            <select name="student_id" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <?php foreach ($students as $st): ?>
                                    <option value="<?= $st['id'] ?>" <?= (!empty($editTitle['student_id']) && $editTitle['student_id'] == $st['id']) ? 'selected' : '' ?>><?= esc($st['username']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Researcher</label>
                            <select name="researcher_id" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <?php foreach ($researchers as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= (!empty($editTitle['researcher_id']) && $editTitle['researcher_id'] == $r['id']) ? 'selected' : '' ?>><?= esc($r['fullname'] ?? ($r['surname'] ?? '' . ', ' . $r['first_name'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Instructor / Adviser</label>
                            <select name="instructor_id" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <?php foreach ($instructors as $ins): ?>
                                    <option value="<?= $ins['id'] ?>" <?= (!empty($editTitle['instructor_id']) && $editTitle['instructor_id'] == $ins['id']) ? 'selected' : '' ?>><?= esc($ins['username']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Strand / Degree Program</label>
                            <select name="strand_id" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <?php foreach ($strands as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= (!empty($editTitle['strand_id']) && $editTitle['strand_id'] == $s['id']) ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Category</label>
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= (!empty($editTitle['category_id']) && $editTitle['category_id'] == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">School Year</label>
                            <select name="school_year_id" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <?php foreach ($schoolYears as $sy): ?>
                                    <option value="<?= $sy['id'] ?>" <?= (!empty($editTitle['school_year_id']) && $editTitle['school_year_id'] == $sy['id']) ? 'selected' : '' ?>><?= esc($sy['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" <?= (!empty($editTitle['status']) && $editTitle['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= (!empty($editTitle['status']) && $editTitle['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                                <option value="for_revision" <?= (!empty($editTitle['status']) && $editTitle['status'] === 'for_revision') ? 'selected' : '' ?>>For Revision</option>
                                <option value="rejected" <?= (!empty($editTitle['status']) && $editTitle['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                                <option value="completed" <?= (!empty($editTitle['status']) && $editTitle['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Save Title</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" id="rejectForm" action="">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title">Reject Research Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label small mb-1">Reason for Rejection *</label>
                    <textarea name="reason" rows="3" class="form-control" required placeholder="Please provide a clear reason for rejection..."></textarea>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x-circle me-1"></i> Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" id="revisionForm" action="">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title">Request Revision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label small mb-1">Revision Notes *</label>
                    <textarea name="notes" rows="3" class="form-control" required placeholder="What needs to be revised?"></textarea>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info btn-sm"><i class="bi bi-pencil-square me-1"></i> Submit Revision Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Abstract Modal -->
<div class="modal fade" id="titleViewAbstractModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i>Research Abstract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="titleViewAbstractBody">
                <div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button class="btn btn-light border btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Submit Abstract Modal -->
<div class="modal fade" id="titleSubmitAbstractModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" id="titleSubmitAbstractForm" action="">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Abstract Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 mb-3 small border-0">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Abstract Requirement:</strong> An approved abstract must be submitted after passing the Final Defense before this research can be marked as completed.
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Research Title</label>
                        <input type="text" id="tmodalTitleDisplay" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Abstract Content <span class="text-danger">*</span></label>
                        <textarea name="abstract" id="tmodalAbstractInput" class="form-control" rows="8" required placeholder="Enter or paste the complete abstract here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check2-circle me-1"></i> Save & Submit Abstract</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($editTitle)): ?>
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal(document.getElementById('addTitleModal')).show());</script>
<?php endif; ?>

<script>
const titleInput = document.getElementById('titleInput');
const dupWarning = document.getElementById('duplicateWarning');
const addTitleForm = document.getElementById('addTitleForm');
let isExactDuplicate = false;

titleInput.addEventListener('blur', () => {
    const title = titleInput.value.trim();
    const id = '<?= !empty($editTitle['id']) ? $editTitle['id'] : '' ?>';
    if (title.length < 5) { dupWarning.classList.add('d-none'); return; }
    const formData = new FormData();
    formData.append('title', title);
    if (id) formData.append('id', id);
    fetch('<?= base_url('admin/research-titles/check-duplicate') ?>', {
        method: 'POST',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        isExactDuplicate = res.is_exact_duplicate === true;
        if (isExactDuplicate) {
            dupWarning.classList.remove('d-none');
            dupWarning.className = 'alert alert-danger border-0 shadow-sm small';
            dupWarning.innerHTML = '<i class="bi bi-x-octagon-fill me-2"></i><strong>Duplicate Research Title:</strong> ' + res.message;
        } else if (res.top_score >= 40) {
            dupWarning.classList.remove('d-none');
            dupWarning.querySelector('.dup-score') && (dupWarning.querySelector('.dup-score').textContent = res.top_score);
            dupWarning.className = res.top_score >= 70 ? 'alert alert-warning border-0 shadow-sm small' : 'alert alert-info border-0 shadow-sm small';
            dupWarning.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>' + res.top_score + '% similar</strong> to an existing title. Please review before submitting.';
        } else {
            isExactDuplicate = false;
            dupWarning.classList.add('d-none');
        }
    });
});

// Prevent form submission if an exact duplicate was flagged
addTitleForm.addEventListener('submit', function(e) {
    if (isExactDuplicate) {
        e.preventDefault();
        dupWarning.classList.remove('d-none');
        dupWarning.className = 'alert alert-danger border-0 shadow-sm small';
        dupWarning.innerHTML = '<i class="bi bi-x-octagon-fill me-2"></i><strong>Duplicate Research Title:</strong> This research title already exists in the system. Please enter a different research title.';
        titleInput.focus();
    }
});
</script>

<script>
document.querySelectorAll('.reject-btn').forEach(b => {
    b.addEventListener('click', () => {
        document.getElementById('rejectForm').action = '<?= base_url('admin/research-titles/reject/') ?>' + b.dataset.id;
    });
});
document.querySelectorAll('.revision-btn').forEach(b => {
    b.addEventListener('click', () => {
        document.getElementById('revisionForm').action = '<?= base_url('admin/research-titles/for-revision/') ?>' + b.dataset.id;
    });
});

document.querySelectorAll('.title-view-abstract-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const modalBody = document.getElementById('titleViewAbstractBody');
        modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
        new bootstrap.Modal(document.getElementById('titleViewAbstractModal')).show();

        fetch('<?= base_url('admin/research-titles/ajax-abstract/') ?>' + id, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
            .then(r => r.json())
            .then(data => {
                if (data.status !== 'success') {
                    modalBody.innerHTML = '<div class="alert alert-danger">Unable to load abstract record.</div>';
                    return;
                }
                const hasAb = data.has_abstract;
                modalBody.innerHTML = `
                    <div class="mb-3 border-bottom pb-3">
                        <h6 class="fw-bold mb-1">${data.title}</h6>
                        <div class="text-muted small"><strong>Author:</strong> ${data.author} &bull; <strong>Status:</strong> <span class="badge bg-info-subtle text-info">${data.research_status}</span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold small text-primary"><i class="bi bi-file-text me-1"></i>Abstract Content:</span>
                            <span class="badge ${hasAb ? 'bg-success' : 'bg-warning text-dark'}">${hasAb ? 'Submitted' : 'No Abstract Submitted'}</span>
                        </div>
                        <div class="p-3 bg-light rounded small" style="line-height: 1.8; white-space: pre-wrap; max-height: 350px; overflow-y: auto;">${hasAb ? data.abstract : '<em class="text-muted">No abstract submitted yet. An abstract must be submitted after passing Final Defense.</em>'}</div>
                    </div>
                `;
            });
    });
});

document.querySelectorAll('.title-submit-abstract-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const title = this.dataset.title;
        const abstract = this.dataset.abstract;

        document.getElementById('titleSubmitAbstractForm').action = '<?= base_url('admin/research-titles/submit-abstract/') ?>' + id;
        document.getElementById('tmodalTitleDisplay').value = title;
        document.getElementById('tmodalAbstractInput').value = abstract || '';

        new bootstrap.Modal(document.getElementById('titleSubmitAbstractModal')).show();
    });
});

document.querySelectorAll('.assign-teacher-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const title = this.dataset.title;
        const instructorId = this.dataset.instructorId || '';

        document.getElementById('assignTeacherForm').action = '<?= base_url('admin/research-titles/assign-teacher/') ?>' + id;
        document.getElementById('assignTitleDisplay').textContent = title;
        document.getElementById('assignInstructorSelect').value = instructorId;

        new bootstrap.Modal(document.getElementById('assignTeacherModal')).show();
    });
});
</script>

<!-- Assign Teacher / Research Instructor Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="assignTeacherForm" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-person-video3 me-2"></i>Assign Teacher/Research Instructor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 p-3 bg-light rounded border">
                        <div class="small text-muted mb-1">Research Title:</div>
                        <div class="fw-bold text-dark" id="assignTitleDisplay"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Teacher/Research Instructor <span class="text-danger">*</span></label>
                        <select name="instructor_id" id="assignInstructorSelect" class="form-select shadow-sm" required>
                            <option value="">-- Select Teacher/Research Instructor --</option>
                            <?php foreach ($instructors ?? [] as $inst): ?>
                                <option value="<?= $inst['id'] ?>"><?= esc($inst['username']) ?> (<?= esc($inst['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text small">The assigned Teacher/Research Instructor will be able to view this title in their <strong>Student Titles</strong> and update its research status.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check2-circle me-1"></i> Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
