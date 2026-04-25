<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Institutional Researchers Directory</h5>
            <p class="text-muted small mb-0">Manage researcher profiles, institutional IDs, and academic categories.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add Researcher
            </a>
            <form action="<?= base_url('admin/researchers') ?>" method="get" class="d-flex gap-2">
                <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> Print List
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Institutional ID</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Category</th>
                    <th>Expertise</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace"><?= $r['institutional_id'] ?></span>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= $r['fullname'] ?? '<span class="text-muted">N/A</span>' ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <?= strtoupper(substr($r['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="small text-muted"><?= $r['username'] ?></div>
                                        <div class="text-muted small" style="font-size: 0.7rem;"><?= $r['email'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                    <?= $r['category_name'] ?? 'Other' ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 200px;" title="<?= $r['expertise'] ?>">
                                    <?= $r['expertise'] ?? '<span class="text-muted italic">Not specified</span>' ?>
                                </div>
                            </td>
                            <td class="small text-muted">
                                <?= $r['joined_at'] ? date('M d, Y', strtotime($r['joined_at'])) : '-' ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('admin/researchers/edit/' . $r['id']) ?>" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('admin/researchers/delete/' . $r['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this researcher profile?')"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            No researchers found in this category.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
