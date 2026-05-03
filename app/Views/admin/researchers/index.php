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
        <form action="<?= base_url('admin/researchers') ?>" method="get" class="d-flex gap-2 w-100" style="max-width: 1100px;">
            <div class="input-group shadow-sm border rounded">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name, ID, or expertise..." value="<?= $search ?? '' ?>">
            </div>
            <select name="school_year" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 180px;">
                <option value="">School Year</option>
                <?php foreach ($schoolYears as $sy): ?>
                    <option value="<?= $sy['id'] ?>" <?= ($selectedSchoolYear ?? '') == $sy['id'] ? 'selected' : '' ?>><?= $sy['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="category" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 180px;">
                <option value="">Department</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="strand" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 220px;">
                <option value="">Program/Career Pathways</option>
                <?php foreach ($strands as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($selectedStrand ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="sort" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 180px;">
                <option value="">Sorting</option>
                <option value="name" <?= ($sort ?? '') == 'name' ? 'selected' : '' ?>>Name</option>
                <option value="designation" <?= ($sort ?? '') == 'designation' ? 'selected' : '' ?>>Designation</option>
                <option value="category" <?= ($sort ?? '') == 'category' ? 'selected' : '' ?>>Category</option>
                <option value="course" <?= ($sort ?? '') == 'course' ? 'selected' : '' ?>>Course</option>
                <option value="approved_title" <?= ($sort ?? '') == 'approved_title' ? 'selected' : '' ?>>Approve Research Title</option>
                <option value="approved_date" <?= ($sort ?? '') == 'approved_date' ? 'selected' : '' ?>>Approve Date</option>
                <option value="remarks" <?= ($sort ?? '') == 'remarks' ? 'selected' : '' ?>>Remarks</option>
                <option value="abstract" <?= ($sort ?? '') == 'abstract' ? 'selected' : '' ?>>Abstract</option>
                <option value="status" <?= ($sort ?? '') == 'status' ? 'selected' : '' ?>>Status</option>
                <option value="joining_date" <?= ($sort ?? '') == 'joining_date' ? 'selected' : '' ?>>Joining Date</option>
                <option value="bio" <?= ($sort ?? '') == 'bio' ? 'selected' : '' ?>>Short Bio</option>
                <option value="department" <?= ($sort ?? '') == 'department' ? 'selected' : '' ?>>Department</option>
                <option value="adviser" <?= ($sort ?? '') == 'adviser' ? 'selected' : '' ?>>Adviser</option>
                <option value="grammarian" <?= ($sort ?? '') == 'grammarian' ? 'selected' : '' ?>>Grammarian</option>
                <option value="degree" <?= ($sort ?? '') == 'degree' ? 'selected' : '' ?>>Program/Career Pathways</option>
                <option value="school_year" <?= ($sort ?? '') == 'school_year' ? 'selected' : '' ?>>School Year</option>
                <option value="date" <?= ($sort ?? '') == 'date' ? 'selected' : '' ?>>Date</option>
            </select>
            <?php if ($selectedCategory || ($selectedSchoolYear ?? '') || ($selectedStrand ?? '') || ($search ?? '') || ($sort ?? '')): ?>
                <a href="<?= base_url('admin/researchers') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
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
                        <th class="fw-semibold" style="min-width: 150px;">Program/Career Pathways</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Full Name</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Category</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Current Status</th>
                        <th class="text-end fw-semibold" style="width: 120px; white-space: nowrap;">Remarks</th>
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
                                    <?= $r['strand_name'] ?? $r['strand_degree_program'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= $r['fullname'] ?? '<span class="text-muted">N/A</span>' ?></div>
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
                                        <li><hr class="dropdown-divider"></li>
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
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No researchers found matching your criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4 d-flex justify-content-end">
        <button class="btn btn-outline-primary shadow-sm d-flex align-items-center px-3 py-2" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Print List
        </button>
    </div>
</div>

<!-- Sorting Management Modal -->
<div class="modal fade" id="sortingManagementModal" tabindex="-1" aria-labelledby="sortingManagementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sortingManagementModalLabel">Manage Entities</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="mb-3 fw-semibold">Departments / Categories</h6>
                        <form action="<?= base_url('admin/researchers/add-category') ?>" method="post" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="e.g. Senior High" required>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                        <ul class="list-group">
                            <?php foreach ($categories as $cat): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= $cat['name'] ?></span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $cat['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('admin/researchers/delete-category/' . $cat['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3 fw-semibold">Advisers</h6>
                        <form action="<?= base_url('admin/researchers/add-adviser') ?>" method="post" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Smith" required>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                        <ul class="list-group">
                            <?php foreach ($advisers as $adv): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= $adv['name'] ?></span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAdviserModal<?= $adv['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('admin/researchers/delete-adviser/' . $adv['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3 fw-semibold">Grammarians</h6>
                        <form action="<?= base_url('admin/researchers/add-grammarian') ?>" method="post" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="e.g. Ms. Johnson" required>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                        <ul class="list-group">
                            <?php foreach ($grammarians as $gram): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= $gram['name'] ?></span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editGrammarianModal<?= $gram['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('admin/researchers/delete-grammarian/' . $gram['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3 fw-semibold">Program/Career Pathways</h6>
                        <form action="<?= base_url('admin/researchers/add-strand') ?>" method="post" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="e.g. STEM" required>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                        <ul class="list-group">
                            <?php foreach ($strands as $strand): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= $strand['name'] ?></span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editStrandModal<?= $strand['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('admin/researchers/delete-strand/' . $strand['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modals -->
<?php foreach ($categories as $cat): ?>
<div class="modal fade" id="editCategoryModal<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/researchers/edit-category') ?>" method="post">
                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department/Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" value="<?= $cat['name'] ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Edit Adviser Modals -->
<?php foreach ($advisers as $adv): ?>
<div class="modal fade" id="editAdviserModal<?= $adv['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/researchers/edit-adviser') ?>" method="post">
                <input type="hidden" name="id" value="<?= $adv['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Adviser</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" value="<?= $adv['name'] ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Edit Grammarian Modals -->
<?php foreach ($grammarians as $gram): ?>
<div class="modal fade" id="editGrammarianModal<?= $gram['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/researchers/edit-grammarian') ?>" method="post">
                <input type="hidden" name="id" value="<?= $gram['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Grammarian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" value="<?= $gram['name'] ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Edit Strand Modals -->
<?php foreach ($strands as $strand): ?>
<div class="modal fade" id="editStrandModal<?= $strand['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/researchers/edit-strand') ?>" method="post">
                <input type="hidden" name="id" value="<?= $strand['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Program/Career Pathways</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" value="<?= $strand['name'] ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>
