<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-text me-2"></i>Research Abstract</h2>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-4 border-bottom">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-<?= ($research['status'] ?? '') == 'completed' ? 'success' : (($research['status'] ?? '') == 'approved' ? 'primary' : (($research['status'] ?? '') == 'pending' ? 'warning text-dark' : 'secondary')) ?> rounded-pill px-3 py-2">
                            <?= ucwords(str_replace('_', ' ', esc($research['status'] ?? 'Unknown'))) ?>
                        </span>
                        <?php if (!empty($research['school_year_name'])): ?>
                            <span class="badge bg-info rounded-pill px-3 py-2">
                                <i class="bi bi-calendar3 me-1"></i> <?= esc($research['school_year_name']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($research['strand_name'])): ?>
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="bi bi-book me-1"></i> <?= esc($research['strand_name']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($research['category_name'])): ?>
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                                <i class="bi bi-folder me-1"></i> <?= esc($research['category_name']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h3 class="fw-bold mb-2"><?= esc($research['title']) ?></h3>
                    <div class="row g-3 text-muted small">
                        <div class="col-md-4">
                            <strong>Student:</strong> <?= esc($research['student_name'] ?? 'N/A') ?>
                        </div>
                        <?php if (!empty($research['instructor_name'])): ?>
                        <div class="col-md-4">
                            <strong>Instructor:</strong> <?= esc($research['instructor_name']) ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($research['approved_at'])): ?>
                        <div class="col-md-4">
                            <strong>Approved:</strong> <?= date('F d, Y', strtotime($research['approved_at'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-5">
                    <h5 class="fw-semibold mb-3 text-primary"><i class="bi bi-file-text me-2"></i>Abstract</h5>
                    <div class="mb-5" style="line-height: 1.8; white-space: pre-wrap; text-align: justify;">
                        <?= !empty($research['abstract']) ? esc($research['abstract']) : '<p class="text-muted fst-italic">No abstract available for this research.</p>' ?>
                    </div>

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3">
                                <h6 class="fw-semibold mb-3 text-primary"><i class="bi bi-info-circle me-2"></i>Research Details</h6>
                                <dl class="row mb-0 small">
                                    <dt class="col-sm-4 text-muted">Title Status</dt>
                                    <dd class="col-sm-8"><?= ucwords(str_replace('_', ' ', esc($research['status'] ?? 'N/A'))) ?></dd>

                                    <dt class="col-sm-4 text-muted">Submitted</dt>
                                    <dd class="col-sm-8"><?= !empty($research['submitted_at']) ? date('F d, Y h:i A', strtotime($research['submitted_at'])) : 'N/A' ?></dd>

                                    <dt class="col-sm-4 text-muted">Approved At</dt>
                                    <dd class="col-sm-8"><?= !empty($research['approved_at']) ? date('F d, Y h:i A', strtotime($research['approved_at'])) : 'N/A' ?></dd>

                                    <dt class="col-sm-4 text-muted">Duplicate Score</dt>
                                    <dd class="col-sm-8">
                                        <?php 
                                            $score = $research['duplicate_check_score'] ?? 0;
                                            $scoreClass = $score >= 70 ? 'text-danger fw-bold' : ($score >= 40 ? 'text-warning fw-bold' : 'text-success fw-bold');
                                        ?>
                                        <span class="<?= $scoreClass ?>"><?= $score ?>%</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3">
                                <h6 class="fw-semibold mb-3 text-primary"><i class="bi bi-chat-left-text me-2"></i>Feedback</h6>
                                <?php if (!empty($research['revision_notes'])): ?>
                                    <div class="alert alert-warning mb-0 small" style="white-space: pre-wrap;">
                                        <strong class="d-block mb-1"><i class="bi bi-pencil-square me-1"></i> Revision Notes:</strong>
                                        <?= esc($research['revision_notes']) ?>
                                    </div>
                                <?php elseif (!empty($research['rejection_reason'])): ?>
                                    <div class="alert alert-danger mb-0 small" style="white-space: pre-wrap;">
                                        <strong class="d-block mb-1"><i class="bi bi-x-circle me-1"></i> Rejection Reason:</strong>
                                        <?= esc($research['rejection_reason']) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0 fst-italic">No feedback or remarks recorded yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 border-top d-flex justify-content-between">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Go Back
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
