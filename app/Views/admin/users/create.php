<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Create New User</h2>
            <p class="text-muted mb-0">Add a new user account to RMS</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                                    <li><?= $e ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/users/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <?php if (isset($redirectTo) && $redirectTo): ?>
                            <input type="hidden" name="redirect_to" value="<?= esc($redirectTo) ?>">
                        <?php endif; ?>

                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold">Username / Login ID <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required minlength="3">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">User Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                                    <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher/Research Instructor</option>
                                    <option value="coordinator" <?= old('role') === 'coordinator' ? 'selected' : '' ?>>Coordinator</option>
                                    <option value="director" <?= old('role') === 'director' ? 'selected' : '' ?>>Director</option>
                                    <option value="research_staff" <?= old('role') === 'research_staff' ? 'selected' : '' ?>>Research Staff</option>
                                    <option value="sub_admin" <?= old('role') === 'sub_admin' ? 'selected' : '' ?>>Sub-Admin</option>
                                    <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="department" class="form-label fw-semibold">Department</label>
                            <select class="form-select" id="department" name="department">
                                <?php foreach ($departments ?? [] as $slug => $name): ?>
                                    <option value="<?= esc($slug) ?>" <?= old('department') === $slug ? 'selected' : '' ?>><?= esc($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Assign a department to enable its dedicated login and dashboard.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="password_confirm" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirm" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('password_confirm', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary px-5 py-2 rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill ms-auto">
                                <i class="bi bi-person-plus me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd(id, btn) {
    const inp = document.getElementById(id);
    const ic = btn.querySelector('i');
    if (inp.type === 'password') { inp.type = 'text'; ic.className = 'bi bi-eye-slash'; }
    else { inp.type = 'password'; ic.className = 'bi bi-eye'; }
}
</script>
<?= $this->endSection() ?>
