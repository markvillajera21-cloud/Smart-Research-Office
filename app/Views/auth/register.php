<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Research Office</title>
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
        .register-card {
            border: none;
            border-radius: 2rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            background: white;
        }
        .register-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        .register-body {
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
            color: #10b981;
            background-color: #e2e8f0;
        }
        .form-control.has-toggle {
            border-right: none;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
        .btn-success {
            padding: 0.75rem;
            border-radius: 0.75rem;
            font-weight: 600;
            background-color: #10b981;
            border: none;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <div class="logo-wrapper">
                <div class="logo-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-2">Join ROARMS</h3>
            <p class="opacity-75 mb-0 small">Create your research account</p>
        </div>
        
        <div class="register-body">
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-1">Create Account</h4>
                <p class="text-muted small">Fill in your details to get started.</p>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger border-0 small mb-4">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('register') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Username</label>
                    <div class="input-group">
                        <input type="text" name="username" id="username" class="form-control has-toggle" value="<?= old('username') ?>" placeholder="" required>
                        <span class="input-group-text" onclick="clearInput('username')">
                            <i class="bi bi-x-circle"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Email Address</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control has-toggle" value="<?= old('email') ?>" placeholder="" required>
                        <span class="input-group-text" onclick="clearInput('email')">
                            <i class="bi bi-x-circle"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-muted">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control has-toggle" placeholder="" required>
                        <span class="input-group-text" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-success text-white shadow-sm">Register Now</button>
                </div>
                <div class="text-center">
                    <span class="text-muted small">Already have an account?</span>
                    <a href="<?= base_url('login') ?>" class="small text-decoration-none fw-semibold">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            const icon = iconElement.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        function clearInput(inputId) {
            document.getElementById(inputId).value = '';
            document.getElementById(inputId).focus();
        }
    </script>
</body>
</html>
