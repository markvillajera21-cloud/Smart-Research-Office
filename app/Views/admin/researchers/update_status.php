<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= base_url('admin/researchers') ?>" class="btn btn-light shadow-sm d-flex align-items-center px-4 py-2 mb-3">
            <i class="bi bi-arrow-left me-2"></i> Back to Research List
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <div class="mb-4">
        <h4 class="mb-1 fw-bold text-dark">Update Researcher Status</h4>
        <p class="text-muted mb-0">Quickly update the status of researcher profiles.</p>
    </div>

    <div class="d-flex flex-grow-1 gap-2 mb-4">
        <form action="<?= base_url('admin/researchers/update-status') ?>" method="get" class="d-flex gap-2 w-100">
            <div class="input-group shadow-sm border rounded">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name or title..." value="<?= $search ?? '' ?>">
            </div>
            <select name="category" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 220px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($selectedCategory || ($search ?? '')): ?>
                <a href="<?= base_url('admin/researchers/update-status') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                    <th class="fw-semibold">Full Name</th>
                    <th class="fw-semibold">Approved Research Title</th>
                    <th class="fw-semibold">Category</th>
                    <th class="fw-semibold">Current Status</th>
                    <th class="text-end fw-semibold">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
                            <td>
                                <div class="fw-bold small"><?= $r['fullname'] ?? '<span class="text-muted">N/A</span>' ?></div>
                            </td>
                            <td>
                                <div class="small text-muted" style="max-width: 280px;">
                                    <?php if (!empty($r['approved_research_title'])): ?>
                                        <?= (strlen($r['approved_research_title']) > 80) ? substr($r['approved_research_title'], 0, 80) . '...' : $r['approved_research_title'] ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                    <?= $r['category_name'] ?? 'Other' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                                    <?= $r['status_name'] ?? 'Not Set' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?= base_url('admin/researchers/create') ?>"><i class="bi bi-plus-lg me-2"></i> Add</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/researchers/edit/' . $r['id']) ?>"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="<?= base_url('admin/researchers/delete/' . $r['id']) ?>" onclick="return confirm('Are you sure you want to delete this research profile?')"><i class="bi bi-trash me-2"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No researchers found matching your criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
