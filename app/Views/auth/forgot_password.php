<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Smart Research Office</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 2rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            background: white;
        }
        .login-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        .logo-wrapper {
            background: rgba(255, 255, 255, 0.15);
            padding: 1.25rem;
            border-radius: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .logo-icon {
            font-size: 2.5rem;
            line-height: 1;
        }
        .login-body {
            padding: 3rem 2.5rem;
        }
        .input-group-text {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-left: none;
            border-radius: 0 0.75rem 0.75rem 0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }
        .input-group-text:hover {
            color: #2563eb;
            background-color: #e2e8f0;
        }
        .form-control.has-toggle {
            border-right: none;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .btn-primary {
            padding: 0.75rem;
            border-radius: 0.75rem;
            font-weight: 600;
            background-color: #2563eb;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="logo-wrapper">
                <div class="logo-icon">
                    <i class="bi bi-cpu-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-2">Smart Research Office</h3>
            <p class="opacity-75 mb-0 small">Collaborative Research & Automation</p>
        </div>
        
        <div class="login-body">
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-1">Reset Password</h4>
                <p class="text-muted small">Enter your email to receive a password reset link.</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 small mb-4">
                    <i class="bi bi-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success border-0 small mb-4">
                    <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('forgot-password') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-muted">Email Address</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control has-toggle" placeholder="name@company.com" required>
                        <span class="input-group-text" onclick="clearInput('email')">
                            <i class="bi bi-x-circle"></i>
                        </span>
                    </div>
                </div>
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary shadow-sm">Send Reset Link</button>
                </div>
                <div class="text-center">
                    <a href="<?= base_url('login') ?>" class="small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function clearInput(inputId) {
            document.getElementById(inputId).value = '';
            document.getElementById(inputId).focus();
        }
    </script>
</body>
</html>
