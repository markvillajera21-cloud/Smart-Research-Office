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
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name or title..." value="<?= $search ?? '' ?>">
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
                    <th class="fw-semibold">Full Name</th>
                    <th class="fw-semibold">Department</th>
                    <th class="fw-semibold">Approved Research Title</th>
                    <th class="fw-semibold">Pre Oral Defense</th>
                    <th class="fw-semibold">Final Defense</th>
                    <th class="fw-semibold">Current Status</th>
                    <th class="text-end fw-semibold no-print">Remarks</th>
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
                                <div class="small">
                                    <?php if ($r['final_defense_date']): ?>
                                        <div class="text-muted"><?= date('M d, Y', strtotime($r['final_defense_date'])) ?></div>
                                    <?php endif; ?>
                                    <?php if ($r['final_defense_status_name']): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                            <?= $r['final_defense_status_name'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                                    <?= $r['status_name'] ?? 'Not Set' ?>
                                </span>
                            </td>
                            <td class="text-end no-print">
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
                        <td colspan="6" class="text-center py-5 text-muted">
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

<div class="print-only-title">Researcher Status List</div>
<?= $this->endSection() ?>
