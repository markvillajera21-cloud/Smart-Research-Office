<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
    .department-banner { border-left: 5px solid <?= esc($department['accent']) ?>; }
    .department-stat { border-top: 3px solid <?= esc($department['accent']) ?>; }
</style>
<div class="container-fluid py-4">
    <div class="department-banner bg-white shadow-sm p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="text-muted small text-uppercase">Department Portal</div>
                <h1 class="h3 fw-bold mb-1"><?= esc($department['name']) ?> Research Dashboard</h1>
                <p class="text-muted mb-0">Welcome, <?= esc(session()->get('username')) ?>. This dashboard contains <?= esc($department['name']) ?> records only.</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-1"></i>Log out</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card department-stat h-100 shadow-sm"><div class="card-body"><div class="text-muted small">Total Research</div><div class="display-6 fw-bold"><?= $totalResearch ?></div></div></div></div>
        <div class="col-md-3"><div class="card department-stat h-100 shadow-sm"><div class="card-body"><div class="text-muted small">Approved Research</div><div class="display-6 fw-bold text-success"><?= $approvedResearch ?></div></div></div></div>
        <div class="col-md-3"><div class="card department-stat h-100 shadow-sm"><div class="card-body"><div class="text-muted small">Upcoming Schedules</div><div class="display-6 fw-bold text-primary"><?= count($upcomingSchedules) ?></div></div></div></div>
        <div class="col-md-3"><div class="card department-stat h-100 shadow-sm"><div class="card-body"><div class="text-muted small">Passed Evaluations</div><div class="display-6 fw-bold text-success"><?= $evaluationStats['passed'] ?></div></div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm h-100"><div class="card-body">
                <h2 class="h5 fw-bold mb-3">Recent Research Records</h2>
                <?php if (!$researchers): ?><p class="text-muted small mb-0">No research records are assigned to this department yet.</p><?php else: ?>
                    <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Researcher</th><th>Title</th><th>Status</th></tr></thead><tbody>
                    <?php foreach ($researchers as $researcher): ?><tr><td class="small fw-semibold"><?= esc($researcher['fullname'] ?: 'Unnamed researcher') ?></td><td class="small"><?= esc($researcher['approved_research_title'] ?: 'No approved title') ?></td><td><span class="badge bg-light text-dark"><?= esc($researcher['status'] ?: 'Pending') ?></span></td></tr><?php endforeach; ?>
                    </tbody></table></div>
                <?php endif; ?>
            </div></div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4"><div class="card-body"><h2 class="h5 fw-bold mb-3">Evaluation Remarks</h2><div class="d-flex justify-content-between border-bottom py-2"><span>Passed</span><strong class="text-success"><?= $evaluationStats['passed'] ?></strong></div><div class="d-flex justify-content-between border-bottom py-2"><span>Failed</span><strong class="text-danger"><?= $evaluationStats['failed'] ?></strong></div><div class="d-flex justify-content-between pt-2"><span>For Compliance</span><strong class="text-warning"><?= $evaluationStats['for_compliance'] ?></strong></div></div></div>
            <div class="card shadow-sm"><div class="card-body"><h2 class="h5 fw-bold mb-3">Upcoming Schedules</h2><?php if (!$upcomingSchedules): ?><p class="text-muted small mb-0">No upcoming schedules.</p><?php else: ?><?php foreach ($upcomingSchedules as $schedule): ?><div class="border-bottom py-2"><div class="small fw-semibold"><?= esc($schedule['schedule_title']) ?></div><div class="text-muted small"><?= esc(ucwords(str_replace('_', ' ', $schedule['schedule_type']))) ?>, <?= date('M d, Y', strtotime($schedule['schedule_date'])) ?> at <?= date('h:i A', strtotime($schedule['schedule_time'])) ?></div><div class="text-muted small"><?= esc($schedule['venue']) ?></div></div><?php endforeach; ?><?php endif; ?></div></div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
