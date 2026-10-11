<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="mb-5 d-flex align-items-center gap-3">
                    <div class="d-grid place-items-center" style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#eef2ff,#e0e7ff);color:#4338ca;">
                        <i class="bi bi-gear-fill fs-3"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">Settings</h5>
                        <p class="text-muted mb-0 small">Manage your account settings, password, and notification preferences.</p>
                    </div>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 small mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success border-0 small mb-4">
                        <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= esc($formAction ?? base_url('admin/settings')) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-5">
                        <h6 class="fw-semibold mb-3 text-uppercase small" style="letter-spacing:.08em;color:#475569;">Account Security</h6>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-muted">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Enter current password to change it" autocomplete="current-password">
                                <div class="form-text small">Required only if you are changing your password.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">New Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="At least 8 characters" autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password" autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="fw-semibold mb-3 text-uppercase small" style="letter-spacing:.08em;color:#475569;">Notifications</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 border rounded">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" name="email_notifications" checked>
                                    <label class="form-check-label fw-medium" for="emailNotifications">
                                        Email notifications
                                    </label>
                                    <div class="form-text small ms-4">Receive email alerts for approvals, schedules, and remarks.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 border rounded">
                                    <input class="form-check-input" type="checkbox" id="systemUpdates" name="system_updates">
                                    <label class="form-check-label fw-medium" for="systemUpdates">
                                        System update alerts
                                    </label>
                                    <div class="form-text small ms-4">Occasional announcements about the RMS system.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url(is_research_office() ? 'admin/dashboard' : 'user/dashboard') ?>" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
