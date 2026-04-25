<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Global Research History</h5>
            <p class="text-muted small mb-0">Monitor all researcher milestones and career achievements across the institution.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> Export Report
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Researcher</th>
                    <th>Category</th>
                    <th>Achievement / Milestone</th>
                    <th>Impact</th>
                    <th>School Year</th>
                    <th>Date Achieved</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($milestones)): ?>
                    <?php foreach ($milestones as $milestone): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                        <?= strtoupper(substr($milestone['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold small"><?= $milestone['username'] ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?= $milestone['email'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                switch ($milestone['category']) {
                                    case 'PROJECT': $badgeClass = 'bg-info'; break;
                                    case 'GRANT': $badgeClass = 'bg-success'; break;
                                    case 'DATA': $badgeClass = 'bg-primary'; break;
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?> small"><?= $milestone['category'] ?></span>
                            </td>
                            <td>
                                <div class="fw-medium small"><?= $milestone['event_name'] ?></div>
                                <div class="text-muted x-small text-truncate" style="max-width: 250px;" title="<?= $milestone['description'] ?>">
                                    <?= $milestone['description'] ?>
                                </div>
                            </td>
                            <td>
                                <span class="text-primary fw-bold small">+<?= $milestone['impact_score'] ?></span>
                            </td>
                            <td>
                                <?php 
                                    $metadata = json_decode($milestone['metadata'], true);
                                    if (isset($metadata['school_year'])): 
                                ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 small">
                                        S.Y. <?= $metadata['school_year'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?= date('M d, Y', strtotime($milestone['achieved_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
                            No research history records found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .x-small {
        font-size: 0.7rem;
    }
</style>
<?= $this->endSection() ?>
