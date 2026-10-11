<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Add Examiner</h2>
            <p class="text-muted mb-0">Register an examiner for evaluations and defenses</p>
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

                    <form action="<?= base_url('admin/examiners/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Examiner Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name')) ?>" required minlength="2">
                        </div>
                        <div class="mb-4">
                            <label for="examiner_role" class="form-label fw-semibold">Examiner Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="examiner_role" name="examiner_role" required>
                                <option value="">-- Select Role --</option>
                                <?php foreach (\App\Models\ExaminerModel::roleLabels() as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= old('examiner_role') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Only active examiners with this role will appear in assignment lists.</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/examiners') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Examiner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
