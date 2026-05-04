<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="mb-4">
                <h5 class="mb-1 fw-bold text-dark">Settings</h5>
                <p class="text-muted mb-0">Manage your account settings and preferences.</p>
            </div>

            <div class="mb-4">
                <h6 class="fw-semibold mb-3">Account Settings</h6>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Change Password</label>
                        <input type="password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-semibold mb-3">Notification Settings</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                    <label class="form-check-label" for="emailNotifications">
                        Email notifications
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="systemUpdates">
                    <label class="form-check-label" for="systemUpdates">
                        System update alerts
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-light">Cancel</a>
                <button class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
