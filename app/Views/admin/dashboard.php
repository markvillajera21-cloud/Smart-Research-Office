<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary-blue: #3b82f6;
        --primary-dark: #1e40af;
        --success-green: #10b981;
        --warning-orange: #f59e0b;
        --danger-red: #ef4444;
        --light-bg: #f3f4f6;
        --card-bg: #ffffff;
        --border-gray: #e5e7eb;
        --text-dark: #1f2937;
        --text-light: #6b7280;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body, html {
        background: var(--light-bg);
        color: var(--text-dark);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    .navbar {
        display: none !important;
    }
    
    main {
        background: var(--light-bg) !important;
    }
    
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
        background: var(--light-bg);
    }
    
    /* Header */
    .dashboard-header {
        background: var(--card-bg);
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .dashboard-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }
    
    .dashboard-header h1 span {
        color: var(--primary-blue);
    }
    
    .header-actions {
        display: flex;
        gap: 12px;
    }
    
    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-secondary {
        background: var(--light-bg);
        color: var(--text-dark);
        border: 1px solid var(--border-gray);
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
    }
    
    .btn-primary,
    a.btn-primary {
        background: var(--primary-blue);
        color: white;
        text-decoration: none;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
    /* Top Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1024px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }
    
    .stat-box {
        background: var(--card-bg);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        position: relative;
        cursor: pointer;
    }
    
    .stat-box:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .stat-box a {
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
    }
    
    /* Small Tooltip Popup */
    .stat-report {
        display: none;
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%) translateY(100%);
        background: var(--text-dark);
        color: white;
        padding: 12px 15px;
        border-radius: 6px;
        font-size: 11px;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
        min-width: 180px;
    }
    
    .stat-box:hover .stat-report {
        display: block;
        animation: fadeIn 0.2s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(95%);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(100%);
        }
    }
    
    .report-title {
        font-weight: 700;
        font-size: 11px;
        margin-bottom: 6px;
        display: block;
    }
    
    .report-items {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .report-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 10px;
        gap: 8px;
    }
    
    .report-label {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .report-value {
        font-weight: 700;
        color: #60a5fa;
    }
    
    .stat-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: rgba(59, 130, 246, 0.1);
    }
    
    .stat-icon.green {
        background: rgba(16, 185, 129, 0.1);
    }
    
    .stat-icon.orange {
        background: rgba(245, 158, 11, 0.1);
    }
    
    .stat-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-details {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 10px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    .stat-description {
        font-size: 12px;
        color: var(--text-light);
    }
    
    /* Main Content Grid */
    .main-content {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1200px) {
        .main-content {
            grid-template-columns: 1fr;
        }
    }
    
    .content-card {
        background: var(--card-bg);
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .content-card h2 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .chart-container {
        height: 250px;
        position: relative;
        margin-bottom: 20px;
    }
    
    /* Calendar */
    .calendar-wrapper {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .calendar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .calendar-nav button {
        background: var(--light-bg);
        border: 1px solid var(--border-gray);
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }
    
    .calendar-nav button:hover {
        background: #f9fafb;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }
    
    .calendar-day-header {
        text-align: center;
        font-weight: 600;
        font-size: 11px;
        color: var(--text-light);
        padding: 8px 0;
        text-transform: uppercase;
    }
    
    .calendar-day {
        text-align: center;
        padding: 8px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--light-bg);
    }
    
    .calendar-day:hover {
        background: #e5e7eb;
    }
    
    .calendar-day.today {
        background: var(--primary-blue);
        color: white;
        font-weight: 600;
    }
    
    .calendar-day.event {
        background: var(--primary-blue);
        color: white;
    }
    
    .calendar-day.empty {
        background: transparent;
        cursor: default;
    }
    
    .calendar-footer {
        padding-top: 15px;
        border-top: 1px solid var(--border-gray);
        font-size: 12px;
        color: var(--text-light);
    }
    
    .calendar-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .calendar-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }
    
    .calendar-modal-overlay.show {
        display: flex;
    }
    
    .calendar-modal {
        width: min(100%, 420px);
        background: white;
        border-radius: 12px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        padding: 22px;
    }
    
    .calendar-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    
    .calendar-modal-header h3 {
        margin: 0;
        font-size: 20px;
        color: var(--text-dark);
    }
    
    .calendar-modal-close {
        border: none;
        background: transparent;
        font-size: 26px;
        line-height: 1;
        cursor: pointer;
        color: var(--text-light);
    }
    
    .schedule-form {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    
    .schedule-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .schedule-field label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .schedule-field input,
    .schedule-field textarea {
        width: 100%;
        border: 1px solid var(--border-gray);
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        background: #fff;
        color: var(--text-dark);
    }
    
    .schedule-field textarea {
        resize: vertical;
        min-height: 80px;
    }
    
    .schedule-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 8px;
    }
    
    .schedule-cancel,
    .schedule-submit {
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }
    
    .schedule-cancel {
        background: var(--light-bg);
        color: var(--text-dark);
        border: 1px solid var(--border-gray);
    }
    
    .schedule-submit {
        background: var(--primary-blue);
        color: white;
    }
    
    .schedule-submit:hover {
        background: var(--primary-dark);
    }
    
    .calendar-header-left {
        font-weight: 600;
        font-size: 14px;
    }
    
    .calendar-header-right {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .btn-add-schedule {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-add-schedule:hover {
        background: var(--primary-dark);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-add-schedule:active {
        transform: scale(0.98);
    }
    
    /* Data Table */
    .table-wrapper {
        background: var(--card-bg);
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow-x: auto;
    }
    
    .table-wrapper h2 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-dark);
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: var(--light-bg);
        border-bottom: 2px solid var(--border-gray);
    }
    
    .data-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .data-table tbody tr {
        border-bottom: 1px solid var(--border-gray);
        transition: all 0.2s;
    }
    
    .data-table tbody tr:hover {
        background: var(--light-bg);
    }
    
    .data-table td {
        padding: 12px;
        font-size: 13px;
        color: var(--text-dark);
    }
    
    .data-table td.light {
        color: var(--text-light);
    }
    
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-orange);
    }
    
    .badge.approved {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-green);
    }
    
    .badge.published {
        background: rgba(59, 130, 246, 0.1);
        color: var(--primary-blue);
    }
    
    /* Bottom Stats */
    .bottom-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .bottom-stat-item {
        background: var(--card-bg);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .bottom-stat-item a {
        text-decoration: none;
        color: inherit;
    }
    
    .bottom-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 5px;
    }
    
    .bottom-stat-label {
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>RMS <span>Dashboard</span></h1>
        <div class="header-actions">
            <?php if (is_research_office()): ?>
                <a href="<?= base_url('admin/researchers/create') ?>" class="btn-action btn-secondary">
                    ➕ Add Research
                </a>
                <a href="<?= base_url('admin/researchers/research-teachers') ?>" class="btn-action btn-primary">
                    ➕ Add Research Teacher
                </a>
            <?php else: ?>
                <button class="btn-action btn-secondary" disabled style="opacity: 0.6;">
                    ➕ Add Research
                </button>
                <button class="btn-action btn-primary" disabled style="opacity: 0.6;">
                    ➕ Add Research Teacher
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Top Stats Row -->
    <div class="stats-row">
        <div class="stat-box">
            <a href="<?= base_url('admin/researchers') ?>" style="text-decoration: none; color: inherit;">
                <div class="stat-header">
                    <div class="stat-icon">📊</div>
                    <div class="stat-title">Statistics</div>
                </div>
                <div class="stat-details">
                    <div>
                        <div class="stat-value"><?= $totalResearch ?></div>
                        <div class="stat-description">Total Research</div>
                    </div>
                </div>
            </a>
            <div class="stat-report">
                <div class="report-title">📊 Research Report</div>
                <div class="report-items">
                    <div class="report-item">
                        <span class="report-label">Total Research:</span>
                        <span class="report-value"><?= $totalResearch ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Approved:</span>
                        <span class="report-value"><?= $totalApproved ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Published:</span>
                        <span class="report-value"><?= $totalPublished ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Pending:</span>
                        <span class="report-value"><?= $pendingReviews ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="stat-box">
            <a href="<?= base_url('admin/researchers') ?>" style="text-decoration: none; color: inherit;">
                <div class="stat-header">
                    <div class="stat-icon orange">📋</div>
                    <div class="stat-title">Research Output</div>
                </div>
                <div class="stat-details">
                    <div>
                        <div class="stat-value"><?= $totalApproved ?></div>
                        <div class="stat-description">Approved</div>
                    </div>
                </div>
            </a>
            <div class="stat-report">
                <div class="report-title">📋 Output Results</div>
                <div class="report-items">
                    <div class="report-item">
                        <span class="report-label">Approved:</span>
                        <span class="report-value"><?= $totalApproved ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Published:</span>
                        <span class="report-value"><?= $totalPublished ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">High School Dept:</span>
                        <span class="report-value"><?= $totalHighSchool ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">College Dept:</span>
                        <span class="report-value"><?= $totalCollege ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="stat-box">
            <a href="<?= base_url('admin/researchers/advisers') ?>" style="text-decoration: none; color: inherit;">
                <div class="stat-header">
                    <div class="stat-icon green">💬</div>
                    <div class="stat-title">Adviser</div>
                </div>
                <div class="stat-details">
                    <div>
                        <div class="stat-value"><?= $totalAdvisers ?></div>
                        <div class="stat-description">On Duty</div>
                    </div>
                </div>
            </a>
            <div class="stat-report">
                <div class="report-title">💬 Adviser Report</div>
                <div class="report-items">
                    <div class="report-item">
                        <span class="report-label">Total Advisers:</span>
                        <span class="report-value"><?= $totalAdvisers ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Grammarians:</span>
                        <span class="report-value"><?= $totalGrammarians ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Statisticians:</span>
                        <span class="report-value"><?= $totalStatisticians ?></span>
                    </div>
                    <div class="report-item">
                        <span class="report-label">Research Teachers:</span>
                        <span class="report-value"><?= $totalResearchTeachers ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Analytics Chart -->
        <div class="content-card">
            <h2>📈 Analytics</h2>
            <div class="chart-container">
                <canvas id="analyticsChart"></canvas>
            </div>
            <p style="font-size: 12px; color: var(--text-light); margin: 0;">
                Research submissions and approvals over time
            </p>
        </div>
        
        <!-- Calendar -->
        <div class="content-card">
            <h2>📅 Calendar</h2>
            <div class="calendar-wrapper">
                <div class="calendar-header-row">
                    <span class="calendar-header-left" id="calendarMonthLabel">August 2026</span>
                    <div class="calendar-header-right">
                        <button class="btn-action btn-secondary" id="prevMonthBtn" type="button" aria-label="Previous month">&lt;</button>
                        <button class="btn-action btn-secondary" id="nextMonthBtn" type="button" aria-label="Next month">&gt;</button>
                        <button class="btn-add-schedule" id="addScheduleBtn" type="button">+ Add Schedule</button>
                    </div>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>
                </div>
                <div class="calendar-footer">
                    <span id="eventCountText">● 3 events scheduled for this month</span>
                </div>
            </div>
        </div>
    </div>

    <div class="calendar-modal-overlay" id="scheduleModal">
        <div class="calendar-modal" role="dialog" aria-modal="true" aria-labelledby="scheduleModalTitle">
            <div class="calendar-modal-header">
                <h3 id="scheduleModalTitle">Add Schedule</h3>
                <button class="calendar-modal-close" type="button" aria-label="Close" id="closeScheduleModal">&times;</button>
            </div>
            <form class="schedule-form" id="scheduleForm">
                <div class="schedule-field">
                    <label for="scheduleTitle">Schedule Title</label>
                    <input id="scheduleTitle" name="title" type="text" placeholder="e.g. Thesis Defense" required>
                </div>
                <div class="schedule-field">
                    <label for="scheduleDate">Date</label>
                    <input id="scheduleDate" name="date" type="date" required>
                </div>
                <div class="schedule-field">
                    <label for="scheduleNotes">Notes</label>
                    <textarea id="scheduleNotes" name="notes" placeholder="Add description or notes"></textarea>
                </div>
                <div class="schedule-actions">
                    <button class="schedule-cancel" type="button" id="cancelScheduleBtn">Cancel</button>
                    <button class="schedule-submit" type="submit">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Data Table -->
    <div class="table-wrapper">
        <h2>📋 Top Table</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Research Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Advanced Machine Learning Techniques</td>
                    <td class="light">John Doe</td>
                    <td><span class="badge approved">Approved</span></td>
                    <td class="light">2026-08-15</td>
                    <td class="light">College</td>
                    <td><a href="#" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">View</a></td>
                </tr>
                <tr>
                    <td>Environmental Impact Study</td>
                    <td class="light">Jane Smith</td>
                    <td><span class="badge pending">Pending</span></td>
                    <td class="light">2026-08-14</td>
                    <td class="light">High School</td>
                    <td><a href="#" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">View</a></td>
                </tr>
                <tr>
                    <td>Digital Transformation in Education</td>
                    <td class="light">Bob Johnson</td>
                    <td><span class="badge published">Published</span></td>
                    <td class="light">2026-08-12</td>
                    <td class="light">College</td>
                    <td><a href="#" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">View</a></td>
                </tr>
                <tr>
                    <td>Social Media Analysis</td>
                    <td class="light">Alice Brown</td>
                    <td><span class="badge pending">Pending</span></td>
                    <td class="light">2026-08-11</td>
                    <td class="light">College</td>
                    <td><a href="#" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">View</a></td>
                </tr>
                <tr>
                    <td>Renewable Energy Solutions</td>
                    <td class="light">Charlie Wilson</td>
                    <td><span class="badge approved">Approved</span></td>
                    <td class="light">2026-08-10</td>
                    <td class="light">High School</td>
                    <td><a href="#" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">View</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Bottom Stats -->
    <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-dark);">Key Metrics</h2>
    <div class="bottom-stats">
        <a href="<?= base_url('admin/researchers/research-teachers') ?>" class="bottom-stat-item" style="text-decoration: none; color: inherit;">
            <div class="bottom-stat-value">👨‍🏫</div>
            <div class="bottom-stat-label">Research Teachers</div>
            <div style="font-size: 20px; font-weight: 700; color: var(--text-dark); margin-top: 8px;"><?= $totalResearchTeachers ?></div>
        </a>
        
        <a href="<?= base_url('admin/researchers/grammarians') ?>" class="bottom-stat-item" style="text-decoration: none; color: inherit;">
            <div class="bottom-stat-value">📝</div>
            <div class="bottom-stat-label">Grammarians</div>
            <div style="font-size: 20px; font-weight: 700; color: var(--text-dark); margin-top: 8px;"><?= $totalGrammarians ?></div>
        </a>
        
        <a href="<?= base_url('admin/researchers/statisticians') ?>" class="bottom-stat-item" style="text-decoration: none; color: inherit;">
            <div class="bottom-stat-value">📊</div>
            <div class="bottom-stat-label">Statisticians</div>
            <div style="font-size: 20px; font-weight: 700; color: var(--text-dark); margin-top: 8px;"><?= $totalStatisticians ?></div>
        </a>
        
        <a href="<?= base_url('admin/researchers') ?>" class="bottom-stat-item" style="text-decoration: none; color: inherit;">
            <div class="bottom-stat-value">📋</div>
            <div class="bottom-stat-label">Published</div>
            <div style="font-size: 20px; font-weight: 700; color: var(--text-dark); margin-top: 8px;"><?= $totalPublished ?></div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scheduleModal = document.getElementById('scheduleModal');
        const addScheduleBtn = document.getElementById('addScheduleBtn');
        const closeScheduleModal = document.getElementById('closeScheduleModal');
        const cancelScheduleBtn = document.getElementById('cancelScheduleBtn');
        const scheduleForm = document.getElementById('scheduleForm');
        const scheduleDate = document.getElementById('scheduleDate');
        const eventCountText = document.getElementById('eventCountText');
        const calendarGrid = document.getElementById('calendarGrid');
        const calendarMonthLabel = document.getElementById('calendarMonthLabel');
        const prevMonthBtn = document.getElementById('prevMonthBtn');
        const nextMonthBtn = document.getElementById('nextMonthBtn');

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];

        const now = new Date();
        let viewYear = now.getFullYear();
        let viewMonth = now.getMonth();

        function getSchedules() {
            return JSON.parse(localStorage.getItem('dashboardSchedules') || '[]');
        }

        function getMonthSchedules() {
            return getSchedules().filter(function (s) {
                const d = new Date(s.date);
                return !isNaN(d) && d.getFullYear() === viewYear && d.getMonth() === viewMonth;
            });
        }

        function renderCalendar() {
            calendarMonthLabel.textContent = monthNames[viewMonth] + ' ' + viewYear;

            const dayCells = calendarGrid.querySelectorAll('.calendar-day');
            dayCells.forEach(function (el) { el.remove(); });

            const firstDay = new Date(viewYear, viewMonth, 1).getDay();
            const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
            const today = new Date();

            for (let i = 0; i < firstDay; i++) {
                const empty = document.createElement('div');
                empty.className = 'calendar-day empty';
                calendarGrid.appendChild(empty);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day';
                cell.textContent = day;
                const dateStr = viewYear + '-' +
                    String(viewMonth + 1).padStart(2, '0') + '-' +
                    String(day).padStart(2, '0');
                cell.setAttribute('data-date', dateStr);

                if (viewYear === today.getFullYear() &&
                    viewMonth === today.getMonth() &&
                    day === today.getDate()) {
                    cell.classList.add('today');
                }

                calendarGrid.appendChild(cell);
            }

            highlightScheduledDays();
            updateEventCount();
        }

        function openScheduleModal() {
            if (!scheduleDate.value) {
                scheduleDate.value = new Date().toISOString().split('T')[0];
            }
            scheduleModal.classList.add('show');
        }

        function closeScheduleModalFn() {
            scheduleModal.classList.remove('show');
            scheduleForm.reset();
        }

        function updateEventCount() {
            const count = getMonthSchedules().length;
            const label = count === 1 ? ' event scheduled for this month' : ' events scheduled for this month';
            eventCountText.textContent = '● ' + count + label;
        }

        function highlightScheduledDays() {
            const dayEls = document.querySelectorAll('.calendar-day');
            dayEls.forEach(function (dayEl) {
                dayEl.classList.remove('event');
                dayEl.title = '';
            });

            getMonthSchedules().forEach(function (schedule) {
                const matchingDay = document.querySelector('.calendar-day[data-date="' + schedule.date + '"]');
                if (matchingDay) {
                    matchingDay.classList.add('event');
                    matchingDay.title = schedule.title;
                }
            });
        }

        prevMonthBtn.addEventListener('click', function () {
            viewMonth--;
            if (viewMonth < 0) {
                viewMonth = 11;
                viewYear--;
            }
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', function () {
            viewMonth++;
            if (viewMonth > 11) {
                viewMonth = 0;
                viewYear++;
            }
            renderCalendar();
        });

        addScheduleBtn.addEventListener('click', openScheduleModal);
        closeScheduleModal.addEventListener('click', closeScheduleModalFn);
        cancelScheduleBtn.addEventListener('click', closeScheduleModalFn);
        scheduleModal.addEventListener('click', function (event) {
            if (event.target === scheduleModal) {
                closeScheduleModalFn();
            }
        });

        scheduleForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const title = document.getElementById('scheduleTitle').value.trim();
            const date = document.getElementById('scheduleDate').value;
            const notes = document.getElementById('scheduleNotes').value.trim();

            if (!title || !date) {
                return;
            }

            const saved = getSchedules();
            saved.push({
                title: title,
                date: date,
                notes: notes
            });

            localStorage.setItem('dashboardSchedules', JSON.stringify(saved));
            closeScheduleModalFn();

            const d = new Date(date);
            if (!isNaN(d)) {
                viewYear = d.getFullYear();
                viewMonth = d.getMonth();
            }
            renderCalendar();
        });

        renderCalendar();

        // Analytics Chart
        const analyticsCtx = document.getElementById('analyticsChart').getContext('2d');
        new Chart(analyticsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [
                    {
                        label: 'Submitted',
                        data: [12, 19, 15, 25, 22, 30, 28, 35],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#3b82f6'
                    },
                    {
                        label: 'Approved',
                        data: [8, 14, 10, 18, 16, 24, 20, 28],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#6b7280',
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e5e7eb' },
                        ticks: { color: '#6b7280' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280' }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
