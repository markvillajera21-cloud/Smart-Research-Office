<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Top Action Bar -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-3 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add Researcher
        </a>
    </div>
    
    <div class="d-flex flex-grow-1 justify-content-center mx-3">
        <form action="<?= base_url('admin/researchers/high-school') ?>" method="get" class="d-flex gap-2 w-100" style="max-width: 650px;">
            <div class="input-group shadow-sm border rounded">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name, ID, or expertise..." value="<?= $search ?? '' ?>">
            </div>
            <?php if ($search ?? ''): ?>
                <a href="<?= base_url('admin/researchers/high-school') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
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
        <h4 class="mb-1 fw-bold text-dark">High School Department</h4>
        <p class="text-muted mb-0">Manage high school department researchers.</p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                        <th class="ps-4">APPROVED RESEARCH TITLE</th>
                        <th>STRAND/DEGREE PROGRAM</th>
                        <th>FULL NAME</th>
                        <th style="width: 150px;">SCHOOL YEAR</th>
                        <th>JOINED</th>
                        <th class="text-end pe-4" style="width: 120px;">ACTIONS</th>
                    </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
                            <td>
                                <div class="small text-muted" style="max-width: 300px;">
                                    <?= $r['approved_research_title'] ? (strlen($r['approved_research_title']) > 80 ? substr($r['approved_research_title'], 0, 80) . '...' : $r['approved_research_title']) : 'N/A' ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                                    <?= $r['strand_degree_program'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= $r['fullname'] ?? '<span class="text-muted">N/A</span>' ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace"><?= $r['school_year'] ?? 'N/A' ?></span>
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
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            No researchers found in High School Department.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>