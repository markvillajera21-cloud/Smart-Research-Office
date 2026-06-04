<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark">Upload Data</h4>
        <p class="text-muted mb-0">Upload CSV/Excel/JSON and other supported files.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-3">Upload a file</h6>
            <form action="<?= base_url('admin/uploads/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-medium">Choose file</label>
                    <input type="file" name="data_file" class="form-control" required>
                    <div class="form-text text-muted small">
                        Max <?= esc((string)($maxUploadMb ?? 25)) ?> MB. Blocked: <?= esc(implode(', ', $blockedExtensions ?? ['php','exe'])) ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-cloud-arrow-up me-2"></i> Upload
                </button>
            </form>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Recent uploads</h6>
                <span class="badge bg-light text-dark border"><?= count($files ?? []) ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr class="text-uppercase text-muted small">
                            <th class="fw-semibold">File</th>
                            <th class="fw-semibold" style="width: 120px;">Size</th>
                            <th class="fw-semibold" style="width: 180px;">Modified</th>
                            <th class="fw-semibold text-end" style="width: 90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($files)): ?>
                            <?php foreach ($files as $f): ?>
                                <tr>
                                    <td class="font-monospace small">
                                    <a href="<?= base_url('admin/uploads/view/' . $f['name']) ?>" target="_blank" class="text-decoration-none"><?= esc($f['name'] ?? '') ?></a>
                                </td>
                                    <td class="small text-muted"><?= number_format(($f['size'] ?? 0) / 1024, 1) ?> KB</td>
                                    <td class="small text-muted"><?= !empty($f['modified']) ? date('M d, Y h:i A', (int) $f['modified']) : '-' ?></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/uploads/view/' . $f['name']) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/uploads/view/' . $f['name']) ?>" download class="btn btn-sm btn-outline-success me-1" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="<?= base_url('admin/uploads/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this upload?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="name" value="<?= esc($f['name'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No uploads yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

