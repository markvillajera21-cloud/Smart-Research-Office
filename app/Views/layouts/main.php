<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Smart Research Office' ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1e3a8a;
            --primary-dark: #1e40af;
            --accent-color: #facc15;
            --accent-dark: #eab308;
            --secondary-color: #475569;
            --sidebar-bg: #1e3a8a;
            --sidebar-text: #fef9c3;
            --sidebar-active: #1e40af;
            --main-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            background-color: var(--main-bg);
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            border-right: none;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .nav-link {
            color: var(--sidebar-text);
            font-weight: 600;
            padding: 0.875rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.25rem 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: white;
            background-color: rgba(250, 204, 21, 0.2);
        }

        .nav-link.active {
            background-color: var(--accent-color);
            color: #1e3a8a;
            box-shadow: 0 4px 12px rgba(250, 204, 21, 0.4);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .navbar-brand .bi {
            color: var(--primary-color);
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.875rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--card-bg);
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 0.625rem;
            font-weight: 700;
            font-size: 0.9375rem;
            color: white;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.3), 0 2px 4px -2px rgba(30, 58, 138, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(30, 58, 138, 0.4), 0 4px 6px -4px rgba(30, 58, 138, 0.3);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-secondary {
            border: 2px solid var(--accent-color);
            color: var(--primary-color);
            padding: 0.625rem 1.5rem;
            border-radius: 0.625rem;
            font-weight: 700;
            font-size: 0.9375rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            border-color: var(--accent-dark);
            color: var(--primary-dark);
            background-color: rgba(250, 204, 21, 0.15);
            transform: translateY(-1px);
        }

        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-top: none;
            border-bottom: 2px solid #e2e8f0;
        }

        .badge {
            padding: 0.375em 0.75em;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.8125rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control,
        .form-select {
            border-color: #cbd5e1;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0 sidebar d-none d-md-block sticky-top">
                <div class="p-4 border-bottom mb-4" style="border-color: rgba(250, 204, 21, 0.2);">
                    <a class="navbar-brand fs-4" href="#" style="color: var(--sidebar-text);">
                        <i class="bi bi-cpu me-2" style="color: var(--accent-color);"></i>SRO
                    </a>
                </div>
                <?= view('theme/sidebar') ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-0">
                <!-- Top Header -->
                <nav class="navbar navbar-expand-lg bg-white border-bottom px-4 py-3 sticky-top shadow-sm">
                    <div class="container-fluid p-0">
                        <h5 class="mb-0 fw-semibold" style="color: var(--text-primary);"><?= $page_title ?? 'Dashboard' ?></h5>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" style="color: var(--text-primary);">
                                    <div class="text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
                                        <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
                                    </div>
                                    <span class="d-none d-sm-inline fw-semibold"><?= session()->get('username') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3">
                                    <li><a class="dropdown-item py-2 px-3" href="<?= base_url(session()->get('role') === 'admin' ? 'admin/profile' : 'user/profile') ?>"><i class="bi bi-person me-2" style="color: var(--primary-color);"></i> Profile</a></li>
                                    <li><a class="dropdown-item py-2 px-3" href="<?= base_url(session()->get('role') === 'admin' ? 'admin/settings' : 'user/settings') ?>"><i class="bi bi-gear me-2" style="color: var(--primary-color);"></i> Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 px-3 text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="p-4" style="background-color: var(--main-bg);">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?= $this->renderSection('content') ?>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
