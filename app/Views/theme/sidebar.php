<?php
$dashboardUrl = is_sub_admin() ? 'sub-admin/dashboard' : 'admin/dashboard';
$profileUrl   = is_sub_admin() ? 'sub-admin/profile' : 'admin/profile';
$settingsUrl  = is_sub_admin() ? 'sub-admin/settings' : 'admin/settings';
?>
<ul class="nav flex-column flex-grow-1">
    <?php if (is_research_office()): ?>
    <?php
        $schedulingOpen  = url_is('admin/activities*') || url_is('admin/scheduling*') || url_is('admin/pre-oral-scheduling*');
        $titleOpen       = url_is('admin/research-titles*');
        $monitoringOpen  = url_is('admin/monitoring*');
        $evalOpen        = url_is('admin/evaluations*');
        $mgmtOpen        = url_is('admin/users*') || url_is('admin/experts*') || url_is('admin/researchers*');
        $reportsOpen     = url_is('admin/reports*') || url_is('admin/generated-reports*');
        $securityOpen    = url_is('admin/uploads*') || url_is('admin/audit-logs') || url_is($profileUrl) || url_is($settingsUrl);
    ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('sub-admin/dashboard') || url_is('admin/dashboard') ? 'active' : '') ?>" href="<?= base_url($dashboardUrl) ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#schedulingCollapse" aria-expanded="<?= $schedulingOpen ? 'true' : 'false' ?>">
            <span>1. Scheduling</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $schedulingOpen ? 'show' : '' ?>" id="schedulingCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/activities*') ? 'active' : '') ?>" href="<?= base_url('admin/activities') ?>">
                        <i class="bi bi-calendar-check-fill"></i> Calendar of Activities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/scheduling*') && !url_is('admin/pre-oral-scheduling*') ? 'active' : '') ?>" href="<?= base_url('admin/scheduling') ?>">
                        <i class="bi bi-calendar-event-fill"></i> Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/pre-oral-scheduling*') ? 'active' : '') ?>" href="<?= base_url('admin/pre-oral-scheduling') ?>">
                        <i class="bi bi-calendar2-range-fill"></i> Pre-Oral Scheduling
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#titleCollapse" aria-expanded="<?= $titleOpen ? 'true' : 'false' ?>">
            <span>2. Title Management</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $titleOpen ? 'show' : '' ?>" id="titleCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/research-titles*') ? 'active' : '') ?>" href="<?= base_url('admin/research-titles') ?>">
                        <i class="bi bi-journal-text"></i> Research Titles
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#monitoringCollapse" aria-expanded="<?= $monitoringOpen ? 'true' : 'false' ?>">
            <span>3. Monitoring</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $monitoringOpen ? 'show' : '' ?>" id="monitoringCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/monitoring') ? 'active' : '') ?>" href="<?= base_url('admin/monitoring') ?>">
                        <i class="bi bi-speedometer2"></i> Progress Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/monitoring/completed') ? 'active' : '') ?>" href="<?= base_url('admin/monitoring/completed') ?>">
                        <i class="bi bi-check2-circle"></i> Completed Research
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/monitoring/incomplete') ? 'active' : '') ?>" href="<?= base_url('admin/monitoring/incomplete') ?>">
                        <i class="bi bi-clock-history"></i> Incomplete Research
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/monitoring/pre-oral-summary') ? 'active' : '') ?>" href="<?= base_url('admin/monitoring/pre-oral-summary') ?>">
                        <i class="bi bi-clipboard-data"></i> Pre-Oral Summary Report
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#evalCollapse" aria-expanded="<?= $evalOpen ? 'true' : 'false' ?>">
            <span>4. Evaluations</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $evalOpen ? 'show' : '' ?>" id="evalCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/evaluations*') ? 'active' : '') ?>" href="<?= base_url('admin/evaluations') ?>">
                        <i class="bi bi-clipboard-check-fill"></i> Evaluation & Remarks
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mgmtCollapse" aria-expanded="<?= $mgmtOpen ? 'true' : 'false' ?>">
            <span>5. Management</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $mgmtOpen ? 'show' : '' ?>" id="mgmtCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <?php if (can_manage_users()): ?>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/users*') ? 'active' : '') ?>" href="<?= base_url('admin/users') ?>">
                        <i class="bi bi-person-badge-fill"></i> Users
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/experts*') ? 'active' : '') ?>" href="<?= base_url('admin/experts') ?>">
                        <i class="bi bi-person-lines-fill"></i> Expert Pool
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/researchers') ? 'active' : '') ?>" href="<?= base_url('admin/researchers') ?>">
                        <i class="bi bi-people-fill"></i> Research List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/researchers/research-teachers') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/research-teachers') ?>">
                        <i class="bi bi-mortarboard-fill"></i> Research Teachers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/researchers/grammarians') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/grammarians') ?>">
                        <i class="bi bi-translate"></i> Grammarians
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/researchers/statisticians') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/statisticians') ?>">
                        <i class="bi bi-bar-chart-fill"></i> Statisticians
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/researchers/advisers') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/advisers') ?>">
                        <i class="bi bi-chat-right-dots-fill"></i> Advisers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/researchers/update-status') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/update-status') ?>">
                        <i class="bi bi-arrow-repeat"></i> Update Status
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#reportsCollapse" aria-expanded="<?= $reportsOpen ? 'true' : 'false' ?>">
            <span>8. Reports</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $reportsOpen ? 'show' : '' ?>" id="reportsCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/reports/schedule') ? 'active' : '') ?>" href="<?= base_url('admin/reports/schedule') ?>">
                        <i class="bi bi-file-earmark-calendar"></i> Schedule Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/reports/status') ? 'active' : '') ?>" href="<?= base_url('admin/reports/status') ?>">
                        <i class="bi bi-file-earmark-bar-graph"></i> Research Status Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/reports/researchers*') ? 'active' : '') ?>" href="<?= base_url('admin/reports/researchers') ?>">
                        <i class="bi bi-file-earmark-person"></i> Researcher Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/reports/completion') ? 'active' : '') ?>" href="<?= base_url('admin/reports/completion') ?>">
                        <i class="bi bi-file-earmark-check"></i> Completion Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/reports/history') ? 'active' : '') ?>" href="<?= base_url('admin/reports/history') ?>">
                        <i class="bi bi-file-earmark-text"></i> Reports History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/generated-reports') ? 'active' : '') ?>" href="<?= base_url('admin/generated-reports') ?>">
                        <i class="bi bi-files"></i> Generated Reports
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#securityCollapse" aria-expanded="<?= $securityOpen ? 'true' : 'false' ?>">
            <span>7. Security & Others</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $securityOpen ? 'show' : '' ?>" id="securityCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <?php if (is_admin()): ?>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/uploads*') ? 'active' : '') ?>" href="<?= base_url('admin/uploads') ?>">
                        <i class="bi bi-cloud-arrow-up"></i> Upload Data
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('admin/audit-logs') ? 'active' : '') ?>" href="<?= base_url('admin/audit-logs') ?>">
                        <i class="bi bi-clock-history"></i> Audit Trail / Activity Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is($profileUrl) ? 'active' : '') ?>" href="<?= base_url($profileUrl) ?>">
                        <i class="bi bi-person-circle"></i> My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is($settingsUrl) ? 'active' : '') ?>" href="<?= base_url($settingsUrl) ?>">
                        <i class="bi bi-gear-fill"></i> Settings
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <?php elseif (is_teacher()): ?>
    <?php
        $teacherClassesOpen = url_is('teacher/students') || url_is('teacher/schedules') || url_is('teacher/titles') || url_is('teacher/evaluations');
    ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('teacher/dashboard') ? 'active' : '') ?>" href="<?= base_url('teacher/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>
    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#teacherClassesCollapse" aria-expanded="<?= $teacherClassesOpen ? 'true' : 'false' ?>">
            <span>My Classes</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $teacherClassesOpen ? 'show' : '' ?>" id="teacherClassesCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('teacher/students') ? 'active' : '') ?>" href="<?= base_url('teacher/students') ?>">
                        <i class="bi bi-people-fill"></i> Assigned Students
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('teacher/schedules') ? 'active' : '') ?>" href="<?= base_url('teacher/schedules') ?>">
                        <i class="bi bi-calendar-event-fill"></i> My Schedules
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('teacher/titles') ? 'active' : '') ?>" href="<?= base_url('teacher/titles') ?>">
                        <i class="bi bi-journal-text"></i> Student Titles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('teacher/evaluations') ? 'active' : '') ?>" href="<?= base_url('teacher/evaluations') ?>">
                        <i class="bi bi-clipboard-check-fill"></i> Student Evaluations
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <?php elseif (is_student()): ?>
    <?php
        $studentResourcesOpen = url_is('student/expert-pool') || url_is('student/search-titles') || url_is('student/completed-researches');
    ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('student/dashboard') ? 'active' : '') ?>" href="<?= base_url('student/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> My Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('student/my-titles*') ? 'active' : '') ?>" href="<?= base_url('student/my-titles') ?>">
            <i class="bi bi-journal-text"></i> Student Titles
        </a>
    </li>
    <li class="nav-item">
        <button class="sidebar-dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#studentResourcesCollapse" aria-expanded="<?= $studentResourcesOpen ? 'true' : 'false' ?>">
            <span>Research Resources</span>
            <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $studentResourcesOpen ? 'show' : '' ?>" id="studentResourcesCollapse">
            <ul class="nav flex-column sidebar-collapse">
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('student/expert-pool') ? 'active' : '') ?>" href="<?= base_url('student/expert-pool') ?>">
                        <i class="bi bi-person-lines-fill"></i> Expert Pool
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('student/search-titles') ? 'active' : '') ?>" href="<?= base_url('student/search-titles') ?>">
                        <i class="bi bi-search"></i> Search Research
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('student/completed-researches') ? 'active' : '') ?>" href="<?= base_url('student/completed-researches') ?>">
                        <i class="bi bi-check2-circle"></i> Completed Researches
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <?php else: ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('user/dashboard') ? 'active' : '') ?>" href="<?= base_url('user/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>
    <?php endif; ?>
</ul>

<div class="nav-item mt-auto p-4" style="border-top: 1px solid rgba(255, 255, 255, 0);">
    <a href="<?= base_url('logout') ?>" class="btn w-100 d-flex align-items-center justify-content-center gap-2 py-2" style="background-color: rgba(255,255,255,0.1); border: none; color: white;">
        <i class="bi bi-box-arrow-right"></i>
        <span class="fw-semibold">Sign Out</span>
    </a>
</div>
