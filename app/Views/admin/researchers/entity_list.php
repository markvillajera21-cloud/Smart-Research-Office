<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><?= $page_title ?></h4>
    <?php if (session()->get('role') === 'admin'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-2"></i> Add New
        </button>
    <?php endif; ?>
</div>

<!-- Search Form -->
<div class="mb-4">
    <form action="" method="get" class="d-flex gap-2">
        <div class="input-group shadow-sm border rounded" style="min-width: 300px;">
            <span class="input-group-text bg-white border-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name..." value="<?= $search ?? '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if ($search): ?>
            <a href="<?= current_url() ?>" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Items Table -->
<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                    <th class="fw-semibold" style="min-width: 300px;">Name</th>
                    <th class="fw-semibold" style="width: 150px;">Created At</th>
                    <?php if (session()->get('role') === 'admin'): ?>
                        <th class="fw-semibold text-end" style="width: 150px;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="fw-medium"><?= esc($item['name']) ?></div>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <?= date('M d, Y H:i', strtotime($item['created_at'])) ?>
                                </span>
                            </td>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal<?= $item['id'] ?>">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </button></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="<?= base_url($delete_route . '/' . $item['id']) ?>" onclick="return confirm('Are you sure you want to delete this <?= strtolower($entity_name) ?>?')">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= (session()->get('role') === 'admin') ? 3 : 2 ?>" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No <?= strtolower($entity_name) ?>s found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New <?= $entity_name ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url($add_route) ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Enter name...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
<?php foreach ($items as $item): ?>
<div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit <?= $entity_name ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url($edit_route) ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <div class="mb-3">
                        <label for="editName<?= $item['id'] ?>" class="form-label fw-medium">Name</label>
                        <input type="text" name="name" id="editName<?= $item['id'] ?>" class="form-control" required value="<?= esc($item['name']) ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>