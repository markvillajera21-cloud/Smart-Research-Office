<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-semibold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Research Expert Pool</h2>
            <p class="text-muted mb-0 mt-1 small">Active research experts available for guidance and panel assignments.</p>
        </div>
    </div>

    <?php if (empty($experts)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-people display-4 mb-3 d-block"></i>
                <p class="mb-0">No active experts registered at this time.</p>
            </div>
        </div>
    <?php else: ?>
        <?php
        $grouped = [];
        foreach ($experts as $e) {
            $grouped[$e['expert_role']][] = $e;
        }
        $roleIcons = [
            'adviser'     => 'bi-chat-right-dots-fill',
            'grammarian'  => 'bi-translate',
            'statistician'=> 'bi-bar-chart-fill',
        ];
        $roleBadges = [
            'adviser'     => 'bg-success-subtle text-success border border-success border-opacity-25',
            'grammarian'  => 'bg-warning-subtle text-warning border border-warning border-opacity-25',
            'statistician'=> 'bg-info-subtle text-info border border-info border-opacity-25',
        ];
        ?>

        <?php foreach ($grouped as $role => $roleExperts): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                <i class="bi <?= $roleIcons[$role] ?? 'bi-person-fill' ?> text-primary fs-5"></i>
                <h5 class="mb-0 fw-semibold"><?= esc(\App\Models\ExpertModel::roleLabel($role)) ?>s</h5>
                <span class="badge bg-primary-subtle text-primary ms-2"><?= count($roleExperts) ?> active</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-4" style="width: 50px;">#</th>
                                <th>Expert Name</th>
                                <th>Role</th>
                                <th>Specialization / Subject</th>
                                <th>Department</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roleExperts as $i => $expert): ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                                <td class="fw-semibold">
                                    <i class="bi <?= $roleIcons[$expert['expert_role']] ?? 'bi-person' ?> me-2 text-muted"></i>
                                    <?= esc($expert['name']) ?>
                                </td>
                                <td>
                                    <span class="badge <?= $roleBadges[$expert['expert_role']] ?? 'bg-secondary-subtle text-secondary' ?> px-3 py-2">
                                        <?= esc(\App\Models\ExpertModel::roleLabel($expert['expert_role'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($expert['specialization'])): ?>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-book me-1 text-primary"></i><?= esc($expert['specialization']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($expert['department'])): ?>
                                        <span class="small text-secondary fw-medium">
                                            <i class="bi bi-building me-1 text-muted"></i><?= esc($expert['department']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>
