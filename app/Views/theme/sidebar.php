<ul class="nav flex-column flex-grow-1">
    <?php if (session()->get('role') === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/dashboard') ? 'active' : '') ?>" href="<?= base_url('admin/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>
    <li class="nav-item">
        <div class="px-4 py-2 mt-3 mb-1 text-uppercase small" style="letter-spacing: 0.1em; color: rgba(226, 232, 240, 0.6); font-weight: 700;">Management</div>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/users*') ? 'active' : '') ?>" href="<?= base_url('admin/users') ?>">
            <i class="bi bi-person-badge-fill"></i> Users
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
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/generated-reports') ? 'active' : '') ?>" href="<?= base_url('admin/generated-reports') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i> Generated Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/uploads*') ? 'active' : '') ?>" href="<?= base_url('admin/uploads') ?>">
            <i class="bi bi-cloud-arrow-up"></i> Upload Data
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/audit-logs') ? 'active' : '') ?>" href="<?= base_url('admin/audit-logs') ?>">
            <i class="bi bi-clock-history"></i> Audit Trail
        </a>
    </li>
    <?php elseif (session()->get('role') === 'archive_viewer'): ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/dashboard') ? 'active' : '') ?>" href="<?= base_url('admin/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>
    <li class="nav-item">
        <div class="px-4 py-2 mt-3 mb-1 text-uppercase small" style="letter-spacing: 0.1em; color: rgba(226, 232, 240, 0.6); font-weight: 700;">Management</div>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/users*') ? 'active' : '') ?>" href="<?= base_url('admin/users') ?>">
            <i class="bi bi-person-badge-fill"></i> Users
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
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/generated-reports') ? 'active' : '') ?>" href="<?= base_url('admin/generated-reports') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i> Generated Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/uploads*') ? 'active' : '') ?>" href="<?= base_url('admin/uploads') ?>">
            <i class="bi bi-cloud-arrow-up"></i> Upload Data
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/audit-logs') ? 'active' : '') ?>" href="<?= base_url('admin/audit-logs') ?>">
            <i class="bi bi-clock-history"></i> Audit Trail
        </a>
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
