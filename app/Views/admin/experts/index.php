<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card p-4 border-0 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h5 class="mb-1 fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Expert Registration Pool</h5>
            <p class="text-muted small mb-0">Register and manage advisers, grammarians, and statisticians for research project assignments.</p>
        </div>
        <a href="<?= base_url('admin/experts/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-3 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add Expert
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($poolMissing)): ?>
        <div class="alert alert-warning border-0">Run database migrations to create the expert pool tables.</div>
    <?php endif; ?>

    <!-- Filter Form -->
    <form id="expertFilterForm" method="get" action="<?= base_url('admin/experts') ?>" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Filter by Role</label>
            <select name="role" id="roleFilterSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Roles</option>
                <?php foreach (\App\Models\ExpertModel::roleLabels() as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= ($roleFilter ?? '') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Filter by Status</label>
            <select name="status" id="statusFilterSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($statusFilter ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold mb-1">Search Name / Subject / Dept</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="expertSearchInput" class="form-control" placeholder="Search expert, specialization, department..." value="" autocomplete="off">
            </div>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-primary btn-sm flex-grow-1" type="submit">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <?php if (!empty($roleFilter) || !empty($statusFilter)): ?>
                <a href="<?= base_url('admin/experts') ?>" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="expertsTable">
            <thead class="table-light text-uppercase small text-muted">
                <tr>
                    <th class="ps-3" style="width: 50px;">#</th>
                    <th>Expert Name</th>
                    <th>Role</th>
                    <th>Specialization / Subject</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody id="expertsTableBody">
                <?php if (empty($experts)): ?>
                    <tr id="emptyRow">
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-people display-6 mb-2 d-block text-secondary opacity-50"></i>
                            No experts found matching the selected criteria.
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($experts as $i => $expert): ?>
                    <?php
                    $roleKey = strtolower($expert['expert_role'] ?? '');
                    $statusKey = (int) ($expert['is_active'] ?? 0) === 1 ? 'active' : 'inactive';
                    $roleBadges = [
                        'adviser'     => 'bg-primary-subtle text-primary border border-primary border-opacity-25',
                        'grammarian'  => 'bg-info-subtle text-info-emphasis border border-info border-opacity-25',
                        'statistician'=> 'bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25',
                    ];
                    $badgeClass = $roleBadges[$roleKey] ?? 'bg-secondary-subtle text-secondary';
                    ?>
                    <tr class="expert-row" 
                        data-role="<?= esc($roleKey, 'attr') ?>" 
                        data-status="<?= esc($statusKey, 'attr') ?>"
                        data-search="<?= esc(strtolower($expert['name'] . ' ' . ($expert['specialization'] ?? '') . ' ' . ($expert['department'] ?? '')), 'attr') ?>">
                        <td class="ps-3 text-muted small row-num"><?= $i + 1 ?></td>
                        <td class="fw-semibold">
                            <i class="bi bi-person me-1 text-muted"></i><?= esc($expert['name']) ?>
                        </td>
                        <td>
                            <span class="badge <?= $badgeClass ?> px-2 py-1">
                                <?= esc(\App\Models\ExpertModel::roleLabel($expert['expert_role'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($expert['specialization'])): ?>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-book me-1 text-primary"></i><?= esc($expert['specialization']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($expert['department'])): ?>
                                <span class="small text-secondary fw-medium">
                                    <i class="bi bi-building me-1 text-muted"></i><?= esc($expert['department']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int) ($expert['is_active'] ?? 0) === 1): ?>
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-25">
                                    <i class="bi bi-slash-circle me-1"></i>Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= !empty($expert['created_at']) ? date('M d, Y', strtotime($expert['created_at'])) : 'N/A' ?>
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= base_url('admin/experts/edit/' . $expert['id']) ?>">
                                            <i class="bi bi-pencil me-2 text-primary"></i> Edit Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= base_url('admin/experts/toggle/' . $expert['id']) ?>">
                                            <i class="bi bi-slash-circle me-2"></i>
                                            <?= (int) ($expert['is_active'] ?? 0) === 1 ? 'Deactivate' : 'Reactivate' ?>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="<?= base_url('admin/experts/delete/' . $expert['id']) ?>" onclick="return confirm('Remove this expert from the pool?')">
                                            <i class="bi bi-trash me-2"></i> Remove
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('expertSearchInput');
    const roleSelect = document.getElementById('roleFilterSelect');
    const statusSelect = document.getElementById('statusFilterSelect');
    const rows = document.querySelectorAll('.expert-row');

    function applyFilters() {
        const searchVal = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const roleVal = (roleSelect ? roleSelect.value : '').toLowerCase().trim();
        const statusVal = (statusSelect ? statusSelect.value : '').toLowerCase().trim();

        let visibleCount = 0;

        rows.forEach(row => {
            const rowRole = row.getAttribute('data-role') || '';
            const rowStatus = row.getAttribute('data-status') || '';
            const rowSearch = row.getAttribute('data-search') || '';

            const matchesRole = !roleVal || rowRole === roleVal;
            const matchesStatus = !statusVal || rowStatus === statusVal;
            const matchesSearch = !searchVal || rowSearch.includes(searchVal);

            if (matchesRole && matchesStatus && matchesSearch) {
                row.style.display = '';
                visibleCount++;
                const numEl = row.querySelector('.row-num');
                if (numEl) numEl.textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });

        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
});
</script>
<?= $this->endSection() ?>
