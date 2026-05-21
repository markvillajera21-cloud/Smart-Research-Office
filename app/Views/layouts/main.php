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
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --sidebar-bg: #ffffff;
            --main-bg: #eff6ff;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: var(--main-bg);
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.25rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            background-color: #eff6ff;
            color: var(--primary-color);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px;
        }

        .table thead th {
            background-color: #dbeafe;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-top: none;
        }

        .badge {
            padding: 0.5em 0.8em;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 0.925rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0 sidebar d-none d-md-block sticky-top">
                <div class="p-4 border-bottom mb-4">
                    <a class="navbar-brand fs-4" href="#">
                        <i class="bi bi-cpu text-primary me-2"></i>SRO
                    </a>
                </div>
                <?= view('theme/sidebar') ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-0">
                <!-- Top Header -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 sticky-top">
                    <div class="container-fluid p-0">
                        <h5 class="mb-0 fw-semibold"><?= $page_title ?? 'Dashboard' ?></h5>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
                                    </div>
                                    <span class="d-none d-sm-inline fw-medium"><?= session()->get('username') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3">
                                    <li><a class="dropdown-item py-2" href="<?= base_url(session()->get('role') === 'admin' ? 'admin/profile' : 'user/profile') ?>"><i class="bi bi-person me-2"></i> Profile</a></li>
                                    <li><a class="dropdown-item py-2" href="<?= base_url(session()->get('role') === 'admin' ? 'admin/settings' : 'user/settings') ?>"><i class="bi bi-gear me-2"></i> Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
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
