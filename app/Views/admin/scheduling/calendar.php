<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Month view of all research defense activities, hearings, and schedules.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form method="get" class="d-flex gap-2 align-items-center">
                <select name="month" class="form-select form-select-sm">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="form-select form-select-sm">
                    <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button class="btn btn-outline-primary btn-sm" type="submit">Go</button>
            </form>
            <a href="<?= base_url('admin/scheduling') ?>" class="btn btn-light border btn-sm">
                <i class="bi bi-list-ul me-1"></i> List View
            </a>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModalRedirect">
                <i class="bi bi-plus-lg me-1"></i> Add Schedule
            </button>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 text-primary fw-bold"><?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></h5>
            <div class="d-flex gap-3 small">
                <span><span class="badge bg-info-subtle text-info rounded-pill">●</span> Title Hearing</span>
                <span><span class="badge bg-warning-subtle text-warning rounded-pill">●</span> Pre-Oral</span>
                <span><span class="badge bg-success-subtle text-success rounded-pill">●</span> Final Defense</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered border-1" style="table-layout:fixed;">
                <thead>
                    <tr class="table-light">
                        <th class="text-center p-2 small" style="width:14.28%">Sunday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Monday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Tuesday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Wednesday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Thursday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Friday</th>
                        <th class="text-center p-2 small" style="width:14.28%">Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $firstDay = date('w', mktime(0, 0, 0, $month, 1, $year));
                    $today = date('Y-m-d');
                    $dayCounter = 1;
                    $printed = false;
                    for ($row = 0; $row < 6; $row++): ?>
                        <tr>
                            <?php for ($col = 0; $col < 7; $col++): ?>
                                <td class="p-1 align-top small" style="min-height: 110px;">
                                    <?php
                                    if (($row == 0 && $col < $firstDay) || $dayCounter > $daysInMonth):
                                        echo '&nbsp;';
                                    else:
                                        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $dayCounter);
                                        $isToday = ($dateStr === $today);
                                        $bg = $isToday ? 'bg-primary-subtle' : '';
                                    ?>
                                        <div class="d-flex justify-content-between px-1">
                                            <span class="fw-semibold small <?= $isToday ? 'text-primary' : '' ?>"><?= $dayCounter ?></span>
                                            <?php if (isset($calendarEvents[$dayCounter]) && count($calendarEvents[$dayCounter]) > 0): ?>
                                                <span class="badge bg-light text-dark rounded-pill"><?= count($calendarEvents[$dayCounter]) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="<?= $bg ?> rounded p-1">
                                            <?php if (isset($calendarEvents[$dayCounter])): ?>
                                                <?php foreach (array_slice($calendarEvents[$dayCounter], 0, 3) as $ev):
                                                    $typeBadge = [
                                                        'title_hearing' => 'bg-info-subtle text-info',
                                                        'pre_oral' => 'bg-warning-subtle text-warning',
                                                        'final_defense' => 'bg-success-subtle text-success',
                                                    ];
                                                ?>
                                                    <div class="btn p-1 mb-1 w-100 text-start <?= $typeBadge[$ev['schedule_type']] ?? 'bg-secondary' ?> rounded-1 view-event-btn"
                                                         data-id="<?= $ev['id'] ?>"
                                                         style="font-size: 10.5px; cursor: pointer; border: none;">
                                                        <div class="fw-bold" style="font-size: 10px;"><?= date('h:iA', strtotime($ev['schedule_time'])) ?></div>
                                                        <div class="text-truncate" style="max-width: 120px;"><?= esc($ev['schedule_title']) ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <?php if (count($calendarEvents[$dayCounter]) > 3): ?>
                                                    <div class="text-center small fw-semibold text-muted">+<?= count($calendarEvents[$dayCounter]) - 3 ?> more</div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php $dayCounter++;
                                    endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php
                        if ($dayCounter > $daysInMonth) break;
                    endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Event Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4" id="viewEventBody"></div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.view-event-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        fetch('<?= base_url('admin/scheduling/ajax-event/') ?>' + id, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(r => r.json())
            .then(data => {
                if (!data) return;
                const typeLabel = {title_hearing: 'Title Hearing', pre_oral: 'Pre-Oral Defense', final_defense: 'Final Defense'};
                document.getElementById('viewEventBody').innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-0">${data.schedule_title || 'Schedule'}</h5>
                            <span class="badge bg-info-subtle text-info mt-1">${typeLabel[data.schedule_type] || data.schedule_type}</span>
                        </div>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="row g-2 small mb-4">
                        <div class="col-6"><div class="text-muted">Date</div><div class="fw-semibold">${new Date(data.schedule_date).toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'})}</div></div>
                        <div class="col-6"><div class="text-muted">Time</div><div class="fw-semibold">${data.schedule_time || 'N/A'}</div></div>
                        <div class="col-12"><div class="text-muted">Venue</div><div class="fw-semibold">${data.venue || 'N/A'}</div></div>
                        <div class="col-6"><div class="text-muted">Student</div><div class="fw-semibold">${data.student_name || 'N/A'}</div></div>
                        <div class="col-6"><div class="text-muted">Instructor</div><div class="fw-semibold">${data.instructor_name || 'N/A'}</div></div>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= base_url('admin/scheduling/edit/') ?>${data.id}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
                        <button class="btn btn-light border btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('viewEventModal')).show();
            });
    });
});

document.getElementById('addScheduleModalRedirect').addEventListener('click', () => {
    window.location.href = '<?= base_url('admin/scheduling') ?>';
});
</script>
<?= $this->endSection() ?>
