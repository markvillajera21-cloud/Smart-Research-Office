<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Summary report of Pre-Oral schedules, evaluations, and compliance tracking.</p>
        </div>
        <div class="btn-group btn-group-sm" role="group">
            <a href="<?= base_url('admin/monitoring') ?>?view=board" class="btn btn-light border"><i class="bi bi-kanban me-1"></i> Board</a>
            <a href="<?= base_url('admin/monitoring/completed') ?>" class="btn btn-light border"><i class="bi bi-check2-circle me-1"></i> Completed</a>
            <a href="<?= base_url('admin/monitoring/incomplete') ?>" class="btn btn-light border"><i class="bi bi-clock-history me-1"></i> Incomplete</a>
            <a href="<?= base_url('admin/monitoring/pre-oral-summary') ?>" class="btn btn-primary text-white border-primary"><i class="bi bi-bar-chart me-1"></i> Pre-Oral</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4">
                <p class="small text-muted mb-1">Total Scheduled (All Time)</p>
                <h2 class="mb-0 text-primary"><?= $preOralSummary['total_scheduled'] ?? 0 ?></h2>
                <p class="text-xs text-muted mt-2 mb-0">Completed: <span class="text-success fw-semibold"><?= $preOralSummary['total_completed'] ?? 0 ?></span></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 bg-success-subtle">
                <p class="small text-success mb-1">Passed</p>
                <h2 class="mb-0 text-success"><?= $evalStats['passed'] ?? 0 ?></h2>
                <p class="text-xs text-success mt-2 mb-0">
                    <?= ($evalStats['total'] ?? 0) > 0 ? round((($evalStats['passed'] ?? 0) / ($evalStats['total'] ?? 1)) * 100, 1) : 0 ?>% pass rate
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 bg-danger-subtle">
                <p class="small text-danger mb-1">Failed</p>
                <h2 class="mb-0 text-danger"><?= $evalStats['failed'] ?? 0 ?></h2>
                <p class="text-xs text-danger mt-2 mb-0">
                    <?= ($evalStats['total'] ?? 0) > 0 ? round((($evalStats['failed'] ?? 0) / ($evalStats['total'] ?? 1)) * 100, 1) : 0 ?>% failure rate
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 bg-info-subtle">
                <p class="small text-info mb-1">For Compliance</p>
                <h2 class="mb-0 text-info"><?= $evalStats['for_compliance'] ?? 0 ?></h2>
                <p class="text-xs text-info mt-2 mb-0">
                    Pending: <?= count($pendingCompliance ?? []) ?> | Overdue: <?= count($overdueCompliance ?? []) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- ========================================================
         Pre-Oral Research Titles List Section
    ========================================================= -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning-subtle border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-warning-emphasis">
                    <i class="bi bi-journal-bookmark-fill me-2"></i> Research Titles Scheduled for Pre-Oral Defense
                </h5>
                <div class="text-muted text-xs mt-1">
                    Live list of research titles scheduled for or undergoing Pre-Oral Defense, updated automatically from research and schedule records.
                </div>
            </div>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                <?= count($preOralTitles ?? []) ?> Research Title<?= count($preOralTitles ?? []) !== 1 ? 's' : '' ?>
            </span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($preOralTitles)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-25"></i>
                    <p class="mb-0">No research titles are currently scheduled for Pre-Oral Defense.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 40px;">#</th>
                                <th style="width: 22%;">Researcher / Author</th>
                                <th style="width: 35%;">Research Title</th>
                                <th style="width: 18%;">Course / Strand</th>
                                <th style="width: 15%;">Pre-Oral Date</th>
                                <th class="text-end pe-3" style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $statusBadge = [
                                'scheduled'   => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                'completed'   => 'bg-success-subtle text-success border border-success-subtle',
                                'cancelled'   => 'bg-danger-subtle text-danger border border-danger-subtle',
                                'rescheduled' => 'bg-info-subtle text-info border border-info-subtle',
                            ];
                            $statusIcon = [
                                'scheduled'   => 'bi-calendar-event',
                                'completed'   => 'bi-check-circle-fill',
                                'cancelled'   => 'bi-x-circle-fill',
                                'rescheduled' => 'bi-arrow-repeat',
                            ];
                            foreach ($preOralTitles as $idx => $t): 
                                $author = !empty($t['researcher_name']) ? $t['researcher_name'] : (!empty($t['student_name']) ? $t['student_name'] : 'N/A');
                                $researchTitle = !empty($t['researcher_approved_title']) 
                                    ? $t['researcher_approved_title'] 
                                    : (!empty($t['research_title']) ? $t['research_title'] : $t['schedule_title']);
                                
                                $strandCourseParts = array_filter([
                                    $t['strand_name'] ?? '',
                                    $t['course_name'] ?? ($t['strand_degree_program'] ?? '')
                                ]);
                                $courseStrand = !empty($strandCourseParts) ? implode(' / ', $strandCourseParts) : '—';
                                
                                $badgeCls = $statusBadge[$t['status']] ?? 'bg-secondary-subtle text-secondary';
                                $iconCls = $statusIcon[$t['status']] ?? 'bi-dash-circle';
                            ?>
                                <tr>
                                    <td class="ps-3 text-muted fw-semibold"><?= $idx + 1 ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($author) ?></div>
                                        <?php if (!empty($t['researcher_name']) && !empty($t['student_name']) && $t['researcher_name'] !== $t['student_name']): ?>
                                            <div class="text-xs text-muted">Student: <?= esc($t['student_name']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-wrap" style="line-height: 1.4;"><?= esc($researchTitle) ?></div>
                                        <?php if (!empty($t['schedule_title']) && $t['schedule_title'] !== $researchTitle): ?>
                                            <div class="text-xs text-muted mt-1"><i class="bi bi-tag me-1"></i>Schedule: <?= esc($t['schedule_title']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($courseStrand !== '—'): ?>
                                            <span class="badge bg-light text-dark border"><?= esc($courseStrand) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= date('M d, Y', strtotime($t['schedule_date'])) ?></div>
                                        <div class="text-muted text-xs"><i class="bi bi-clock me-1"></i><?= date('h:i A', strtotime($t['schedule_time'])) ?></div>
                                        <?php if (!empty($t['venue'])): ?>
                                            <div class="text-muted text-xs"><i class="bi bi-geo-alt me-1"></i><?= esc($t['venue']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="badge rounded-pill <?= $badgeCls ?>">
                                            <i class="bi <?= $iconCls ?> me-1"></i><?= ucfirst($t['status']) ?>
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

    <!-- Monthly Pre-Oral Schedules Card -->
    <div class="card p-4 mb-4">
        <h5 class="mb-3"><i class="bi bi-calendar4-week text-primary me-2"></i> Monthly Pre-Oral Schedules (<?= date('F Y') ?>)</h5>
        <?php if (count($monthlyPreOrals) === 0): ?>
            <p class="text-muted small mb-0">No Pre-Oral schedules this month yet.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Date</th><th>Title</th><th>Student</th><th>Time</th><th>Venue</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyPreOrals as $m): ?>
                        <tr>
                            <td class="fw-semibold small"><?= date('M d', strtotime($m['schedule_date'])) ?></td>
                            <td class="small"><?= esc($m['schedule_title']) ?></td>
                            <td class="small"><?= esc($m['student_name'] ?? 'N/A') ?></td>
                            <td class="small text-muted"><?= date('h:i A', strtotime($m['schedule_time'])) ?></td>
                            <td class="small text-muted"><?= esc($m['venue']) ?></td>
                            <td>
                                <?php $b = ['scheduled'=>'bg-primary-subtle text-primary','completed'=>'bg-success-subtle text-success','cancelled'=>'bg-danger-subtle text-danger','rescheduled'=>'bg-warning-subtle text-warning']; ?>
                                <span class="badge rounded-pill <?= $b[$m['status']] ?>"><?= ucfirst($m['status']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 border-warning h-100">
                <h6 class="mb-3 text-warning"><i class="bi bi-exclamation-circle-fill me-1"></i> PENDING COMPLIANCE</h6>
                <?php if (count($pendingCompliance) === 0): ?><p class="small text-muted mb-0">No pending compliance.</p><?php endif; ?>
                <?php foreach ($pendingCompliance as $c):
                    $diff = floor((strtotime($c['compliance_deadline']) - time()) / 86400);
                ?>
                    <div class="card bg-warning-subtle mb-2 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="small fw-semibold"><?= esc($c['schedule_type']) ?>: <?= esc($c['student_name']) ?></div>
                                <span class="badge bg-warning text-dark"><?= $diff ?>d left</span>
                            </div>
                            <div class="small mb-1"><?= esc($c['schedule_title']) ?></div>
                            <div class="text-muted text-xs mb-2">Deadline: <?= date('M d, Y', strtotime($c['compliance_deadline'])) ?></div>
                            <div class="text-xs text-dark mb-2 bg-white rounded p-2"><strong>Remarks:</strong> <?= esc($c['remarks']) ?></div>
                            <a href="<?= base_url('admin/evaluations/mark-compliance/' . $c['id']) ?>" class="btn btn-light border btn-sm text-success">
                                <i class="bi bi-check2 me-1"></i> Mark Submitted
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 border-danger h-100">
                <h6 class="mb-3 text-danger"><i class="bi bi-exclamation-octagon-fill me-1"></i> OVERDUE COMPLIANCE</h6>
                <?php if (count($overdueCompliance) === 0): ?><p class="small text-muted mb-0">No overdue compliance. Great work!</p><?php endif; ?>
                <?php foreach ($overdueCompliance as $c):
                    $diff = abs(floor((time() - strtotime($c['compliance_deadline'])) / 86400));
                ?>
                    <div class="card bg-danger-subtle mb-2 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="small fw-semibold"><?= esc($c['schedule_type']) ?>: <?= esc($c['student_name']) ?></div>
                                <span class="badge bg-danger text-white"><?= $diff ?>d OVERDUE</span>
                            </div>
                            <div class="small mb-1"><?= esc($c['schedule_title']) ?></div>
                            <div class="text-xs text-danger mb-2">Deadline: <?= date('M d, Y', strtotime($c['compliance_deadline'])) ?></div>
                            <div class="text-xs text-dark mb-2 bg-white rounded p-2"><strong>Remarks:</strong> <?= esc($c['remarks']) ?></div>
                            <a href="<?= base_url('admin/evaluations/mark-compliance/' . $c['id']) ?>" class="btn btn-light border btn-sm text-success">
                                <i class="bi bi-check2 me-1"></i> Mark Submitted
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
