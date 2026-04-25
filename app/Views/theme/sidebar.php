<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse shadow-sm" style="min-height: 100vh;">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/dashboard') ? 'active fw-bold' : '') ?>" href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/users*') ? 'active fw-bold' : '') ?>" href="<?= base_url('admin/users') ?>">
                    <i class="bi bi-people"></i> User Management
                </a>
            </li>
            <hr>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
