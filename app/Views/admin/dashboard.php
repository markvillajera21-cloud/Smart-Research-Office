<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .role-card {
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        color: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        transition: transform 0.2s;
    }
    
    .role-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    
    .role-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.25);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-action {
        border-radius: 20px;
        padding: 6px 24px;
        font-weight: 600;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 3px 16px rgba(0,0,0,0.06);
    }
    
    .calendar-container {
        background: white;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 3px 16px rgba(0,0,0,0.06);
    }
</style>

<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <img src="<?= base_url('logo.png') ?>" alt="ROARMS" style="height: 50px;" onerror="this.style.display='none'">
        <h1 class="h3 fw-bold text-dark">ROARMS <span class="text-muted fw-normal">Dashboard</span></h1>
    </div>
    <div class="d-flex gap-2">
        <?php if (session()->get('role') === 'admin'): ?>
        <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-warning btn-action text-dark">
            Add Research
        </a>
        <a href="<?= base_url('admin/uploads') ?>" class="btn btn-secondary btn-action">
            Upload
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Role Cards -->
<div class="row mb-3">
    <div class="col-12 mb-2">
        <h5 class="h6 text-muted fw-semibold">Role</h5>
    </div>
    <div class="col-md-4">
        <div class="role-card">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="role-icon">
                    <i class="bi bi-pen-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="h6 mb-0 fw-bold">Grammarian</h6>
                </div>
            </div>
            <div class="mb-1 small">Status: <span class="fw-bold">Active</span></div>
            <div class="mb-1 small">Responsibility: Document Review</div>
            <div class="text-muted small">• 9:30 AM Oct 26, 2025</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="role-card">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="role-icon">
                    <i class="bi bi-bar-chart-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="h6 mb-0 fw-bold">Statistician</h6>
                </div>
            </div>
            <div class="mb-1 small">Status: <span class="fw-bold">Active</span></div>
            <div class="mb-1 small">Responsibility: Data Analysis</div>
            <div class="text-muted small">• 8:45 AM Oct 26, 2025</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="role-card">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="role-icon">
                    <i class="bi bi-person-check-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="h6 mb-0 fw-bold">Adviser</h6>
                </div>
            </div>
            <div class="mb-1 small">Status: <span class="fw-bold">Idle</span></div>
            <div class="mb-1 small">Responsibility: Consultation</div>
            <div class="text-muted small">• 4:15 PM Oct 25, 2025</div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="row mb-3">
    <div class="col-12 mb-2">
        <h5 class="h6 text-muted fw-semibold">Analytics</h5>
    </div>
    <div class="col-xl-12">
        <div class="chart-container">
            <h6 class="h6 mb-2 fw-semibold text-primary">Research Trends Over Time</h6>
            <div class="mb-2 text-end">
                <select class="form-select w-auto d-inline-block border-2" style="font-size: 0.9rem;">
                    <option>Last 7 Days</option>
                    <option selected>Last 30 Days</option>
                    <option>Last 90 Days</option>
                </select>
            </div>
            <canvas id="trendsChart" height="180"></canvas>
        </div>
    </div>
</div>

<!-- Publication and Calendar Section -->
<div class="row">
    <div class="col-xl-6 mb-3 mb-xl-0">
        <div class="chart-container h-100">
            <h6 class="h6 mb-2 fw-semibold text-primary">Publication Statistics</h6>
            <canvas id="publicationChart" height="160"></canvas>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="calendar-container">
            <h6 class="h6 mb-2 fw-semibold text-primary">Calendar</h6>
            <div class="text-center mb-2">
                <h5 class="fw-bold text-dark"><?= date('F Y') ?></h5>
            </div>
            <!-- Calendar Header -->
            <div class="row g-1 mb-2">
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Sun</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Mon</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Tue</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Wed</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Thu</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Fri</div>
                <div class="col text-center fw-bold text-muted" style="font-size: 0.8rem;">Sat</div>
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
                    $isToday = $day == $today ? 'bg-primary text-white' : '';
                    $todayStyle = $day == $today ? 'fw-bold' : '';
                    echo <<<HTML
                    <div class="col">
                        <div class="text-center p-1 rounded {$isToday} {$todayStyle}" style="font-size: 0.9rem;">
                            {$day}
                        </div>
                    </div>
HTML;
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Research Trends Chart
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    const trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Research Submitted',
                    data: [15, 25, 20, 30, 35, 25, 40, 30, 45, 35, 40, 45],
                    borderColor: '#1e3a8a',
                    backgroundColor: 'rgba(30, 58, 138, 0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3
                },
                {
                    label: 'Approved',
                    data: [10, 20, 18, 25, 30, 22, 35, 28, 40, 32, 38, 42],
                    borderColor: '#fbbf24',
                    backgroundColor: 'rgba(251, 191, 36, 0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        padding: 15
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 50,
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Publication Statistics Chart
    const pubCtx = document.getElementById('publicationChart').getContext('2d');
    const pubChart = new Chart(pubCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [
                {
                    label: 'High School',
                    data: [12, 15, 10, 18],
                    backgroundColor: '#1e3a8a',
                    borderRadius: 6,
                    borderWidth: 0
                },
                {
                    label: 'College',
                    data: [8, 12, 14, 10],
                    backgroundColor: '#60a5fa',
                    borderRadius: 6,
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        padding: 15
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>
