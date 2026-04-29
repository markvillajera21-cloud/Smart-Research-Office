<ul class="nav flex-column flex-grow-1">
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/dashboard') ? 'active' : '') ?>" href="<?= base_url('admin/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>
    
    <?php if (session()->get('role') === 'admin'): ?>
    <li class="nav-item">
        <div class="px-4 py-2 mt-3 mb-1 text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Management</div>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/users*') ? 'active' : '') ?>" href="<?= base_url('admin/users') ?>">
            <i class="bi bi-person-badge-fill"></i> Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/researchers') ? 'active' : '') ?>" href="<?= base_url('admin/researchers') ?>">
            <i class="bi bi-people-fill"></i> Researchers List
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/researchers/high-school') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/high-school') ?>">
            <i class="bi bi-mortarboard"></i> High School Department
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/researchers/college') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/college') ?>">
            <i class="bi bi-building"></i> College Department
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/projects*') ? 'active' : '') ?>" href="<?= base_url('admin/projects') ?>">
            <i class="bi bi-journal-text"></i> Projects
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/uploads*') ? 'active' : '') ?>" href="<?= base_url('admin/uploads') ?>">
            <i class="bi bi-cloud-arrow-up"></i> Upload Data
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/audit-logs') ? 'active' : '') ?>" href="<?= base_url('admin/audit-logs') ?>">
            <i class="bi bi-shield-check"></i> Audit Trail
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/history*') ? 'active' : '') ?>" href="<?= base_url('admin/history') ?>">
            <i class="bi bi-mortarboard-fill"></i> Researchers History
        </a>
    </li>
    <?php endif; ?>

    <li class="nav-item">
        <div class="px-4 py-2 mt-3 mb-1 text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Personal</div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-file-earmark-bar-graph"></i> My Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-bell"></i> Notifications
        </a>
    </li>
</ul>

<div class="nav-item mt-auto border-top p-4">
    <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 shadow-sm">
        <i class="bi bi-box-arrow-right"></i>
        <span class="fw-semibold">Sign Out</span>
    </a>
</div>
