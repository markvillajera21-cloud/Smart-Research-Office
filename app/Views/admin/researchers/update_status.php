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
    <div class="mb-4 no-print">
        <h4 class="mb-1 fw-bold text-dark">Update Researcher Status</h4>
        <p class="text-muted mb-0">Quickly update the status of researcher profiles.</p>
    </div>

    <div class="d-flex flex-grow-1 gap-2 mb-4 no-print">
        <form action="<?= base_url('admin/researchers/update-status') ?>" method="get" class="d-flex gap-2 w-100">
            <div class="input-group shadow-sm border rounded">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by author, members or title..." value="<?= $search ?? '' ?>">
            </div>
            <select name="category" class="form-select shadow-sm border rounded" onchange="this.form.submit()" style="width: 220px;">
                <option value="">All Departments</option>
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
                    <th class="fw-semibold" style="min-width: 150px;">Author</th>
                    <th class="fw-semibold" style="min-width: 200px;">Members</th>
                    <th class="fw-semibold">Department</th>
                    <th class="fw-semibold">Approved Research Title</th>
                    <th class="fw-semibold">Pre Oral Defense</th>
                    <th class="fw-semibold">Final Defense</th>
                    <th class="text-end fw-semibold no-print">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
                            <td style="min-width: 150px; padding-right: 15px;">
                                <div class="fw-bold small"><?= $r['author'] ?? $r['fullname'] ?? '<span class="text-muted">N/A</span>' ?></div>
                            </td>
                            <td style="min-width: 200px; padding-right: 15px;">
                                <div class="small text-muted">
                                    <?php if (!empty($r['surname'])): ?>
                                        <?= nl2br($r['surname']) ?>
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
                                <div class="small text-muted" style="max-width: 280px;">
                                    <?php if (!empty($r['approved_research_title'])): ?>
                                        <?= (strlen($r['approved_research_title']) > 80) ? substr($r['approved_research_title'], 0, 80) . '...' : $r['approved_research_title'] ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <?php if ($r['pre_oral_defense_date']): ?>
                                        <div class="text-muted"><?= date('M d, Y', strtotime($r['pre_oral_defense_date'])) ?></div>
                                    <?php endif; ?>
                                    <?php if ($r['pre_oral_defense_status_name']): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                            <?= $r['pre_oral_defense_status_name'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 small">
                                    <?php if ($r['final_defense_date']): ?>
                                        <div class="text-muted"><?= date('M d, Y', strtotime($r['final_defense_date'])) ?></div>
                                    <?php endif; ?>
                                    <?php 
                                    $finalDefensePassed = (isset($r['final_defense_status_id']) && (int)$r['final_defense_status_id'] === 2) 
                                        || (isset($r['final_defense_status_name']) && strtolower($r['final_defense_status_name']) === 'passed');
                                    $hasAbstract = !empty(trim((string)($r['abstract'] ?? '')));
                                    ?>
                                    <?php if ($finalDefensePassed): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle text-start">
                                            <i class="bi bi-check-circle-fill me-1"></i> Final Defense: Passed
                                        </span>
                                        <?php if ($hasAbstract): ?>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle text-start">
                                                <i class="bi bi-file-earmark-check-fill me-1"></i> Abstract: Submitted &#10003;
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle text-start">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Abstract: Required
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif (!empty($r['final_defense_status_name'])): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small text-start">
                                            <?= $r['final_defense_status_name'] ?>
                                        </span>
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
                                        <li><a class="dropdown-item" href="<?= base_url('admin/researchers/edit/' . $r['id'] . '?from=update_status') ?>"><i class="bi bi-pencil me-2"></i> Edit</a></li>
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No researchers found matching your criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4 no-print">
        <div class="btn-group" role="group">
            <button onclick="printWithOrientation('landscape')" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-printer"></i> Print List
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" onclick="printWithOrientation('landscape'); return false;"><i class="bi bi-file-earmark-arrow-down me-2"></i> Landscape</a></li>
                <li><a class="dropdown-item" href="#" onclick="printWithOrientation('portrait'); return false;"><i class="bi bi-file-earmark-arrow-up me-2"></i> Portrait</a></li>
            </ul>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide unnecessary elements */
        .no-print,
        .sidebar,
        .navbar,
        header,
        footer,
        .btn,
        .dropdown {
            display: none !important;
        }

        /* Make content full width */
        .container, .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Print-only title */
        .print-only-title {
            display: block !important;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Page setup will be set by JavaScript */
        @page {
            margin: 1cm;
        }

        /* Table styling */
        .table {
            width: 100% !important;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* Prevent table rows from breaking */
        .table tr {
            page-break-inside: avoid;
        }

        /* Badges lose color for better printing */
        .badge {
            border: 1px solid #000;
            color: #000 !important;
            background-color: transparent !important;
            padding: 2px 6px;
        }

        /* Ensure colors are printed */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    /* Hide print-only title on screen */
    .print-only-title {
        display: none;
    }
</style>

<script>
    function printWithOrientation(orientation) {
        // Create a temporary style element
        const style = document.createElement('style');
        style.id = 'print-orientation-style';
        style.innerHTML = `
            @page {
                size: ${orientation};
                margin: 1cm;
            }
        `;
        document.head.appendChild(style);

        // Trigger print
        window.print();

        // Clean up - remove the temporary style after print
        setTimeout(() => {
            const existingStyle = document.getElementById('print-orientation-style');
            if (existingStyle) {
                existingStyle.remove();
            }
        }, 500);
    }
</script>

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

<div class="print-only-title">Researcher Status List</div>
<?= $this->endSection() ?>
