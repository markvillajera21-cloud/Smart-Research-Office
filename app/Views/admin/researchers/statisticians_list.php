<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <?php if (session()->get('role') === 'admin'): ?>
        <a href="<?= base_url('admin/researchers/create') ?>" class="btn btn-primary shadow-sm d-flex align-items-center px-3 py-2">
            <i class="bi bi-plus-lg me-2"></i> Add Research
        </a>
        <?php endif; ?>
    </div>
    
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <form action="<?= base_url('admin/researchers/statisticians') ?>" method="get" class="d-flex flex-wrap gap-2 align-items-center">
            <!-- School Year -->
            <select name="school_year" class="form-select shadow-sm" style="width: 160px;" onchange="this.form.submit()">
                <option value="">All School Year</option>
                <?php foreach ($schoolYears as $sy): ?>
                    <option value="<?= $sy['id'] ?>" <?= ($schoolYear ?? '') == $sy['id'] ? 'selected' : '' ?>><?= $sy['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Department -->
            <select name="category" class="form-select shadow-sm" style="width: 160px;" onchange="this.form.submit()">
                <option value="">All Department</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($category ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Program/Academic Track -->
            <select name="strand" class="form-select shadow-sm" style="width: 200px;" onchange="this.form.submit()">
                <option value="">All Program/Academic Track</option>
                <?php foreach ($strands as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($strand ?? '') == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Adviser -->
            <select name="adviser" class="form-select shadow-sm" style="width: 160px;" onchange="this.form.submit()">
                <option value="">All Adviser</option>
                <?php foreach ($advisers as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($adviser ?? '') == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Grammarian -->
            <select name="grammarian" class="form-select shadow-sm" style="width: 160px;" onchange="this.form.submit()">
                <option value="">All Grammarian</option>
                <?php foreach ($grammarians as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= ($grammarian ?? '') == $g['id'] ? 'selected' : '' ?>><?= $g['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Statistician -->
            <select name="statistician" class="form-select shadow-sm" style="width: 160px;" onchange="this.form.submit()">
                <option value="">All Statistician</option>
                <?php foreach ($statisticians as $st): ?>
                    <option value="<?= $st['id'] ?>" <?= ($statistician ?? '') == $st['id'] ? 'selected' : '' ?>><?= $st['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Research Teacher -->
            <select name="research_teacher" class="form-select shadow-sm" style="width: 180px;" onchange="this.form.submit()">
                <option value="">All Research Teacher</option>
                <?php foreach ($researchTeachers as $rt): ?>
                    <option value="<?= $rt['id'] ?>" <?= ($researchTeacher ?? '') == $rt['id'] ? 'selected' : '' ?>><?= $rt['name'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Search -->
            <div class="input-group shadow-sm border rounded" style="width: 300px;">
                <span class="input-group-text bg-white border-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search by name, title, author..." value="<?= $search ?? '' ?>">
            </div>
            
            <?php if (($search ?? '') || ($strand ?? '') || ($schoolYear ?? '') || ($category ?? '') || ($adviser ?? '') || ($grammarian ?? '') || ($statistician ?? '') || ($researchTeacher ?? '')): ?>
                <a href="<?= base_url('admin/researchers/statisticians') ?>" class="btn btn-light border shadow-sm d-flex align-items-center" title="Clear Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </form>
        <button class="btn btn-outline-primary shadow-sm d-flex align-items-center px-3 py-2" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Print List
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <div class="mb-4">
        <h4 class="mb-1 fw-bold text-dark">Research by Statisticians</h4>
        <p class="text-muted mb-0">Manage and filter research by statisticians</p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-uppercase text-muted small">
                    <th class="fw-semibold">Approved Research Title</th>
                    <th class="fw-semibold">Program/Academic Track</th>
                    <th class="fw-semibold" style="min-width: 250px;">Author</th>
                    <th class="fw-semibold" style="min-width: 300px; padding-left: 30px;">Members</th>
                    <th class="fw-semibold">School Year</th>
                    <th class="fw-semibold">Adviser</th>
                    <th class="fw-semibold">Grammarian</th>
                    <th class="fw-semibold">Statistician</th>
                    <th class="fw-semibold">Research Teacher</th>
                    <?php if (session()->get('role') === 'admin'): ?>
                    <th class="text-end fw-semibold no-print" style="width: 100px;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($researchers)): ?>
                    <?php foreach ($researchers as $r): ?>
                        <tr>
                            <td>
                                <div class="small" style="max-width: 280px;">
                                    <?= $r['approved_research_title'] ?? '<span class="text-muted">N/A</span>' ?>
                                </div>
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
                                <span class="badge bg-light text-dark border font-monospace small">
                                    <?= $r['school_year_name'] ?? 'N/A' ?>
                                </span>
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
                            <?php if (session()->get('role') === 'admin'): ?>
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
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= (session()->get('role') === 'admin') ? 10 : 9 ?>" class="text-center py-5 text-muted">
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