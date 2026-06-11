<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Top Action Bar -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <?php if (session()->get('role') === 'admin'): ?>
        <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-4 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add New
        </a>
        <?php endif; ?>
    </div>
    
    <div class="d-flex flex-grow-1 justify-content-center mx-3">
        <form action="<?= base_url('admin/researchers') ?>" method="get" class="d-flex gap-2 w-100 flex-wrap" style="max-width: 1600px;">
            <div class="input-group shadow-sm border rounded" style="min-width: 300px;">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by author, members, research title, name, ID, or email..." value="<?= $search ?? '' ?>">
            </div>
            <select name="school_year" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 150px;">
                <option value="">School Year</option>
                <?php foreach ($schoolYears as $sy): ?>
                    <option value="<?= $sy['id'] ?>" <?= ($selectedSchoolYear ?? '') == $sy['id'] ? 'selected' : '' ?>><?= $sy['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="category" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 150px;">
                <option value="">Select Department</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="strand" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 180px;">
                <option value="">Program/Academic Track</option>
                <?php foreach ($strands as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($selectedStrand ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="adviser" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 150px;">
                <option value="">Adviser</option>
                <?php foreach ($advisers as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($selectedAdviser ?? '') == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="grammarian" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 150px;">
                <option value="">Grammarian</option>
                <?php foreach ($grammarians as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= ($selectedGrammarian ?? '') == $g['id'] ? 'selected' : '' ?>><?= $g['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="statistician" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 150px;">
                <option value="">Statisticians</option>
                <?php foreach ($statisticians as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($selectedStatistician ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="research_teacher" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 150px;">
                <option value="">Research Teacher</option>
                <?php foreach ($researchTeachers as $rt): ?>
                    <option value="<?= $rt['id'] ?>" <?= ($selectedResearchTeacher ?? '') == $rt['id'] ? 'selected' : '' ?>><?= $rt['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <?php if ($selectedCategory || ($selectedSchoolYear ?? '') || ($selectedStrand ?? '') || ($search ?? '') || ($selectedAdviser ?? '') || ($selectedGrammarian ?? '') || ($selectedStatistician ?? '') || ($selectedResearchTeacher ?? '')): ?>
                <a href="<?= base_url('admin/researchers') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <!-- Directory Header -->
    <div class="mb-4 no-print">
        <h4 class="mb-1 fw-bold text-dark">Institutional Research Directory</h4>
        <p class="text-muted mb-0">Manage research profiles, institutional IDs, and academic categories.</p>
    </div>
    
    <!-- Print-only Title -->
    <div class="mb-4 print-title" style="display: none;">
        <h2 class="fw-bold text-center text-dark">Institutional Research Directory</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                        <th class="fw-semibold" style="min-width: 150px;">Department</th>
                        <th class="fw-semibold" style="min-width: 150px;">Program/Academic Track</th>
                        <th class="fw-semibold" style="min-width: 250px;">Author</th>
                        <th class="fw-semibold" style="min-width: 300px; padding-left: 30px;">Members</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Adviser</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Grammarian</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Statisticians</th>
                        <th class="fw-semibold" style="white-space: nowrap;">Research Teacher</th>
                        <th class="fw-semibold" style="min-width: 220px;">Approved Research Title</th>
                        <th class="text-end fw-semibold no-print" style="width: 120px; white-space: nowrap;">Remarks</th>
                    </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                    <?= $r['category_name'] ?? 'Other' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                                    <?= $r['strand_name'] ?? $r['strand_degree_program'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td style="padding: 20px 24px; line-height: 2; min-width: 250px;">
                                <div class="fw-bold small">
                                    <?php if (!empty($r['author'])): ?>
                                        <?= nl2br(preg_replace('/,\s*/', "\n", esc($r['author']))) ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="padding: 20px 24px 20px 54px; line-height: 2; min-width: 300px;">
                                <div class="fw-bold small">
                                    <?php if (!empty($r['surname'])): ?>
                                        <?= nl2br(preg_replace('/,\s*/', "\n", esc($r['surname']))) ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">
                                    <?= $r['adviser_name'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small">
                                    <?= $r['grammarian_name'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small">
                                    <?= $r['statistician_name'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 small">
                                    <?= $r['research_teacher_name'] ?? 'N/A' ?>
                                </span>
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
                            <td class="text-end no-print">
                                <?php if (session()->get('role') === 'admin'): ?>
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
                                <?php endif; ?>
                            </td>
                        </tr>


                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No researchers found matching your criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4 d-flex justify-content-end no-print">
        <div class="btn-group">
            <button class="btn btn-outline-primary shadow-sm d-flex align-items-center px-3 py-2" onclick="printWithOrientation()">
                <i class="bi bi-printer me-2"></i> Print List
            </button>
            <button type="button" class="btn btn-outline-primary shadow-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" onclick="document.getElementById('printOrientation').value='landscape'; printWithOrientation(); return false;">
                    <i class="bi bi-file-earmark-fill me-2"></i> Landscape
                </a></li>
                <li><a class="dropdown-item" href="#" onclick="document.getElementById('printOrientation').value='portrait'; printWithOrientation(); return false;">
                    <i class="bi bi-file-earmark me-2"></i> Portrait
                </a></li>
            </ul>
        </div>
        <input type="hidden" id="printOrientation" value="landscape">
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
                        <h6 class="mb-3 fw-semibold">Departments</h6>
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
                        <h6 class="mb-3 fw-semibold">Program/Academic Track</h6>
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
                    <h5 class="modal-title">Edit Department</h5>
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
                    <h5 class="modal-title">Edit Program/Academic Track</h5>
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


<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    /* Remove sidebar and header for printing */
    aside, header {
        display: none !important;
    }
    
    /* Make main content full width */
    main, .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Card styles for print */
    .card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    
    /* Table responsive for print */
    .table-responsive {
        overflow-x: visible !important;
        overflow: visible !important;
        width: 100% !important;
    }
    
    .table {
        width: 100% !important;
        font-size: 12px !important;
    }
    
    /* Table header for print */
    .table thead th {
        background-color: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 2px solid #dee2e6 !important;
        padding: 8px !important;
    }
    
    /* Table rows for print */
    .table tbody tr {
        page-break-inside: avoid !important;
    }
    
    .table td {
        padding: 6px !important;
        vertical-align: top !important;
    }
    
    /* Badges for print (remove background, just text) */
    .badge {
        background: none !important;
        color: #000 !important;
        border: none !important;
        padding: 0 !important;
        font-size: 12px !important;
    }
    
    body {
        background: white !important;
        color: black !important;
        font-size: 12px !important;
        overflow-x: hidden !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Page margins */
    @page {
        margin: 1cm;
    }
    
    /* Show title when printing */
    .print-title {
        display: block !important;
        text-align: center !important;
        margin-bottom: 20px !important;
    }
}
</style>

<script>
function printWithOrientation() {
    var orientation = document.getElementById('printOrientation').value;
    
    // Create a style element for page size
    var style = document.createElement('style');
    style.innerHTML = '@page { size: ' + orientation + '; margin: 1cm; }';
    document.head.appendChild(style);
    
    // Trigger print
    window.print();
    
    // Remove the style after printing to not affect normal view
    setTimeout(function() {
        document.head.removeChild(style);
    }, 1000);
}
</script>
<?= $this->endSection() ?>
