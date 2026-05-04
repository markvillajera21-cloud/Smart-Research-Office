<ul class="nav flex-column flex-grow-1">
    <?php if (session()->get('role') === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/dashboard') ? 'active' : '') ?>" href="<?= base_url('admin/dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Overview
        </a>
    </li>
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
            <i class="bi bi-people-fill"></i> Research List
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/researchers/update-status') ? 'active' : '') ?>" href="<?= base_url('admin/researchers/update-status') ?>">
            <i class="bi bi-arrow-repeat"></i> Update Status
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (url_is('admin/uploads*') ? 'active' : '') ?>" href="<?= base_url('admin/uploads') ?>">
            <i class="bi bi-cloud-arrow-up"></i> Upload Data
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

<div class="nav-item mt-auto border-top p-4">
    <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 shadow-sm">
        <i class="bi bi-box-arrow-right"></i>
        <span class="fw-semibold">Sign Out</span>
    </a>
</div>
