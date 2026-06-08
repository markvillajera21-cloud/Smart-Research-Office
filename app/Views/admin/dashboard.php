<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(30, 58, 138, 0.2);
    }
    .action-btn {
        transition: all 0.3s ease;
    }
    .action-btn:hover {
        transform: scale(1.05);
    }
    .analytics-chart {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 12px;
        padding: 20px;
    }
    .calendar-day {
        transition: all 0.2s ease;
        cursor: pointer;
        border-radius: 8px;
    }
    .calendar-day:hover {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
    }
    .calendar-today {
        background: linear-gradient(135deg, #1e3a8a, #1e40af);
        color: white;
    }
</style>

<!-- Dashboard Header with Add User Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3" style="color: #1e3a8a; font-weight: 800;">Dashboard</h1>
    <?php if (session()->get('role') === 'admin'): ?>
    <a href="<?= base_url('admin/users/create') ?>" class="action-btn btn btn-primary fw-bold px-4">
        <i class="bi bi-person-plus-fill me-2"></i> Add User
    </a>
    <?php endif; ?>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Actions -->
    <?php if (session()->get('role') === 'admin'): ?>
    <div class="col-12 col-md-3">
        <div class="card h-100 p-4">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 800;">Quick Actions</h5>
            <div class="d-grid gap-3">
                <a href="<?= base_url('admin/uploads') ?>" class="action-btn btn btn-warning fw-bold py-3 w-100" style="color: #1e3a8a;">
                    <i class="bi bi-cloud-upload-fill me-2"></i> Upload
                </a>
                <a href="#" class="action-btn btn btn-info fw-bold py-3 w-100">
                    <i class="bi bi-calendar-check-fill me-2"></i> Appointment
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stat Cards (Grammarian, Statistician, Adviser, Total Users) -->
    <div class="col-12 <?php echo session()->get('role') === 'admin' ? 'col-md-9' : 'col-md-12' ?>">
        <div class="row g-3">
            <div class="col-12 col-sm-3">
                <div class="stat-card card p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #fef9c3, #facc15);">
                            <i class="bi bi-people fs-3" style="color: #1e3a8a;"></i>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1e3a8a;">Grammarian</h5>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="stat-card card p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                            <i class="bi bi-bar-chart fs-3" style="color: #1e3a8a;"></i>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1e3a8a;">Statistician</h5>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="stat-card card p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                            <i class="bi bi-person-badge fs-3" style="color: #1e3a8a;"></i>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1e3a8a;">Adviser</h5>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <a href="<?= base_url('admin/users') ?>" class="text-decoration-none">
                    <div class="stat-card card p-4">
                        <div class="d-flex flex-column align-items-center text-center">
                            <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #fef9c3, #facc15);">
                                <i class="bi bi-people-fill fs-3" style="color: #1e3a8a;"></i>
                            </div>
                            <h5 class="fw-bold mb-1" style="color: #1e3a8a;">Total Users</h5>
                            <h2 class="mb-0" style="color: #1e3a8a; font-weight: 800;">15</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Analytics Section -->
    <div class="col-12 col-xl-8">
        <div class="card h-100 p-4">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 800;">Analytics</h5>
            
            <div class="analytics-chart mb-4">
                <h6 class="mb-3" style="color: #1e40af; font-weight: 700;">Research Activity</h6>
                <div class="d-flex align-items-end gap-2" style="height: 200px;">
                    <div class="flex-1 d-flex flex-column align-items-center">
                        <div style="width: 100%; height: 80%; background: linear-gradient(to top, #1e3a8a, #3b82f6); border-radius: 8px 8px 0 0;"></div>
                        <small class="mt-2 text-muted">Mon</small>
                    </div>
                    <div class="flex-1 d-flex flex-column align-items-center">
                        <div style="width: 100%; height: 60%; background: linear-gradient(to top, #1e40af, #60a5fa); border-radius: 8px 8px 0 0;"></div>
                        <small class="mt-2 text-muted">Tue</small>
                    </div>
                    <div class="flex-1 d-flex flex-column align-items-center">
                        <div style="width: 100%; height: 90%; background: linear-gradient(to top, #1e3a8a, #3b82f6); border-radius: 8px 8px 0 0;"></div>
                        <small class="mt-2 text-muted">Wed</small>
                    </div>
                    <div class="flex-1 d-flex flex-column align-items-center">
                        <div style="width: 100%; height: 70%; background: linear-gradient(to top, #1e40af, #60a5fa); border-radius: 8px 8px 0 0;"></div>
                        <small class="mt-2 text-muted">Thu</small>
                    </div>
                    <div class="flex-1 d-flex flex-column align-items-center">
                        <div style="width: 100%; height: 100%; background: linear-gradient(to top, #1e3a8a, #3b82f6); border-radius: 8px 8px 0 0;"></div>
                        <small class="mt-2 text-muted">Fri</small>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-4">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 12px; height: 12px; background: #1e3a8a; border-radius: 3px;"></div>
                        <small class="text-muted">Research Submitted</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 12px; height: 12px; background: #facc15; border-radius: 3px;"></div>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Section -->
    <div class="col-12 col-xl-4">
        <div class="card h-100 p-4">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 800;">Calendar</h5>
            
            <div class="text-center mb-3">
                <h6 class="fw-bold mb-3" style="color: #1e3a8a;"><?= date('F Y') ?></h6>
            </div>
            
            <!-- Calendar Header -->
            <div class="row g-1 mb-2">
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Sun</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Mon</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Tue</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Wed</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Thu</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Fri</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.75rem;">Sat</div>
            </div>
            
            <!-- Calendar Days -->
            <div class="row g-1">
                <?php
                $firstDay = date('w', strtotime(date('Y-m-01')));
                $daysInMonth = date('t');
                $today = date('j');
                
                for ($i = 0; $i < $firstDay; $i++) {
                    echo '<div class="col"></div>';
                }
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $isToday = $day == $today ? 'calendar-today' : '';
                    echo <<<HTML
                    <div class="col">
                        <div class="calendar-day p-2 text-center {$isToday}">
                            <span class="fw-semibold">{$day}</span>
                        </div>
                    </div>
HTML;
                }
                ?>
            </div>
            
            <!-- Storage Info -->
            <div class="mt-4 pt-4">
                <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <p class="mb-0" style="color: #1e3a8a; font-weight: 600;">Storage Used</p>
                    <div class="progress mt-3 mb-2" style="height: 10px; background-color: #bfdbfe; border-radius: 9999px;">
                        <div class="progress-bar rounded-3" style="width: 65%; background: linear-gradient(90deg, #1e3a8a, #facc15);"></div>
                    </div>
                    <small style="color: #1e40af; font-weight: 600;">12.5 GB of 20 GB</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
