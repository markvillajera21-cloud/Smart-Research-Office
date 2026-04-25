<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Smart Research Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SRO Portal</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><span class="nav-link text-dark">Welcome, <?= session()->get('username') ?></span></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-danger btn-sm ms-2" href="<?= base_url('logout') ?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Researcher Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">My Research Applications</div>
                    <div class="card-body">
                        <p class="text-muted">You have no active applications.</p>
                        <button class="btn btn-primary">Start New Research</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Profile Information</div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?= session()->get('username') ?></p>
                        <p><strong>Email:</strong> <?= session()->get('email') ?></p>
                        <p><strong>Role:</strong> Researcher</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
