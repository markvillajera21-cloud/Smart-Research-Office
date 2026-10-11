<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-semibold">Assigned Evaluations</h2>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($evaluations)): ?>
                <div class="text-center py-5 text-muted">No evaluations are assigned to you.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Schedule</th>
                                <th>Date</th>
                                <th>Result</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluations as $evaluation): ?>
                                <tr>
                                    <td><?= esc($evaluation['student_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($evaluation['schedule_title'] ?? ucwords(str_replace('_', ' ', $evaluation['schedule_type'] ?? ''))) ?></td>
                                    <td><?= !empty($evaluation['schedule_date']) ? date('M d, Y', strtotime($evaluation['schedule_date'])) : 'N/A' ?></td>
                                    <td><?= esc(ucwords(str_replace('_', ' ', $evaluation['result'] ?? ''))) ?></td>
                                    <td><?= esc($evaluation['overall_score'] ?? 'N/A') ?></td>
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
