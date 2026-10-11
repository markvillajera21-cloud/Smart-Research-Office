<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-semibold"><i class="bi bi-check2-circle me-2"></i>Completed Researches</h2>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= base_url('student/completed-researches') ?>" method="get" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Search by Title or Abstract</label>
                    <input type="text" name="search" class="form-control" placeholder="Enter keywords..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Strand</label>
                    <select name="strand_id" class="form-select">
                        <option value="">All Strands</option>
                        <?php foreach ($strands as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($strandId ?? '') == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <?php if (empty($titles)): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-journal-x display-4 mb-3 d-block"></i>
                        <p class="mb-0">No completed researches found.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($titles as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success rounded-pill px-3 py-2">Completed</span>
                            <?php if (!empty($t['school_year_name'])): ?>
                                <small class="text-muted fw-medium"><?= esc($t['school_year_name']) ?></small>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-semibold mb-2 line-clamp-2"><?= esc($t['title']) ?></h5>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-person me-1"></i> <?= esc($t['student_name'] ?? 'N/A') ?>
                        </p>
                        <?php if (!empty($t['strand_name'])): ?>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-book me-1"></i> <?= esc($t['strand_name']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($t['category_name'])): ?>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-folder me-1"></i> <?= esc($t['category_name']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($t['abstract'])): ?>
                            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= esc($t['abstract']) ?>
                            </p>
                        <?php endif; ?>
                        <div class="mt-auto">
                            <a href="<?= base_url('student/view-abstract/'.$t['id']) ?>" class="btn btn-primary w-100">
                                <i class="bi bi-eye me-1"></i> Read Abstract
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
