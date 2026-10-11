<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="mb-1 fw-semibold"><i class="bi bi-person-video3 me-2 text-primary"></i>Teacher/Research Instructor Dashboard</h2>
        <p class="text-muted mb-0">View only the students, schedules, titles, and evaluations assigned to you.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Assigned Students</div>
                    <div class="display-6 fw-bold"><?= count($students) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Upcoming Schedules</div>
                    <div class="display-6 fw-bold"><?= count($upcomingSchedules) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Research Titles</div>
                    <div class="display-6 fw-bold"><?= count($titles) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Evaluations</div>
                    <div class="display-6 fw-bold"><?= count($evaluations) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h3 class="h5 fw-semibold mb-3">Upcoming Schedules</h3>
            <?php if (empty($upcomingSchedules)): ?>
                <p class="text-muted mb-0">No upcoming schedules assigned to you.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Venue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($upcomingSchedules, 0, 8) as $schedule): ?>
                                <tr>
                                    <td><?= esc($schedule['student_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($schedule['schedule_title'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($schedule['schedule_date'])) ?></td>
                                    <td><?= esc($schedule['venue'] ?? 'TBA') ?></td>
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
