<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Edit User Account</h2>
            <p class="text-muted mb-0">Update user information and permissions</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold">Username / Login ID <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">User Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="student" <?= old('role', $user['role']) === 'student' ? 'selected' : '' ?>>Student</option>
                                    <option value="teacher" <?= in_array(old('role', $user['role']), ['teacher', 'instructor', 'teacher_archive_viewer'], true) ? 'selected' : '' ?>>Teacher/Research Instructor</option>
                                    <option value="coordinator" <?= old('role', $user['role']) === 'coordinator' ? 'selected' : '' ?>>Coordinator</option>
                                    <option value="director" <?= old('role', $user['role']) === 'director' ? 'selected' : '' ?>>Director</option>
                                    <option value="research_staff" <?= in_array(old('role', $user['role']), ['research_staff', 'researcher_archive_viewer'], true) ? 'selected' : '' ?>>Research Staff</option>
                                    <option value="sub_admin" <?= old('role', $user['role']) === 'sub_admin' ? 'selected' : '' ?>>Sub-Admin</option>
                                    <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <div class="form-text mt-2">
                                <strong>Role Permissions:</strong>
                                <ul class="mb-0 mt-1 small">
                                    <li><strong>Student:</strong> Student pages only (titles, schedules, archive search)</li>
                                    <li><strong>Teacher/Research Instructor:</strong> Teacher pages only (assigned students, schedules, titles, evaluations)</li>
                                    <li><strong>Coordinator, Director, Research Staff:</strong> Research office functions</li>
                                    <li><strong>Sub-Admin:</strong> Research office management (scheduling, titles, monitoring, evaluations, reports, expert pool) without user administration</li>
                                    <li><strong>Admin:</strong> Full research office functions plus system administration and user management</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="department" class="form-label fw-semibold">Department</label>
                            <select class="form-select" id="department" name="department">
                                <?php foreach ($departments as $slug => $name): ?>
                                    <option value="<?= esc($slug) ?>" <?= old('department', $user['department'] ?? '') === $slug ? 'selected' : '' ?>><?= esc($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Assign a department to enable its dedicated login and dashboard.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">New Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password (min. 8 characters)">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="password_confirm" class="form-label fw-semibold">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="card bg-light border-0 mb-4 p-4">
                            <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Account Information</h6>
                            <div class="row small">
                                <div class="col-md-6 mb-2">
                                    <strong>Created At:</strong> <?= date('F d, Y h:i A', strtotime($user['created_at'])) ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Last Updated:</strong> <?= date('F d, Y h:i A', strtotime($user['updated_at'])) ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Login Attempts:</strong> <?= $user['login_attempts'] ?? 0 ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Current Role:</strong>
                                    <span class="badge bg-<?= is_student($user['role']) ? 'success' : (is_teacher($user['role']) ? 'warning' : 'primary') ?> ms-1">
                                        <?= esc(role_label($user['role'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary px-5 py-2 rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill ms-auto">
                                <i class="bi bi-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
<?= $this->endSection() ?>
