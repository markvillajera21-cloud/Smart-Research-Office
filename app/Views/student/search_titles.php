<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-semibold"><i class="bi bi-search me-2"></i>Search Research Titles</h2>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= base_url('student/search-titles') ?>" method="get" class="row g-3">
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
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($titles)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x display-4 mb-3 d-block"></i>
                    <p class="mb-0">No completed researches found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Student</th>
                                <th>Strand</th>
                                <th>Category</th>
                                <th>School Year</th>
                                <th>Approved</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($titles as $t): ?>
                            <tr>
                                <td class="fw-medium"><?= esc($t['title']) ?></td>
                                <td><?= esc($t['student_name'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-info rounded-pill px-3 py-2"><?= esc($t['strand_name'] ?? 'N/A') ?></span></td>
                                <td><?= esc($t['category_name'] ?? 'N/A') ?></td>
                                <td><?= esc($t['school_year_name'] ?? 'N/A') ?></td>
                                <td><?= !empty($t['approved_at']) ? date('M d, Y', strtotime($t['approved_at'])) : 'N/A' ?></td>
                                <td>
                                    <a href="<?= base_url('student/view-abstract/'.$t['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i> View Abstract
                                    </a>
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
<?= $this->endSection() ?>
