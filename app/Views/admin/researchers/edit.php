<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Researcher Profile</h5>
            </div>
            <div class="card-body p-4">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/researchers/update/' . $researcher['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <?php if (!empty($researcher['username'])): ?>
                        <div class="mb-3">
                            <label class="form-label">User Account</label>
                            <input type="text" class="form-control" value="<?= $researcher['username'] ?>" disabled>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Surname</label>
                            <input type="text" name="surname" class="form-control" value="<?= old('surname', $researcher['surname']) ?>" placeholder="Enter surname" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= old('first_name', $researcher['first_name']) ?>" placeholder="Enter first name" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Middle Initial</label>
                            <input type="text" name="middle_initial" class="form-control" value="<?= old('middle_initial', $researcher['middle_initial']) ?>" placeholder="e.g. A">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Ext Name</label>
                            <input type="text" name="ext_name" class="form-control" value="<?= old('ext_name', $researcher['ext_name']) ?>" placeholder="e.g. Jr., Sr., III">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Designation
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDesignationModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="designation_id" id="designation_id" class="form-select">
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= old('designation_id', $researcher['designation_id']) == $d['id'] ? 'selected' : '' ?>><?= $d['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                School Year
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSchoolYearModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="school_year_id" id="school_year_id" class="form-select">
                                <option value="">Select School Year</option>
                                <?php foreach ($schoolYears as $sy): ?>
                                    <option value="<?= $sy['id'] ?>" <?= old('school_year_id', $researcher['school_year_id']) == $sy['id'] ? 'selected' : '' ?>><?= $sy['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Category
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('category_id', $researcher['category_id']) == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Program/Career Pathways
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStrandModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="strand_id" id="strand_id" class="form-select">
                                <option value="">Select Strand</option>
                                <?php foreach ($strands as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= old('strand_id', $researcher['strand_id']) == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Approved Research Title</label>
                        <textarea name="approved_research_title" class="form-control" rows="3" placeholder="Enter the approved research title"><?= old('approved_research_title', $researcher['approved_research_title']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Approved Date</label>
                        <input type="date" name="approved_date" class="form-control" value="<?= old('approved_date', $researcher['approved_date']) ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Adviser
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAdviserModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="adviser_id" id="adviser_id" class="form-select">
                                <option value="">Select Adviser</option>
                                <?php foreach ($advisers as $a): ?>
                                    <option value="<?= $a['id'] ?>" <?= old('adviser_id', $researcher['adviser_id']) == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Grammarian
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGrammarianModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="grammarian_id" id="grammarian_id" class="form-select">
                                <option value="">Select Grammarian</option>
                                <?php foreach ($grammarians as $g): ?>
                                    <option value="<?= $g['id'] ?>" <?= old('grammarian_id', $researcher['grammarian_id']) == $g['id'] ? 'selected' : '' ?>><?= $g['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Statisticians
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStatisticianModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="remark_id" id="remark_id" class="form-select">
                                <option value="">Select Statisticians</option>
                                <?php foreach ($statisticians as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= old('remark_id', $researcher['remark_id']) == $r['id'] ? 'selected' : '' ?>><?= $r['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Research Teacher
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addResearchTeacherModal" title="Manage">
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </label>
                            <select name="research_teacher_id" id="research_teacher_id" class="form-select">
                                <option value="">Select Research Teacher</option>
                                <?php foreach ($researchTeachers as $rt): ?>
                                    <option value="<?= $rt['id'] ?>" <?= old('research_teacher_id', $researcher['research_teacher_id']) == $rt['id'] ? 'selected' : '' ?>><?= $rt['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Abstract</label>
                            <textarea name="abstract" class="form-control" rows="3" placeholder="Enter the abstract"><?= old('abstract', $researcher['abstract'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            Status
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStatusModal" title="Manage">
                                    <i class="bi bi-gear"></i> Manage
                                </button>
                            </div>
                        </label>
                        <select name="status_id" id="status_id" class="form-select">
                            <option value="">Select Status</option>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= old('status_id', $researcher['status_id']) == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"><?= old('remarks', $researcher['remarks']) ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/researchers') ?>" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Designation Modal -->
<div class="modal fade" id="addDesignationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Designations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="designationError" class="alert alert-danger d-none"></div>
                <div id="designationSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newDesignationName" class="form-control" placeholder="e.g. Teaching Personnel">
                        <button type="button" id="saveDesignationBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="designationList" class="list-group">
                        <?php foreach ($designations as $d): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $d['id'] ?>">
                                <span class="designation-name"><?= $d['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $d['id'] ?>" data-name="<?= $d['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $d['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- School Year Modal -->
<div class="modal fade" id="addSchoolYearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage School Years</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="schoolYearError" class="alert alert-danger d-none"></div>
                <div id="schoolYearSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newSchoolYearName" class="form-control" placeholder="e.g. 2024-2025">
                        <button type="button" id="saveSchoolYearBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="schoolYearList" class="list-group">
                        <?php foreach ($schoolYears as $sy): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $sy['id'] ?>">
                                <span class="schoolYear-name"><?= $sy['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $sy['id'] ?>" data-name="<?= $sy['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $sy['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="categoryError" class="alert alert-danger d-none"></div>
                <div id="categorySuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newCategoryName" class="form-control" placeholder="e.g. College Department">
                        <button type="button" id="saveCategoryBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="categoryList" class="list-group">
                        <?php foreach ($categories as $cat): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $cat['id'] ?>">
                                <span class="category-name"><?= $cat['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $cat['id'] ?>" data-name="<?= $cat['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $cat['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Strand Modal -->
<div class="modal fade" id="addStrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Program/Career Pathways</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="strandError" class="alert alert-danger d-none"></div>
                <div id="strandSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newStrandName" class="form-control" placeholder="e.g. STEM">
                        <button type="button" id="saveStrandBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="strandList" class="list-group">
                        <?php foreach ($strands as $s): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $s['id'] ?>">
                                <span class="strand-name"><?= $s['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $s['id'] ?>" data-name="<?= $s['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $s['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="addStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Statuses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="statusError" class="alert alert-danger d-none"></div>
                <div id="statusSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newStatusName" class="form-control" placeholder="e.g. Active">
                        <button type="button" id="saveStatusBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="statusList" class="list-group">
                        <?php foreach ($statuses as $s): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $s['id'] ?>">
                                <span class="status-name"><?= $s['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $s['id'] ?>" data-name="<?= $s['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $s['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Adviser Modal -->
<div class="modal fade" id="addAdviserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Advisers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="adviserError" class="alert alert-danger d-none"></div>
                <div id="adviserSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newAdviserName" class="form-control" placeholder="e.g. Dr. Smith">
                        <button type="button" id="saveAdviserBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="adviserList" class="list-group">
                        <?php foreach ($advisers as $a): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $a['id'] ?>">
                                <span class="adviser-name"><?= $a['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $a['id'] ?>" data-name="<?= $a['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $a['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Grammarian Modal -->
<div class="modal fade" id="addGrammarianModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Grammarians</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="grammarianError" class="alert alert-danger d-none"></div>
                <div id="grammarianSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newGrammarianName" class="form-control" placeholder="e.g. Prof. Johnson">
                        <button type="button" id="saveGrammarianBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="grammarianList" class="list-group">
                        <?php foreach ($grammarians as $g): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $g['id'] ?>">
                                <span class="grammarian-name"><?= $g['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $g['id'] ?>" data-name="<?= $g['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $g['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Statistician Modal -->
<div class="modal fade" id="addStatisticianModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Statisticians</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="statisticianError" class="alert alert-danger d-none"></div>
                <div id="statisticianSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newStatisticianName" class="form-control" placeholder="e.g. Dr. Smith">
                        <button type="button" id="saveStatisticianBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="statisticianList" class="list-group">
                        <?php foreach ($statisticians as $s): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $s['id'] ?>">
                                <span class="statistician-name"><?= $s['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $s['id'] ?>" data-name="<?= $s['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $s['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Research Teacher Modal -->
<div class="modal fade" id="addResearchTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Research Teachers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="researchTeacherError" class="alert alert-danger d-none"></div>
                <div id="researchTeacherSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newResearchTeacherName" class="form-control" placeholder="e.g. Prof. Johnson">
                        <button type="button" id="saveResearchTeacherBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="researchTeacherList" class="list-group">
                        <?php foreach ($researchTeachers as $rt): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $rt['id'] ?>">
                                <span class="researchTeacher-name"><?= $rt['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $rt['id'] ?>" data-name="<?= $rt['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $rt['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Abstract Modal -->
<div class="modal fade" id="addAbstractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Abstracts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="abstractError" class="alert alert-danger d-none"></div>
                <div id="abstractSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newAbstractName" class="form-control" placeholder="e.g. Complete">
                        <button type="button" id="saveAbstractBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="abstractList" class="list-group">
                        <?php foreach ($abstracts as $ab): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $ab['id'] ?>">
                                <span class="abstract-name"><?= $ab['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $ab['id'] ?>" data-name="<?= $ab['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $ab['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('saveDesignationBtn').addEventListener('click', function() {
    saveEntity('designation');
});

document.getElementById('saveCourseBtn').addEventListener('click', function() {
    saveEntity('course');
});

document.getElementById('saveSchoolYearBtn').addEventListener('click', function() {
    saveEntity('schoolYear');
});

document.getElementById('saveCategoryBtn').addEventListener('click', function() {
    saveEntity('category');
});

document.getElementById('saveStrandBtn').addEventListener('click', function() {
    saveEntity('strand');
});

document.getElementById('saveStatusBtn').addEventListener('click', function() {
    saveEntity('status');
});

document.getElementById('saveAdviserBtn').addEventListener('click', function() {
    saveEntity('adviser');
});

document.getElementById('saveGrammarianBtn').addEventListener('click', function() {
    saveEntity('grammarian');
});

document.getElementById('saveStatisticianBtn').addEventListener('click', function() {
    saveEntity('statistician');
});

document.getElementById('saveResearchTeacherBtn').addEventListener('click', function() {
    saveEntity('researchTeacher');
});

document.getElementById('saveAbstractBtn').addEventListener('click', function() {
    saveEntity('abstract');
});

// Event delegation for edit/delete buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-btn')) {
        const btn = e.target.closest('.edit-btn');
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const type = getTypeFromBtn(btn);
        editEntity(type, id, name);
    }
    
    if (e.target.closest('.delete-btn')) {
        const btn = e.target.closest('.delete-btn');
        const id = btn.dataset.id;
        const type = getTypeFromBtn(btn);
        deleteEntity(type, id);
    }
});

function getTypeFromBtn(btn) {
    if (btn.closest('#designationList')) return 'designation';
    if (btn.closest('#courseList')) return 'course';
    if (btn.closest('#schoolYearList')) return 'schoolYear';
    if (btn.closest('#categoryList')) return 'category';
    if (btn.closest('#strandList')) return 'strand';
    if (btn.closest('#statusList')) return 'status';
    if (btn.closest('#adviserList')) return 'adviser';
    if (btn.closest('#grammarianList')) return 'grammarian';
    if (btn.closest('#statisticianList')) return 'statistician';
    if (btn.closest('#researchTeacherList')) return 'researchTeacher';
    if (btn.closest('#abstractList')) return 'abstract';
}

function saveEntity(type) {
    const nameInput = document.getElementById('new' + type.charAt(0).toUpperCase() + type.slice(1) + 'Name');
    const errorDiv = document.getElementById(type + 'Error');
    const successDiv = document.getElementById(type + 'Success');
    const name = nameInput.value;
    
    if (!name) {
        errorDiv.textContent = 'Name is required';
        errorDiv.classList.remove('d-none');
        successDiv.classList.add('d-none');
        return;
    }

    const selectId = type === 'researchTeacher' ? 'research_teacher_id' : type + '_id';
    fetch('<?= base_url('admin/researchers/add-') ?>' + type, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'name=' + encodeURIComponent(name) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById(selectId);
            const option = new Option(data.name, data.id);
            select.add(option);
            select.value = data.id;
            
            // Add to list
            const list = document.getElementById(type + 'List');
            const item = document.createElement('div');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.dataset.id = data.id;
            item.innerHTML = `
                <span class="${type}-name">${data.name}</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="${data.id}" data-name="${data.name}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="${data.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            list.appendChild(item);
            
            successDiv.textContent = 'Added successfully!';
            successDiv.classList.remove('d-none');
            errorDiv.classList.add('d-none');
            nameInput.value = '';
        } else {
            errorDiv.textContent = data.error || 'Failed to save';
            errorDiv.classList.remove('d-none');
            successDiv.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'An unexpected error occurred';
        errorDiv.classList.remove('d-none');
        successDiv.classList.add('d-none');
    });
}

function editEntity(type, id, currentName) {
    const newName = prompt('Enter new name:', currentName);
    if (!newName || newName.trim() === '') return;
    
    const errorDiv = document.getElementById(type + 'Error');
    const successDiv = document.getElementById(type + 'Success');
    const selectId = type === 'researchTeacher' ? 'research_teacher_id' : type + '_id';
    
    fetch('<?= base_url('admin/researchers/edit-') ?>' + type, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id=' + id + '&name=' + encodeURIComponent(newName.trim()) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update select
            const select = document.getElementById(selectId);
            const option = select.querySelector('option[value="' + id + '"]');
            if (option) option.textContent = data.name;
            
            // Update list
            const listItem = document.getElementById(type + 'List').querySelector('[data-id="' + id + '"]');
            if (listItem) {
                listItem.querySelector('.' + type + '-name').textContent = data.name;
                listItem.querySelector('.edit-btn').dataset.name = data.name;
            }
            
            successDiv.textContent = 'Updated successfully!';
            successDiv.classList.remove('d-none');
            errorDiv.classList.add('d-none');
        } else {
            errorDiv.textContent = data.error || 'Failed to update';
            errorDiv.classList.remove('d-none');
            successDiv.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'An unexpected error occurred';
        errorDiv.classList.remove('d-none');
        successDiv.classList.add('d-none');
    });
}

function deleteEntity(type, id) {
    if (!confirm('Are you sure you want to delete this?')) return;
    
    const errorDiv = document.getElementById(type + 'Error');
    const successDiv = document.getElementById(type + 'Success');
    const selectId = type === 'researchTeacher' ? 'research_teacher_id' : type + '_id';
    
    fetch('<?= base_url('admin/researchers/delete-') ?>' + type + '/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove from select
            const select = document.getElementById(selectId);
            const option = select.querySelector('option[value="' + id + '"]');
            if (option) option.remove();
            
            // Remove from list
            const listItem = document.getElementById(type + 'List').querySelector('[data-id="' + id + '"]');
            if (listItem) listItem.remove();
            
            successDiv.textContent = 'Deleted successfully!';
            successDiv.classList.remove('d-none');
            errorDiv.classList.add('d-none');
        } else {
            errorDiv.textContent = data.error || 'Failed to delete';
            errorDiv.classList.remove('d-none');
            successDiv.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'An unexpected error occurred';
        errorDiv.classList.remove('d-none');
        successDiv.classList.add('d-none');
    });
}
</script>
<?= $this->endSection() ?>
