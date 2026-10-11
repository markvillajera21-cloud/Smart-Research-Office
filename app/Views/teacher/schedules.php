<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-semibold">My Research Schedules</h2>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($schedules)): ?>
                <div class="text-center py-5 text-muted">No schedules assigned to you.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?= esc(ucwords(str_replace('_', ' ', $schedule['schedule_type'] ?? ''))) ?></td>
                                    <td><?= esc($schedule['schedule_title'] ?? 'N/A') ?></td>
                                    <td><?= esc($schedule['student_name'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($schedule['schedule_date'])) ?></td>
                                    <td><?= date('h:i A', strtotime($schedule['schedule_time'])) ?></td>
                                    <td><?= esc($schedule['venue'] ?? 'TBA') ?></td>
                                    <td><?= esc(ucfirst($schedule['status'] ?? '')) ?></td>
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
