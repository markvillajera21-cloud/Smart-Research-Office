<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($departmentConfig['name']) ?> Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --department-accent: <?= esc($departmentConfig['accent']) ?>; }
        body { min-height: 100vh; display: grid; place-items: center; background: #f4f7f8; }
        .login-card { width: min(440px, 92vw); border: 0; box-shadow: 0 20px 50px rgba(15, 23, 42, .12); }
        .login-header { background: var(--department-accent); color: white; padding: 2.5rem 2rem; }
        .brand-mark { width: 64px; height: 64px; display: grid; place-items: center; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,.45); border-radius: 14px; font-size: 1.8rem; }
        .btn-department { background: var(--department-accent); border-color: var(--department-accent); color: white; }
        .btn-department:hover { filter: brightness(.92); color: white; }
    </style>
</head>
<body>
    <main class="card login-card overflow-hidden">
        <header class="login-header">
            <div class="brand-mark"><i class="bi bi-building"></i></div>
            <div class="text-uppercase small opacity-75">Research Office</div>
            <h1 class="h3 fw-bold mb-1"><?= esc($departmentConfig['name']) ?></h1>
            <p class="mb-0 opacity-75">Department research portal</p>
        </header>
        <section class="card-body p-4 p-md-5">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger small"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <h2 class="h5 fw-bold">Sign in to your dashboard</h2>
            <p class="text-muted small mb-4">Use the account assigned to <?= esc($departmentConfig['name']) ?>.</p>
            <form action="<?= base_url($department . '/login') ?>" method="post">
                <?= csrf_field() ?>
                <label class="form-label small fw-semibold" for="role">Role</label>
                <select class="form-select mb-3" id="role" name="role" required>
                    <option value="">Select your role</option>
                    <?php foreach (\Config\Roles::LOGIN_CHOICES as $value => $label): ?>
                        <option value="<?= esc($value) ?>" <?= old('role') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="form-label small fw-semibold" for="email">Login ID or Email</label>
                <input class="form-control mb-3" type="text" id="email" name="email" value="<?= esc(old('email')) ?>" required autofocus>
                <label class="form-label small fw-semibold" for="password">Password</label>
                <input class="form-control mb-4" type="password" id="password" name="password" required>
                <button class="btn btn-department w-100" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Sign in</button>
            </form>
            <a class="d-block text-center small mt-4 text-decoration-none" href="<?= base_url('login') ?>">Use the main login</a>
        </section>
    </main>
</body>
</html>
