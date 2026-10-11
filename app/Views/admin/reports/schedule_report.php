<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0"><?= date('F d, Y', strtotime($startDate)) ?> to <?= date('F d, Y', strtotime($endDate)) ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/reports') ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
            <a href="<?= base_url('admin/reports/export?type=schedule') ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i> Export CSV</a>
            <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm bg-primary-subtle text-primary">
                <p class="mb-1 small">Total Schedules</p>
                <h2 class="mb-0 fw-bold"><?= $stats['total'] ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm bg-info-subtle text-info">
                <p class="mb-1 small">Title Hearings</p>
                <h2 class="mb-0 fw-bold"><?= $stats['title_hearing'] ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm bg-warning-subtle text-warning">
                <p class="mb-1 small">Pre-Oral Defenses</p>
                <h2 class="mb-0 fw-bold"><?= $stats['pre_oral'] ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0 shadow-sm bg-success-subtle text-success">
                <p class="mb-1 small">Final Defenses</p>
                <h2 class="mb-0 fw-bold"><?= $stats['final_defense'] ?></h2>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Title / Event</th>
                        <th>Student / Researcher</th>
                        <th>Instructor</th>
                        <th>Venue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($schedules) === 0): ?>
                        <tr><td colspan="9" class="text-center py-4 text-muted">No schedules for selected date range.</td></tr>
                    <?php endif; ?>
                    <?php
                    $typeLabel = ['title_hearing' => 'Title Hearing', 'pre_oral' => 'Pre-Oral', 'final_defense' => 'Final Defense'];
                    $cnt = 0;
                    foreach ($schedules as $s): $cnt++;
                    ?>
                        <tr>
                            <td class="small"><?= $cnt ?></td>
                            <td class="small"><?= date('M d, Y', strtotime($s['schedule_date'])) ?></td>
                            <td class="small"><?= date('h:i A', strtotime($s['schedule_time'])) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= $typeLabel[$s['schedule_type']] ?></span></td>
                            <td class="small"><?= esc($s['schedule_title']) ?></td>
                            <td class="small"><?= esc($s['student_name'] ?? $s['researcher_name'] ?? 'N/A') ?></td>
                            <td class="small text-muted"><?= esc($s['instructor_name'] ?? 'N/A') ?></td>
                            <td class="small text-muted"><?= esc($s['venue']) ?></td>
                            <td class="small"><span class="badge bg-light text-dark"><?= ucfirst($s['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
