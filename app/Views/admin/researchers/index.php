<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Top Action Bar -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-4 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add New
        </a>
    </div>
    
    <div class="d-flex flex-grow-1 justify-content-center mx-3">
        <form action="<?= base_url('admin/researchers') ?>" method="get" class="d-flex gap-2 w-100" style="max-width: 650px;">
            <div class="input-group shadow-sm border rounded">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name, ID, or expertise..." value="<?= $search ?? '' ?>">
            </div>
            <select name="category" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 220px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($selectedCategory || ($search ?? '')): ?>
                <a href="<?= base_url('admin/researchers') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <div>
        <button class="btn btn-outline-primary shadow-sm d-flex align-items-center px-3 py-2" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Print List
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <!-- Directory Header -->
    <div class="mb-4">
        <h4 class="mb-1 fw-bold text-dark">Institutional Research Directory</h4>
        <p class="text-muted mb-0">Manage research profiles, institutional IDs, and academic categories.</p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                        <th class="fw-semibold" style="min-width: 220px;">Approved Research Title</th>
                        <th class="fw-semibold" style="min-width: 150px;">Strand/Degree Program</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Full Name</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Category</th>
                        <th class="text-end pe-4 fw-semibold" style="width: 120px; white-space: nowrap;">Remarks</th>
                    </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
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
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                                    <?= $r['strand_degree_program'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= $r['fullname'] ?? '<span class="text-muted">N/A</span>' ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($r['username'])): ?>
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <?= strtoupper(substr($r['username'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="small text-muted"><?= $r['username'] ?></div>
                                            <div class="text-muted small" style="font-size: 0.7rem;"><?= $r['email'] ?></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">No user</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                    <?= $r['category_name'] ?? 'Other' ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="<?= base_url('admin/researchers/create') ?>"><i class="bi bi-plus-lg me-2"></i> Add</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#statusModal<?= $r['id'] ?>"><i class="bi bi-arrow-repeat me-2"></i> Update Status</button></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="<?= base_url('admin/researchers/edit/' . $r['id']) ?>"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="<?= base_url('admin/researchers/delete/' . $r['id']) ?>" onclick="return confirm('Are you sure you want to delete this research profile?')"><i class="bi bi-trash me-2"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Status Update Modal -->
                        <div class="modal fade" id="statusModal<?= $r['id'] ?>" tabindex="-1" aria-labelledby="statusModalLabel<?= $r['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?= base_url('admin/researchers/update-status/' . $r['id']) ?>" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabel<?= $r['id'] ?>">Update Status - <?= $r['fullname'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="status<?= $r['id'] ?>" class="form-label fw-semibold">Select New Status</label>
                                                <select class="form-select" id="status<?= $r['id'] ?>" name="status" required>
                                                    <option value="active" <?= ($r['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                                    <option value="inactive" <?= ($r['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    <option value="on_leave" <?= ($r['status'] ?? '') === 'on_leave' ? 'selected' : '' ?>>On Leave</option>
                                                    <option value="completed" <?= ($r['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
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
