<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Research Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?= view('theme/sidebar') ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary p-2">Welcome, <?= session()->get('username') ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-white bg-primary mb-3">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-journals fs-1 mb-3"></i>
                                <h5 class="card-title">Research Projects</h5>
                                <p class="card-text">Manage all ongoing research activities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-white bg-success mb-3">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-people fs-1 mb-3"></i>
                                <h5 class="card-title">User Management</h5>
                                <p class="card-text">View and manage registered researchers.</p>
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-light btn-sm mt-2">Manage Users</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-white bg-info mb-3">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-activity fs-1 mb-3"></i>
                                <h5 class="card-title">System Logs</h5>
                                <p class="card-text">Monitor system performance and access.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
