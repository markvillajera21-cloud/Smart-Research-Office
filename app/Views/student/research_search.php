<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1"><?= $page_title ?></h4>
            <p class="text-muted small mb-0">Research library - Browse and search completed researches and their abstracts. <span class="text-primary">No edit permissions.</span></p>
        </div>
        <a href="<?= base_url('student/dashboard') ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
    </div>

    <!-- Search Form -->
    <div class="card border-0 shadow-sm p-5 mb-4" style="background: linear-gradient(135deg, #ecfeff 0%, #eff6ff 100%);">
        <h5 class="mb-4 fw-bold"><i class="bi bi-search text-primary me-2"></i> Find Completed Research Studies</h5>
        <form method="get" class="row g-3 align-items-end">
            <div class="col-lg-8 col-md-7">
                <label class="form-label small mb-1">Search by Title / Keywords / Abstract</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Try: artificial intelligence, ABM, STEM, research..." value="<?= esc($search) ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label small mb-1">Filter by Strand / Program</label>
                <select name="strand_id" class="form-select">
                    <option value="">All Strands & Programs</option>
                    <?php foreach ($strands as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $strandId == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-1 col-md-1">
                <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
        <div class="mt-3 small">
            <span class="text-muted">Showing</span> <span class="fw-semibold text-primary"><?= count($titles) ?></span> <span class="text-muted">completed researches.</span>
        </div>
    </div>

    <!-- Results Grid -->
    <?php if (count($titles) === 0): ?>
        <div class="card border-0 shadow-sm p-5 text-center">
            <i class="bi bi-file-earmark-x fs-1 text-muted mb-3 d-block"></i>
            <h5 class="mb-1">No completed researches found</h5>
            <p class="text-muted small mb-0">Try adjusting your search or filters. If this is a new system, completed researches will appear here as they are marked done.</p>
        </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($titles as $t): ?>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <?php if (!empty($t['strand_name'])): ?>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill me-1 mb-1"><?= esc($t['strand_name']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($t['category_name'])): ?>
                                    <span class="badge bg-info-subtle text-info rounded-pill mb-1"><?= esc($t['category_name']) ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-success-subtle text-success"><i class="bi bi-check2-circle me-1"></i> Completed</span>
                        </div>
                        <h5 class="fw-bold mb-2 lh-base"><?= esc($t['title']) ?></h5>
                        <div class="row g-2 mb-3 small">
                            <div class="col-md-6">
                                <span class="text-muted d-block mb-1"><i class="bi bi-person-badge me-1"></i> Author / Student</span>
                                <strong class="text-dark"><?= esc($t['student_name'] ?? 'N/A') ?></strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block mb-1"><i class="bi bi-person me-1"></i> Adviser</span>
                                <strong class="text-dark"><?= esc($t['instructor_name'] ?? 'N/A') ?></strong>
                            </div>
                            <?php if (!empty($t['school_year_name'])): ?>
                                <div class="col-md-12">
                                    <span class="text-muted d-block mb-1"><i class="bi bi-calendar3 me-1"></i> School Year</span>
                                    <strong class="text-dark"><?= esc($t['school_year_name']) ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="bg-light rounded p-3 mb-3">
                            <div class="text-muted small mb-2 fw-semibold"><i class="bi bi-file-earmark-text me-1"></i> Abstract Preview</div>
                            <p class="text-dark small mb-0" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php if (!empty($t['abstract'])): ?>
                                    <?= esc($t['abstract']) ?>
                                <?php else: ?>
                                    <span class="text-muted italic">No abstract provided for this research.</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <button class="btn btn-outline-primary btn-sm view-abstract-btn w-100" data-id="<?= $t['id'] ?>" data-bs-toggle="modal" data-bs-target="#abstractModal">
                            <i class="bi bi-book me-1"></i> Read Full Abstract & Details
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- View Full Abstract Modal -->
<div class="modal fade" id="abstractModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0" id="abstractModalBody"></div>
            <div class="modal-footer border-top-0 pt-0 pb-4 px-5 gap-2">
                <button class="btn btn-primary" id="downloadAbstractBtn"><i class="bi bi-download me-1"></i> Save Details as Bookmark</button>
                <button class="btn btn-light border" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.view-abstract-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        fetch('<?= base_url('student/view-abstract/') ?>' + id, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
            .then(r => r.json())
            .then(data => {
                if (!data) return;
                document.getElementById('abstractModalBody').innerHTML = `
                    <div class="p-5">
                        <div class="mb-4">
                            <span class="badge bg-success-subtle text-success me-2 mb-2"><i class="bi bi-trophy me-1"></i> Completed Research</span>
                            <?php foreach ((['strand_name'=>'bg-primary-subtle text-primary','category_name'=>'bg-info-subtle text-info','school_year_name'=>'bg-secondary-subtle text-secondary']) as $k => $b): ?>
                                <?php echo '<?php if (!empty($data['" . $k . "'])): ?>"; ?>
                                    <span class="badge <?= $b ?> me-1 mb-2">${data.<?= $k ?> ?? ''}</span>
                                <?php echo '<?php endif; ?>'; ?>
                            <?php endforeach; ?>
                        </div>
                        <h3 class="mb-4 fw-bold lh-base text-dark">${data.title}</h3>
                        <div class="card bg-light border-0 mb-4 p-4">
                            <div class="row g-3 small">
                                <div class="col-md-6">
                                    <div class="text-muted mb-1">Student / Researcher</div>
                                    <strong class="text-dark">${data.student_name ?? 'N/A'}</strong>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted mb-1">Research Instructor</div>
                                    <strong class="text-dark">${data.instructor_name ?? 'N/A'}</strong>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted mb-1">Approved / Completed Date</div>
                                    <strong class="text-dark">${data.approved_at ? new Date(data.approved_at).toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}) : 'N/A'}</strong>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted mb-1">Strand</div>
                                    <strong class="text-dark">${data.strand_name ?? 'N/A'}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3"><i class="bi bi-file-earmark-text fs-4"></i></div>
                                <h5 class="mb-0 fw-bold">Full Research Abstract</h5>
                            </div>
                            <div class="border-start border-primary border-4 ps-4 ms-2 py-1">
                                <p class="text-dark mb-0" style="line-height: 1.8;">${data.abstract ? data.abstract.replace(/\n/g, '<br>') : '<span class="text-muted fst-italic">No abstract available on record.</span>'}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-top small text-muted">
                            <i class="bi bi-info-circle me-1"></i> As a student user, you may <strong>view and reference</strong> this completed research for your own studies, but editing is disabled per RMS policy.
                        </div>
                    </div>
                `;
            });
    });
});
document.getElementById('downloadAbstractBtn').addEventListener('click', () => {
    window.print();
});
</script>
<?= $this->endSection() ?>
