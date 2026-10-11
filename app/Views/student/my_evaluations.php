<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Your defense results, scores, and panelist remarks.</p>
        </div>
        <a href="<?= base_url('student/dashboard') ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>

    <?php if (count($evaluations) === 0): ?>
        <div class="card border-0 shadow-sm p-5 text-center">
            <div class="bg-warning-subtle text-warning p-5 rounded-circle d-inline-flex mb-4">
                <i class="bi bi-chat-square-text fs-1"></i>
            </div>
            <h5 class="mb-1">No evaluations yet</h5>
            <p class="text-muted small mb-0 max-w-md mx-auto">Once you complete a title hearing, pre-oral defense, or final defense, your results, scores, and panelists' comments will appear here.</p>
        </div>
    <?php else: ?>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <?php
            $passed = count(array_filter($evaluations, fn($e) => $e['result'] === 'passed'));
            $failed = count(array_filter($evaluations, fn($e) => $e['result'] === 'failed'));
            $forComp = count(array_filter($evaluations, fn($e) => $e['result'] === 'for_compliance'));
            $total = count($evaluations);
            $avg = $total > 0 ? round(array_sum(array_column(array_filter($evaluations, fn($e) => !empty($e['overall_score'])), 'overall_score')) / max(1,count(array_filter($evaluations, fn($e) => !empty($e['overall_score'])))), 1) : 0;
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center bg-success-subtle text-success h-100">
                <div class="fw-bold display-5 mb-1"><?= $passed ?></div>
                <div class="small">Passed</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center bg-danger-subtle text-danger h-100">
                <div class="fw-bold display-5 mb-1"><?= $failed ?></div>
                <div class="small">Failed</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center bg-info-subtle text-info h-100">
                <div class="fw-bold display-5 mb-1"><?= $forComp ?></div>
                <div class="small">For Compliance</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center bg-primary-subtle text-primary h-100">
                <div class="fw-bold display-5 mb-1"><?= $avg ?: 'N/A' ?></div>
                <div class="small">Avg Score</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php
                    $typeLabel = ['title_hearing' => 'Title Hearing', 'pre_oral' => 'Pre-Oral Defense', 'final_defense' => 'Final Defense'];
                    $resultBadge = ['passed' => 'bg-success text-white', 'failed' => 'bg-danger text-white', 'for_compliance' => 'bg-info text-white'];
                    $resultIcon = ['passed' => 'bi-check2-circle', 'failed' => 'bi-x-circle', 'for_compliance' => 'bi-exclamation-square'];
                    $resultLabel = ['passed' => 'PASSED', 'failed' => 'FAILED', 'for_compliance' => 'FOR COMPLIANCE'];
                    $typeColors = ['title_hearing' => 'border-info', 'pre_oral' => 'border-warning', 'final_defense' => 'border-success'];
                ?>
                <?php foreach ($evaluations as $e): ?>
                    <div class="list-group-item p-5 border-start border-5 <?= $typeColors[$e['schedule_type']] ?? 'border-primary' ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                            <div>
                                <span class="badge bg-light text-dark rounded-pill me-2 mb-1">
                                    <i class="bi bi-mortarboard me-1"></i><?= $typeLabel[$e['schedule_type']] ?? $e['schedule_type'] ?>
                                </span>
                                <h5 class="mb-2 fw-bold d-inline"><?= esc($e['schedule_title'] ?? 'Defense Evaluation') ?></h5>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 fs-6 <?= $resultBadge[$e['result']] ?>">
                                <i class="bi <?= $resultIcon[$e['result']] ?> me-2"></i>
                                <?= $resultLabel[$e['result']] ?>
                            </span>
                        </div>

                        <div class="row g-3 mb-4 small">
                            <div class="col-md-4">
                                <span class="text-muted d-block mb-1">Evaluated On</span>
                                <strong class="text-dark"><?= date('F d, Y', strtotime($e['evaluated_at'])) ?></strong>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted d-block mb-1">Overall Score</span>
                                <strong class="text-dark fs-5"><?= !empty($e['overall_score']) ? $e['overall_score'] . '%' : 'N/A' ?></strong>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted d-block mb-1">Evaluated By</span>
                                <strong class="text-dark"><?= esc($e['evaluator_name'] ?? 'Panel / Instructor') ?></strong>
                            </div>
                        </div>

                        <div class="bg-light rounded p-4 mb-3">
                            <div class="d-flex align-items-start mb-2">
                                <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3 mt-1"><i class="bi bi-chat-left-quote-fill fs-5"></i></div>
                                <div>
                                    <div class="text-muted small mb-1 fw-semibold">Panel Remarks & Comments</div>
                                    <p class="mb-0 text-dark" style="white-space: pre-wrap; line-height: 1.8;"><?= esc($e['remarks']) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($e['result'] === 'for_compliance'): ?>
                            <div class="card border-warning border-2 mb-3 bg-warning-subtle">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-warning text-white rounded-circle p-2 me-3 mt-0"><i class="bi bi-exclamation-triangle-fill fs-5"></i></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-warning-emphasis">
                                                COMPLIANCE REQUIRED
                                                <?php if (!empty($e['compliance_submitted']) && $e['compliance_submitted']): ?>
                                                    <span class="badge bg-success text-white ms-2"><i class="bi bi-check2 me-1"></i> COMPLIANCE SUBMITTED</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger text-white ms-2">PENDING</span>
                                                <?php endif; ?>
                                            </h6>
                                            <div class="mb-1 small">
                                                <span class="text-muted me-2">Compliance Deadline:</span>
                                                <strong class="text-warning-emphasis"><?= !empty($e['compliance_deadline']) ? date('F d, Y', strtotime($e['compliance_deadline'])) : 'As advised by instructor' ?></strong>
                                                <?php
                                                    if (!empty($e['compliance_deadline'])) {
                                                        $diff = floor((strtotime($e['compliance_deadline']) - time()) / 86400);
                                                        if (!$e['compliance_submitted']) {
                                                            if ($diff < 0) echo '<span class="badge bg-danger text-white ms-2">OVERDUE by ' . abs($diff) . ' days</span>';
                                                            else if ($diff <= 3) echo '<span class="badge bg-warning text-dark ms-2">' . $diff . ' day(s) remaining</span>';
                                                            else echo '<span class="badge bg-success text-white ms-2">' . $diff . ' days left</span>';
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <p class="small text-muted mb-0">Coordinate with your instructor to submit the required compliance outputs.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($e['result'] === 'passed'): ?>
                            <div class="bg-success-subtle text-success-emphasis border border-success-subtle rounded p-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-trophy-fill fs-2 me-3 text-success"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Congratulations! 🎉</h6>
                                        <p class="mb-0 small">You passed the <?= strtolower($typeLabel[$e['schedule_type']] ?? 'evaluation') ?>. Keep up the great work and continue forward to the next research stage!</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($e['result'] === 'failed'): ?>
                            <div class="bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded p-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill fs-2 me-3 text-danger"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Don't be discouraged 💪</h6>
                                        <p class="mb-0 small">Review the panel remarks carefully, revise and improve your research, coordinate with your instructor, and try again! You've got this!</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
