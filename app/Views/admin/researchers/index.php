<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Top Action Bar -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print flex-wrap gap-3">
    <div>
        <?php if (is_research_office()): ?>
        <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-4 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add New
        </a>
        <?php endif; ?>
    </div>
    
    <div class="d-flex flex-grow-1 justify-content-center mx-1">
        <form action="<?= base_url('admin/researchers') ?>" method="get" class="d-flex gap-2 w-100 flex-wrap" style="max-width: 1600px;">
            <!-- Student Search Box -->
            <div class="input-group shadow-sm border rounded bg-white" style="min-width: 280px; flex: 1;">
                <span class="input-group-text bg-white border-0 text-primary">
                    <i class="bi bi-person-badge"></i>
                </span>
                <input type="text" name="student_search" class="form-control border-0 ps-0" placeholder="Search by student name, Student ID, or Login ID..." value="<?= esc($studentSearch ?? '') ?>">
            </div>

            <!-- General Keyword Search -->
            <div class="input-group shadow-sm border rounded bg-white" style="min-width: 200px; flex: 1;">
                <span class="input-group-text bg-white border-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search research title, track..." value="<?= esc($search ?? '') ?>">
            </div>

            <select name="school_year" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 140px;">
                <option value="">School Year</option>
                <?php foreach ($schoolYears as $sy): ?>
                    <option value="<?= $sy['id'] ?>" <?= ($selectedSchoolYear ?? '') == $sy['id'] ? 'selected' : '' ?>><?= $sy['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="category" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 140px;">
                <option value="">Department</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="strand" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 160px;">
                <option value="">Track / Strand</option>
                <?php foreach ($strands as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($selectedStrand ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="adviser" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 130px;">
                <option value="">Adviser</option>
                <?php foreach ($advisers as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($selectedAdviser ?? '') == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="grammarian" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 130px;">
                <option value="">Grammarian</option>
                <?php foreach ($grammarians as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= ($selectedGrammarian ?? '') == $g['id'] ? 'selected' : '' ?>><?= $g['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="statistician" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 130px;">
                <option value="">Statisticians</option>
                <?php foreach ($statisticians as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($selectedStatistician ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="research_teacher" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 140px;">
                <option value="">Research Teacher</option>
                <?php foreach ($researchTeachers as $rt): ?>
                    <option value="<?= $rt['id'] ?>" <?= ($selectedResearchTeacher ?? '') == $rt['id'] ? 'selected' : '' ?>><?= $rt['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary shadow-sm" title="Search">
                <i class="bi bi-search"></i>
            </button>
            
            <?php if (!empty($selectedCategory) || !empty($selectedSchoolYear) || !empty($selectedStrand) || !empty($search) || !empty($studentSearch) || !empty($selectedAdviser) || !empty($selectedGrammarian) || !empty($selectedStatistician) || !empty($selectedResearchTeacher)): ?>
                <a href="<?= base_url('admin/researchers') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <!-- Directory Header -->
    <div class="mb-3 no-print d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold text-dark">Institutional Research Directory</h4>
            <p class="text-muted mb-0">Manage research profiles, institutional IDs, student records, and academic categories.</p>
        </div>
        <span class="badge bg-light text-dark border px-3 py-2">
            Total Records: <?= count($researchers ?? []) ?>
        </span>
    </div>

    <!-- Active Student Search Results Banner -->
    <?php if (!empty($studentSearch)): ?>
        <div class="alert alert-primary bg-primary-subtle text-primary-emphasis border-0 shadow-sm py-2 px-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2 no-print">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-check-fill fs-5 me-2 text-primary"></i>
                <div>
                    <strong>Student Search Results:</strong> Showing research records for student name or Student ID / Login ID: <strong>"<?= esc($studentSearch) ?>"</strong>
                    <span class="badge bg-primary text-white ms-2"><?= count($researchers) ?> record<?= count($researchers) !== 1 ? 's' : '' ?> found</span>
                </div>
            </div>
            <a href="<?= base_url('admin/researchers') ?>" class="btn btn-sm btn-outline-primary py-1 px-3">
                <i class="bi bi-x-circle me-1"></i> Clear Student Filter
            </a>
        </div>
    <?php endif; ?>
    
    <!-- Print-only Title -->
    <div class="mb-4 print-title" style="display: none;">
        <h2 class="fw-bold text-center text-dark">Institutional Research Directory</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                        <th class="fw-semibold" style="min-width: 140px;">Department</th>
                        <th class="fw-semibold" style="min-width: 150px;">Program/Academic Track</th>
                        <th class="fw-semibold" style="min-width: 250px;">Author / Student</th>
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
                            <td style="padding: 16px 20px; line-height: 1.6; min-width: 250px;">
                                <a href="<?= base_url('admin/researchers/edit/' . $r['id']) ?>" class="text-decoration-none" title="Click to access profile">
                                    <div class="fw-bold small text-primary">
                                        <?php if (!empty($r['author'])): ?>
                                            <?= nl2br(preg_replace('/,\s*/', "\n", esc($r['author']))) ?>
                                        <?php elseif (!empty($r['fullname'])): ?>
                                            <?= esc($r['fullname']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <?php if (!empty($r['username'])): ?>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-primary border text-xs" title="Student ID / Login ID">
                                            <i class="bi bi-person-badge me-1"></i>ID: <?= esc($r['username']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
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
                                <?php if (is_research_office()): ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item" href="<?= base_url('admin/researchers/create') ?>"><i class="bi bi-plus-lg me-2"></i> Add</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/researchers/edit/' . $r['id']) ?>"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                        <li><a class="dropdown-item assign-researcher-teacher-btn" href="#" data-id="<?= $r['id'] ?>" data-name="<?= esc($r['fullname'] ?: ($r['author'] ?? 'Researcher'), 'attr') ?>" data-teacher-id="<?= $r['research_teacher_id'] ?? '' ?>"><i class="bi bi-person-video3 me-2 text-primary"></i> Assign Teacher/Research Instructor</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item view-abstract-btn" href="#" data-id="<?= $r['id'] ?>"><i class="bi bi-eye me-2 text-info"></i> View Abstract</a></li>
                                        <li><a class="dropdown-item submit-abstract-btn" href="#" data-id="<?= $r['id'] ?>" data-name="<?= esc($r['fullname'] ?: ($r['surname'] . ', ' . $r['first_name']), 'attr') ?>" data-title="<?= esc($r['approved_research_title'] ?? '', 'attr') ?>" data-abstract="<?= esc($r['abstract'] ?? '', 'attr') ?>"><i class="bi bi-file-earmark-text me-2 text-primary"></i> Abstract Submission</a></li>
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
                    <i class="bi bi-file-earmark-fill me-2"></i> Portrait
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- ==========================================
     View Abstract Modal
=========================================== -->
<div class="modal fade" id="viewAbstractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i>Research Abstract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="viewAbstractBody">
                <div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button class="btn btn-light border btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     Submit Abstract Modal
=========================================== -->
<div class="modal fade" id="submitAbstractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="post" id="submitAbstractForm" action="">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Abstract Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 mb-3 small border-0">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Abstract Requirement:</strong> Researchers are required to submit an approved abstract after passing the Final Defense before the research project can be marked as completed.
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Researcher / Author</label>
                        <input type="text" id="modalResearcherName" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Approved Research Title</label>
                        <input type="text" id="modalResearchTitle" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Abstract Content <span class="text-danger">*</span></label>
                        <textarea name="abstract" id="modalAbstractText" class="form-control" rows="8" required placeholder="Enter or paste the complete research abstract here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 gap-2">
                    <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check2-circle me-1"></i> Save & Submit Abstract</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.view-abstract-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const modalBody = document.getElementById('viewAbstractBody');
        modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
        new bootstrap.Modal(document.getElementById('viewAbstractModal')).show();

        fetch('<?= base_url('admin/researchers/ajax-abstract/') ?>' + id, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
            .then(r => r.json())
            .then(data => {
                if (data.status !== 'success') {
                    modalBody.innerHTML = '<div class="alert alert-danger">Unable to load abstract record.</div>';
                    return;
                }
                const hasAb = data.has_abstract;
                modalBody.innerHTML = `
                    <div class="mb-3 border-bottom pb-3">
                        <h6 class="fw-bold mb-1">${data.title || '(Untitled Research)'}</h6>
                        <div class="text-muted small"><strong>Researcher:</strong> ${data.fullname}</div>
                        <div class="text-muted small"><strong>Final Defense Status:</strong> <span class="badge ${data.final_defense_status === 'Passed' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}">${data.final_defense_status}</span> &bull; <strong>Date:</strong> ${data.final_defense_date}</div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold small text-primary"><i class="bi bi-file-text me-1"></i>Abstract Submission:</span>
                            <span class="badge ${hasAb ? 'bg-success' : 'bg-warning text-dark'}">${hasAb ? 'Submitted' : 'Pending Submission'}</span>
                        </div>
                        <div class="p-3 bg-light rounded small" style="line-height: 1.8; white-space: pre-wrap; max-height: 350px; overflow-y: auto;">${hasAb ? data.abstract : '<em class="text-muted">No abstract submitted yet. An abstract is required after passing Final Defense.</em>'}</div>
                    </div>
                `;
            });
    });
});

document.querySelectorAll('.submit-abstract-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const name = this.dataset.name;
        const title = this.dataset.title;
        const abstract = this.dataset.abstract;

        document.getElementById('submitAbstractForm').action = '<?= base_url('admin/researchers/submit-abstract/') ?>' + id;
        document.getElementById('modalResearcherName').value = name;
        document.getElementById('modalResearchTitle').value = title || '(No approved title recorded)';
        document.getElementById('modalAbstractText').value = abstract || '';

        new bootstrap.Modal(document.getElementById('submitAbstractModal')).show();
    });
});
</script>
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

document.querySelectorAll('.assign-researcher-teacher-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const name = this.dataset.name;
        const teacherId = this.dataset.teacherId || '';

        document.getElementById('assignResearcherTeacherForm').action = '<?= base_url('admin/researchers/assign-teacher/') ?>' + id;
        document.getElementById('assignResearcherNameDisplay').textContent = name;
        document.getElementById('assignResearcherTeacherSelect').value = teacherId;

        new bootstrap.Modal(document.getElementById('assignResearcherTeacherModal')).show();
    });
});
</script>

<!-- Assign Teacher / Research Instructor Modal (Research List) -->
<div class="modal fade" id="assignResearcherTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="assignResearcherTeacherForm" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-person-video3 me-2"></i>Assign Teacher/Research Instructor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 p-3 bg-light rounded border">
                        <div class="small text-muted mb-1">Researcher / Student:</div>
                        <div class="fw-bold text-dark" id="assignResearcherNameDisplay"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Teacher/Research Instructor <span class="text-danger">*</span></label>
                        <select name="research_teacher_id" id="assignResearcherTeacherSelect" class="form-select shadow-sm" required>
                            <option value="">-- Select Teacher/Research Instructor --</option>
                            <?php foreach ($researchTeachers ?? [] as $rt): ?>
                                <option value="<?= $rt['id'] ?>"><?= esc($rt['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text small">Assigns the research instructor responsible for advising and managing this research record.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check2-circle me-1"></i> Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
