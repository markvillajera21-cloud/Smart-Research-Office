<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Title status distribution and approval analytics.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
            <a href="<?= base_url('admin/reports/export?type=status') ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i> Export CSV</a>
            <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <?php
            $labels = ['pending' => 'Pending', 'approved' => 'Approved', 'for_revision' => 'For Revision', 'rejected' => 'Rejected', 'completed' => 'Completed'];
            $colors = ['pending' => 'warning', 'approved' => 'primary', 'for_revision' => 'info', 'rejected' => 'danger', 'completed' => 'success'];
            $total = array_sum(array_values($statusCounts));
        ?>
        <?php foreach ($statusCounts as $k => $v): ?>
            <div class="col-md-2 col-sm-4 col-6">
                <div class="card p-4 border-0 shadow-sm bg-<?= $colors[$k] ?>-subtle text-<?= $colors[$k] ?> h-100 text-center">
                    <h2 class="mb-1 fw-bold"><?= $v ?></h2>
                    <p class="mb-0 small"><?= $labels[$k] ?></p>
                    <p class="text-xs mt-1 mb-0">
                        <?= $total > 0 ? round(($v / $total) * 100, 1) : 0 ?>%
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card p-4 border-0 shadow-sm bg-dark-subtle text-dark h-100 text-center">
                <h2 class="mb-1 fw-bold"><?= $total ?></h2>
                <p class="mb-0 small">Total</p>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">Status Distribution Visualization</h5>
        <div class="progress" style="height: 30px;">
            <?php foreach ($statusCounts as $k => $v):
                $pct = $total > 0 ? ($v / $total) * 100 : 0;
                if ($pct < 1) continue;
            ?>
                <div class="progress-bar bg-<?= $colors[$k] ?>" role="progressbar" style="width:<?= $pct ?>%" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $pct > 8 ? $labels[$k] . ' ' . $v : $v ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card p-4">
        <h5 class="mb-3">Full Title Status List (<?= count($titles) ?> items)</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0 small">
                <thead class="table-warning">
                    <tr>
                        <th>#</th>
                        <th>Research Title</th>
                        <th>Student / Researcher</th>
                        <th>Strand</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($titles) === 0): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No titles.</td></tr>
                    <?php endif; ?>
                    <?php $cnt = 0; foreach ($titles as $t): $cnt++;
                        $badge = ['pending' => 'bg-warning-subtle text-warning', 'approved' => 'bg-primary-subtle text-primary', 'for_revision' => 'bg-info-subtle text-info', 'rejected' => 'bg-danger-subtle text-danger', 'completed' => 'bg-success-subtle text-success'];
                    ?>
                        <tr>
                            <td><?= $cnt ?></td>
                            <td class="fw-semibold"><?= esc($t['title']) ?></td>
                            <td><?= esc($t['student_name'] ?? $t['researcher_name'] ?? 'N/A') ?></td>
                            <td><?= esc($t['strand_name'] ?? 'N/A') ?></td>
                            <td><?= esc($t['category_name'] ?? 'N/A') ?></td>
                            <td><span class="badge rounded-pill <?= $badge[$t['status']] ?>"><?= $labels[$t['status']] ?? ucfirst($t['status']) ?></span></td>
                            <td class="text-muted"><?= !empty($t['submitted_at']) ? date('M d, Y', strtotime($t['submitted_at'])) : 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
