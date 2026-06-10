<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --bg-dark: #0f172a;
        --bg-card: #1e293b;
        --bg-card-hover: #334155;
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --accent-blue: #3b82f6;
        --accent-green: #22c55e;
        --accent-yellow: #eab308;
        --accent-red: #ef4444;
        --accent-purple: #8b5cf6;
    }
    
    body {
        background: var(--bg-dark);
    }
    
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
    }
    
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 16px;
        border-bottom: 1px solid #334155;
    }
    
    .dashboard-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .dashboard-header h1 span {
        color: var(--accent-blue);
    }
    
    .header-actions {
        display: flex;
        gap: 12px;
    }
    
    .btn-action {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: var(--accent-blue);
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
    .btn-secondary {
        background: var(--bg-card);
        color: var(--text-primary);
        border: 1px solid #475569;
    }
    
    .btn-secondary:hover {
        background: var(--bg-card-hover);
    }
    
    .main-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
    }
    
    /* Left Column - Role Cards */
    .left-column {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .role-card {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #334155;
        transition: all 0.2s;
    }
    
    .role-card:hover {
        background: var(--bg-card-hover);
        transform: translateY(-2px);
    }
    
    .role-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .role-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .role-icon.teacher {
        background: rgba(59, 130, 246, 0.2);
        color: var(--accent-blue);
    }
    
    .role-icon.grammarian {
        background: rgba(34, 197, 94, 0.2);
        color: var(--accent-green);
    }
    
    .role-icon.statistician {
        background: rgba(234, 179, 8, 0.2);
        color: var(--accent-yellow);
    }
    
    .role-icon.adviser {
        background: rgba(239, 68, 68, 0.2);
        color: var(--accent-red);
    }
    
    .role-info h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 2px 0;
    }
    
    .role-info p {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }
    
    .role-stats {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    
    .stat-pill {
        background: rgba(255,255,255,0.05);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-secondary);
    }
    
    .stat-pill.active {
        background: rgba(34, 197, 94, 0.15);
        color: var(--accent-green);
    }
    
    /* Right Column */
    .right-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    
    /* Research Output Section */
    .research-output {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #334155;
    }
    
    .research-output h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 16px 0;
    }
    
    .output-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .output-tab {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .output-tab.active {
        background: var(--accent-blue);
        color: white;
    }
    
    .output-tab:not(.active) {
        background: rgba(255,255,255,0.05);
        color: var(--text-secondary);
    }
    
    .output-grid {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .output-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .output-dot.pending { background: var(--accent-yellow); }
    .output-dot.approved { background: var(--accent-green); }
    .output-dot.rejected { background: var(--accent-red); }
    
    .output-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-right: 16px;
    }
    
    /* Bottom Section - Chart & Calendar */
    .bottom-section {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
    }
    
    /* Status Overview */
    .status-overview {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #334155;
    }
    
    .status-overview h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 20px 0;
    }
    
    .chart-container {
        display: flex;
        align-items: center;
        gap: 32px;
    }
    
    .donut-chart {
        width: 140px;
        height: 140px;
        position: relative;
    }
    
    .donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    
    .donut-center .number {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-primary);
    }
    
    .donut-center .label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    
    .chart-legend {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    
    .legend-dot.completed { background: var(--accent-green); }
    .legend-dot.ongoing { background: var(--accent-blue); }
    .legend-dot.pending { background: var(--accent-yellow); }
    
    .legend-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    
    .legend-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-left: auto;
    }
    
    /* Calendar */
    .calendar-section {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #334155;
    }
    
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    
    .calendar-header h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    
    .calendar-nav {
        display: flex;
        gap: 8px;
    }
    
    .calendar-nav button {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: none;
        background: rgba(255,255,255,0.05);
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .calendar-nav button:hover {
        background: var(--bg-card-hover);
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }
    
    .calendar-day-name {
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-align: center;
        font-weight: 600;
        padding: 6px 0;
    }
    
    .calendar-day {
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-align: center;
        padding: 8px 0;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .calendar-day:hover {
        background: var(--bg-card-hover);
    }
    
    .calendar-day.today {
        background: var(--accent-blue);
        color: white;
        font-weight: 700;
    }
    
    .calendar-day.event {
        background: var(--accent-purple);
        color: white;
        font-weight: 700;
    }
    
    .calendar-footer {
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #334155;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .calendar-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent-purple);
    }
    
    .calendar-footer p {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }
    
    /* Analytics Section */
    .analytics-section {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #334155;
    }
    
    .analytics-section h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 20px 0;
    }
    
    .analytics-metrics {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .metric-card {
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 14px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .metric-card .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .metric-card .label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }
    
    .metric-card .change {
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .metric-card .change.up {
        color: var(--accent-green);
    }
    
    .metric-card .change.down {
        color: var(--accent-red);
    }
    
    .analytics-charts {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 16px;
    }
    
    .line-chart-box {
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 16px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .line-chart-box h3,
    .bar-chart-box h3 {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin: 0 0 12px 0;
        font-weight: 600;
    }
    
    .bar-chart-box {
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 16px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .department-stats {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .dept-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .dept-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
    }
    
    .dept-item-header span:first-child {
        color: var(--text-primary);
        font-weight: 600;
    }
    
    .dept-item-header span:last-child {
        color: var(--text-secondary);
        font-weight: 600;
    }
    
    .dept-bar {
        height: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
        overflow: hidden;
    }
    
    .dept-bar-fill {
        height: 100%;
        border-radius: 4px;
        background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>ROARMS <span>Dashboard</span></h1>
        <div class="header-actions">
            <?php if (session()->get('role') === 'admin'): ?>
                <button class="btn-action btn-secondary">
                    ➕ Add Researcher
                </button>
                <button class="btn-action btn-primary">
                    ➕ Add Research Teacher
                </button>
            <?php else: ?>
                <button class="btn-action btn-secondary" disabled style="opacity: 0.5; cursor: not-allowed;">
                    ➕ Add Researcher
                </button>
                <button class="btn-action btn-primary" disabled style="opacity: 0.5; cursor: not-allowed;">
                    ➕ Add Research Teacher
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="main-grid">
        <!-- Left Column - Role Cards -->
        <div class="left-column">
            <div class="role-card">
                <div class="role-card-header">
                    <div class="role-icon teacher">👨‍🏫</div>
                    <div class="role-info">
                        <h3>Research Teacher</h3>
                        <p>12 Active • 32 Total</p>
                    </div>
                </div>
                <div class="role-stats">
                    <span class="stat-pill active">Active</span>
                    <span class="stat-pill">12 On Duty</span>
                </div>
            </div>
            
            <div class="role-card">
                <div class="role-card-header">
                    <div class="role-icon grammarian">📝</div>
                    <div class="role-info">
                        <h3>Grammarian</h3>
                        <p>8 Active • 15 Total</p>
                    </div>
                </div>
                <div class="role-stats">
                    <span class="stat-pill active">Active</span>
                    <span class="stat-pill">8 Available</span>
                </div>
            </div>
            
            <div class="role-card">
                <div class="role-card-header">
                    <div class="role-icon statistician">📊</div>
                    <div class="role-info">
                        <h3>Statistician</h3>
                        <p>6 Active • 10 Total</p>
                    </div>
                </div>
                <div class="role-stats">
                    <span class="stat-pill active">Active</span>
                    <span class="stat-pill">6 Analyzing</span>
                </div>
            </div>
            
            <div class="role-card">
                <div class="role-card-header">
                    <div class="role-icon adviser">💬</div>
                    <div class="role-info">
                        <h3>Adviser</h3>
                        <p>10 Active • 25 Total</p>
                    </div>
                </div>
                <div class="role-stats">
                    <span class="stat-pill">2 Idle</span>
                    <span class="stat-pill active">10 Consulting</span>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="right-column">
            <!-- Research Output Section -->
            <div class="research-output">
                <h2>Research Output (2026)</h2>
                <div class="output-tabs">
                    <div class="output-tab active">All Research</div>
                    <div class="output-tab">High School</div>
                    <div class="output-tab">College</div>
                    <div class="output-tab">S.Y 2025-2026</div>
                </div>
                <div class="output-grid">
                    <div class="output-dot pending"></div>
                    <div class="output-label">Pending</div>
                    <div class="output-dot approved"></div>
                    <div class="output-label">Approved</div>
                    <div class="output-dot rejected"></div>
                    <div class="output-label">Rejected</div>
                </div>
            </div>
            
            <!-- Analytics Section -->
            <div class="analytics-section">
                <h2>📈 Analytics Overview</h2>
                
                <div class="analytics-metrics">
                    <div class="metric-card">
                        <div class="value">120</div>
                        <div class="label">Total Research</div>
                        <div class="change up">+12% from last month</div>
                    </div>
                    <div class="metric-card">
                        <div class="value">85</div>
                        <div class="label">Approved</div>
                        <div class="change up">+8% from last month</div>
                    </div>
                    <div class="metric-card">
                        <div class="value">68</div>
                        <div class="label">Published</div>
                        <div class="change up">+15% from last month</div>
                    </div>
                    <div class="metric-card">
                        <div class="value">4.2</div>
                        <div class="label">Avg. Rating</div>
                        <div class="change up">+0.3 from last month</div>
                    </div>
                </div>
                
                <div class="analytics-charts">
                    <div class="line-chart-box">
                        <h3>Research Trends (Jan - Dec 2026)</h3>
                        <canvas id="trendsLineChart"></canvas>
                    </div>
                    <div class="bar-chart-box">
                        <h3>Department Performance</h3>
                        <div class="department-stats">
                            <div class="dept-item">
                                <div class="dept-item-header">
                                    <span>STEM</span>
                                    <span>45</span>
                                </div>
                                <div class="dept-bar">
                                    <div class="dept-bar-fill" style="width: 90%;"></div>
                                </div>
                            </div>
                            <div class="dept-item">
                                <div class="dept-item-header">
                                    <span>ABM</span>
                                    <span>32</span>
                                </div>
                                <div class="dept-bar">
                                    <div class="dept-bar-fill" style="width: 70%;"></div>
                                </div>
                            </div>
                            <div class="dept-item">
                                <div class="dept-item-header">
                                    <span>HUMSS</span>
                                    <span>28</span>
                                </div>
                                <div class="dept-bar">
                                    <div class="dept-bar-fill" style="width: 60%;"></div>
                                </div>
                            </div>
                            <div class="dept-item">
                                <div class="dept-item-header">
                                    <span>TVL</span>
                                    <span>15</span>
                                </div>
                                <div class="dept-bar">
                                    <div class="dept-bar-fill" style="width: 35%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Section -->
            <div class="bottom-section">
                <!-- Status Overview -->
                <div class="status-overview">
                    <h2>Status Overview</h2>
                    <div class="chart-container">
                        <div class="donut-chart">
                            <canvas id="donutChart"></canvas>
                            <div class="donut-center">
                                <div class="number">43</div>
                                <div class="label">Total</div>
                            </div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-dot completed"></div>
                                <div class="legend-label">Completed</div>
                                <div class="legend-value">25</div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot ongoing"></div>
                                <div class="legend-label">Ongoing</div>
                                <div class="legend-value">12</div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot pending"></div>
                                <div class="legend-label">Pending</div>
                                <div class="legend-value">6</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Calendar -->
                <div class="calendar-section">
                    <div class="calendar-header">
                        <h2>June 2026</h2>
                        <div class="calendar-nav">
                            <button>‹</button>
                            <button>›</button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-name">S</div>
                        <div class="calendar-day-name">M</div>
                        <div class="calendar-day-name">T</div>
                        <div class="calendar-day-name">W</div>
                        <div class="calendar-day-name">T</div>
                        <div class="calendar-day-name">F</div>
                        <div class="calendar-day-name">S</div>
                        
                        <!-- Calendar Days -->
                        <div class="calendar-day"></div>
                        <div class="calendar-day">1</div>
                        <div class="calendar-day">2</div>
                        <div class="calendar-day">3</div>
                        <div class="calendar-day">4</div>
                        <div class="calendar-day">5</div>
                        <div class="calendar-day">6</div>
                        
                        <div class="calendar-day">7</div>
                        <div class="calendar-day">8</div>
                        <div class="calendar-day">9</div>
                        <div class="calendar-day">10</div>
                        <div class="calendar-day">11</div>
                        <div class="calendar-day">12</div>
                        <div class="calendar-day">13</div>
                        
                        <div class="calendar-day">14</div>
                        <div class="calendar-day">15</div>
                        <div class="calendar-day">16</div>
                        <div class="calendar-day">17</div>
                        <div class="calendar-day">18</div>
                        <div class="calendar-day">19</div>
                        <div class="calendar-day">20</div>
                        
                        <div class="calendar-day">21</div>
                        <div class="calendar-day">22</div>
                        <div class="calendar-day">23</div>
                        <div class="calendar-day today">24</div>
                        <div class="calendar-day">25</div>
                        <div class="calendar-day">26</div>
                        <div class="calendar-day">27</div>
                        
                        <div class="calendar-day">28</div>
                        <div class="calendar-day event">29</div>
                        <div class="calendar-day">30</div>
                        <div class="calendar-day"></div>
                        <div class="calendar-day"></div>
                        <div class="calendar-day"></div>
                        <div class="calendar-day"></div>
                    </div>
                    <div class="calendar-footer">
                        <div class="calendar-dot"></div>
                        <p>June 29 - S.Y. 2026-2027</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Donut Chart
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [25, 12, 6],
                backgroundColor: [
                    '#22c55e',
                    '#3b82f6',
                    '#eab308'
                ],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
    
    // Trends Line Chart
    const trendsLineCtx = document.getElementById('trendsLineChart').getContext('2d');
    new Chart(trendsLineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Submitted',
                    data: [8, 12, 10, 15, 18, 16, 20, 19, 22, 25, 23, 28],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Approved',
                    data: [6, 10, 8, 12, 15, 13, 17, 16, 19, 21, 20, 24],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10 }
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>
