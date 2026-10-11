<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- Welcome Banner -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
        <div class="card-body px-4 py-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1">Welcome, <?= esc(session()->get('username') ?? 'Student') ?>!</h3>
                <p class="mb-0 opacity-75">Access the Expert Pool, search completed researches, and read approved research abstracts.</p>
            </div>
            <i class="bi bi-mortarboard-fill" style="font-size: 3.5rem; opacity: 0.25;"></i>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="<?= base_url('student/expert-pool') ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-3 p-3" style="background: rgba(79,70,229,0.1);">
                            <i class="bi bi-person-lines-fill fs-2 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Expert Pool</h5>
                            <p class="text-muted small mb-0">View active advisers, grammarians, and statisticians.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= base_url('student/search-titles') ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-3 p-3" style="background: rgba(16,185,129,0.1);">
                            <i class="bi bi-search fs-2 text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Search Research</h5>
                            <p class="text-muted small mb-0">Search for completed research titles by keyword or strand.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= base_url('student/completed-researches') ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-3 p-3" style="background: rgba(245,158,11,0.1);">
                            <i class="bi bi-check2-circle fs-2 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Completed Researches</h5>
                            <p class="text-muted small mb-0">Browse the archive of all completed research projects.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3" role="alert">
        <i class="bi bi-info-circle-fill fs-4 text-info mt-1 flex-shrink-0"></i>
        <div>
            <strong>Student Access Notice</strong><br>
            As a student, you have read-only access to the <strong>Expert Pool</strong>, <strong>Research Search</strong>, and <strong>Completed Researches</strong> archive. If you need to view a specific research abstract, use the <em>Search Research</em> or <em>Completed Researches</em> sections and click <strong>"Read Abstract"</strong>.
        </div>
    </div>

</div>

<style>
.hover-lift { transition: transform 0.18s ease, box-shadow 0.18s ease; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important; }
</style>

<?= $this->endSection() ?>
