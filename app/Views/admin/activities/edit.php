<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2"></i>Edit Activity Schedule</h2>
            <p class="text-muted mb-0">Update approved schedule dates or status for this activity</p>
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
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/activities/update/' . $activity['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="activity_type" class="form-label fw-semibold">Activity / Defense Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="activity_type" name="activity_type" required>
                                <?php foreach (\App\Models\CalendarActivityModel::typeLabels() as $val => $lbl): ?>
                                    <option value="<?= esc($val) ?>" <?= old('activity_type', $activity['activity_type']) === $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Activity Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= esc(old('title', $activity['title'])) ?>" required minlength="3">
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= esc(old('start_date', $activity['start_date'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= esc(old('end_date', $activity['end_date'])) ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="academic_year" class="form-label fw-semibold">Academic Year</label>
                                <input type="text" class="form-control" id="academic_year" name="academic_year" value="<?= esc(old('academic_year', $activity['academic_year'])) ?>" placeholder="e.g. 2025-2026">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <?php foreach (\App\Models\CalendarActivityModel::statusLabels() as $val => $lbl): ?>
                                        <option value="<?= esc($val) ?>" <?= old('status', $activity['status']) === $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description / Remarks</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= esc(old('description', $activity['description'])) ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/activities') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Activity Schedule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
