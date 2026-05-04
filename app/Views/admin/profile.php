<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="mb-4">
                <h5 class="mb-1 fw-bold text-dark">Profile Information</h5>
                <p class="text-muted mb-0">View and manage your profile details.</p>
            </div>

            <div class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="mb-0"><?= esc($user['username']) ?></h4>
                        <p class="text-muted mb-0"><?= esc($user['email']) ?></p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= esc($user['username']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="<?= esc($user['email']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="<?= ucfirst(esc($user['role'])) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Member Since</label>
                        <input type="text" class="form-control" value="<?= date('M d, Y', strtotime($user['created_at'])) ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-light">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
