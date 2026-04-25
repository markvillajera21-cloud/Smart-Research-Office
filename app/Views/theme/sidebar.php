<ul class="nav flex-column">
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
            <i class="bi bi-people-fill"></i> Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-journal-text"></i> Projects
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
