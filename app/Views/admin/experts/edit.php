<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2"></i>Edit Expert</h2>
            <p class="text-muted mb-0">Update expert information or status</p>
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

                    <form action="<?= base_url('admin/experts/update/' . $expert['id']) ?>" method="post" autocomplete="off">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Expert Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name', $expert['name'])) ?>" required minlength="2" autocomplete="off">
                        </div>
                        <div class="mb-4">
                            <label for="expert_role" class="form-label fw-semibold">Expert Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="expert_role" name="expert_role" required>
                                <?php foreach (\App\Models\ExpertModel::roleLabels() as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= old('expert_role', $expert['expert_role']) === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="specialization" class="form-label fw-semibold">Specialization / Subject</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" value="<?= esc(old('specialization', $expert['specialization'] ?? '')) ?>" placeholder="e.g. English, Filipino, Mathematics, Psychology" autocomplete="off">
                            <div class="form-text">Subject area, field of expertise, or discipline.</div>
                        </div>
                        <div class="mb-4">
                            <label for="department" class="form-label fw-semibold">Department / Office</label>
                            <input type="text" class="form-control" id="department" name="department" value="<?= esc(old('department', $expert['department'] ?? '')) ?>" placeholder="e.g. College Dept., High School Dept., HRDO" autocomplete="off">
                            <div class="form-text">Office, academic unit, or institutional department.</div>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= old('is_active', $expert['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active (visible in research project assignment lists)</label>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/experts') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Expert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
