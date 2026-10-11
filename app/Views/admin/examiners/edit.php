<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2"></i>Edit Examiner</h2>
            <p class="text-muted mb-0">Update examiner information or status</p>
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

                    <form action="<?= base_url('admin/examiners/update/' . $examiner['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Examiner Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name', $examiner['name'])) ?>" required minlength="2">
                        </div>
                        <div class="mb-4">
                            <label for="examiner_role" class="form-label fw-semibold">Examiner Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="examiner_role" name="examiner_role" required>
                                <?php foreach (\App\Models\ExaminerModel::roleLabels() as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= old('examiner_role', $examiner['examiner_role']) === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= old('is_active', $examiner['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active (visible in assignment lists)</label>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/examiners') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Examiner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
