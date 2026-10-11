<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-2"></i>My Schedules</h2>
                <p class="text-muted mb-0">View all your research defense schedules</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($mySchedules)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 opacity-50"></i>
                    <p class="mt-3 mb-0">No schedules yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Schedule Type</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Instructor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mySchedules as $schedule): ?>
                                <tr>
                                    <td class="fw-medium">
                                        <?php
                                        $typeBadge = ['title_hearing' => 'bg-info', 'pre_oral' => 'bg-warning text-dark', 'final_defense' => 'bg-primary'];
                                        $typeText = ['title_hearing' => 'Title Hearing', 'pre_oral' => 'Pre-Oral Defense', 'final_defense' => 'Final Defense'];
                                        ?>
                                        <span class="badge rounded-pill px-3 py-2 <?= $typeBadge[$schedule['schedule_type']] ?? 'bg-secondary' ?>">
                                            <?= $typeText[$schedule['schedule_type']] ?? ucwords(str_replace('_', ' ', $schedule['schedule_type'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($schedule['schedule_title'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($schedule['schedule_date'])) ?></td>
                                    <td><?= date('h:i A', strtotime($schedule['schedule_time'])) ?></td>
                                    <td><?= esc($schedule['venue'] ?? 'TBA') ?></td>
                                    <td><?= esc($schedule['instructor_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php
                                        $statusBadge = ['scheduled' => 'bg-primary', 'completed' => 'bg-success', 'cancelled' => 'bg-danger', 'rescheduled' => 'bg-warning text-dark'];
                                        ?>
                                        <span class="badge rounded-pill px-3 py-2 <?= $statusBadge[$schedule['status']] ?? 'bg-secondary' ?>">
                                            <?= ucfirst($schedule['status']) ?>
                                        </span>
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
