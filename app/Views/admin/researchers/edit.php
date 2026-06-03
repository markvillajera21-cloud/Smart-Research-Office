<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Research Profile</h5>
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Author</label>
                            <textarea name="author" class="form-control" rows="3" placeholder="Enter author name"><?= old('author', $researcher['author'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Surname</label>
                            <textarea name="surname" class="form-control" rows="5" placeholder="Enter surname" required><?= old('surname', $researcher['surname']) ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <textarea name="first_name" class="form-control" rows="5" placeholder="Enter first name" required><?= old('first_name', $researcher['first_name']) ?></textarea>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Middle Initial</label>
                            <textarea name="middle_initial" class="form-control" rows="5" placeholder="e.g. A"><?= old('middle_initial', $researcher['middle_initial']) ?></textarea>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Ext Name</label>
                            <textarea name="ext_name" class="form-control" rows="5" placeholder="e.g. Jr., Sr., III"><?= old('ext_name', $researcher['ext_name']) ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Designation
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDesignationModal" title="Manage">
                                        <i class="bi bi-gear"></i>
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
                                        <i class="bi bi-gear"></i>
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
                                Department
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Manage">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </div>
                            </label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Select Department</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('category_id', $researcher['category_id']) == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Program/Academic Track
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStrandModal" title="Manage">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </div>
                            </label>
                            <select name="strand_id" id="strand_id" class="form-select">
                                <option value="">Select Program/Academic Track</option>
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
                            <label class="form-label">Pre Oral Defense Date</label>
                            <input type="date" name="pre_oral_defense_date" class="form-control" value="<?= old('pre_oral_defense_date', $researcher['pre_oral_defense_date']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Pre Oral Defense Status
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDefenseStatusModal" title="Manage">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </div>
                            </label>
                            <select name="pre_oral_defense_status_id" class="form-select">
                                <option value="">Select Status</option>
                                <?php foreach ($defenseStatuses as $ds): ?>
                                    <option value="<?= $ds['id'] ?>" <?= old('pre_oral_defense_status_id', $researcher['pre_oral_defense_status_id']) == $ds['id'] ? 'selected' : '' ?>><?= $ds['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Final Defense Date</label>
                            <input type="date" name="final_defense_date" class="form-control" value="<?= old('final_defense_date', $researcher['final_defense_date']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Final Defense Status
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDefenseStatusModal" title="Manage">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </div>
                            </label>
                            <select name="final_defense_status_id" class="form-select">
                                <option value="">Select Status</option>
                                <?php foreach ($defenseStatuses as $ds): ?>
                                    <option value="<?= $ds['id'] ?>" <?= old('final_defense_status_id', $researcher['final_defense_status_id']) == $ds['id'] ? 'selected' : '' ?>><?= $ds['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Adviser
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAdviserModal" title="Manage">
                                        <i class="bi bi-gear"></i>
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
                                        <i class="bi bi-gear"></i>
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
                                        <i class="bi bi-gear"></i>
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
                                        <i class="bi bi-gear"></i>
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
                <h5 class="modal-title fw-bold">Manage Departments</h5>
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
                <h5 class="modal-title fw-bold">Manage Program/Academic Track</h5>
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

<!-- Defense Status Modal -->
<div class="modal fade" id="addDefenseStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Defense Statuses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="defenseStatusError" class="alert alert-danger d-none"></div>
                <div id="defenseStatusSuccess" class="alert alert-success d-none"></div>
                
                <!-- Add New -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Add New</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="newDefenseStatusName" class="form-control" placeholder="e.g. Pending">
                        <button type="button" id="saveDefenseStatusBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Existing</label>
                    <div id="defenseStatusList" class="list-group">
                        <?php foreach ($defenseStatuses as $ds): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $ds['id'] ?>">
                                <span class="defenseStatus-name"><?= $ds['name'] ?></span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $ds['id'] ?>" data-name="<?= $ds['name'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $ds['id'] ?>">
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('Manage functionality initialized');
    
    // Attach save button listeners
    const saveButtons = [
        { id: 'saveDesignationBtn', type: 'designation' },
        { id: 'saveCourseBtn', type: 'course' },
        { id: 'saveSchoolYearBtn', type: 'schoolYear' },
        { id: 'saveCategoryBtn', type: 'category' },
        { id: 'saveStrandBtn', type: 'strand' },
        { id: 'saveStatusBtn', type: 'status' },
        { id: 'saveAdviserBtn', type: 'adviser' },
        { id: 'saveGrammarianBtn', type: 'grammarian' },
        { id: 'saveStatisticianBtn', type: 'statistician' },
        { id: 'saveResearchTeacherBtn', type: 'researchTeacher' },
        { id: 'saveAbstractBtn', type: 'abstract' },
        { id: 'saveDefenseStatusBtn', type: 'defenseStatus' }
    ];
    
    saveButtons.forEach(btn => {
        const element = document.getElementById(btn.id);
        if (element) {
            element.addEventListener('click', function() {
                console.log('Save button clicked:', btn.type);
                saveEntity(btn.type);
            });
        }
    });
    
    // Event delegation for edit/delete buttons
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            const id = editBtn.dataset.id;
            const name = editBtn.dataset.name;
            const type = getTypeFromBtn(editBtn);
            console.log('Edit button clicked:', { type, id, name });
            editEntity(type, id, name);
        }
        
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            const id = deleteBtn.dataset.id;
            const type = getTypeFromBtn(deleteBtn);
            console.log('Delete button clicked:', { type, id });
            deleteEntity(type, id);
        }
    });
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
    if (btn.closest('#defenseStatusList')) return 'defenseStatus';
    console.error('Could not determine type for button');
    return null;
}

function saveEntity(type) {
    if (!type) return;
    
    const nameInput = document.getElementById('new' + type.charAt(0).toUpperCase() + type.slice(1) + 'Name');
    const errorDiv = document.getElementById(type + 'Error');
    const successDiv = document.getElementById(type + 'Success');
    
    if (!nameInput) {
        console.error('Name input not found for type:', type);
        return;
    }
    
    const name = nameInput.value;
    
    if (!name || name.trim() === '') {
        if (errorDiv) {
            errorDiv.textContent = 'Name is required';
            errorDiv.classList.remove('d-none');
        }
        if (successDiv) successDiv.classList.add('d-none');
        return;
    }

    const apiType = type === 'statistician' ? 'remark' : (type === 'defenseStatus' ? 'defense-status' : type);
    let selectIds = [];
    if (type === 'defenseStatus') {
        selectIds = ['pre_oral_defense_status_id', 'final_defense_status_id'];
    } else {
        selectIds = [type === 'researchTeacher' ? 'research_teacher_id' : (type === 'statistician' ? 'remark_id' : type + '_id')];
    }
    console.log('Saving entity:', { type, apiType, name, selectIds });
    
    fetch('<?= base_url('admin/researchers/add-') ?>' + apiType, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'name=' + encodeURIComponent(name.trim()) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => {
        console.log('Save response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Save response data:', data);
        if (data.success) {
            selectIds.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    const option = new Option(data.name, data.id);
                    select.add(option);
                    select.value = data.id;
                }
            });
            
            // Add to list
            const list = document.getElementById(type + 'List');
            if (list) {
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
            }
            
            if (successDiv) {
                successDiv.textContent = 'Added successfully!';
                successDiv.classList.remove('d-none');
            }
            if (errorDiv) errorDiv.classList.add('d-none');
            nameInput.value = '';
        } else {
            if (errorDiv) {
                errorDiv.textContent = data.error || 'Failed to save';
                errorDiv.classList.remove('d-none');
            }
            if (successDiv) successDiv.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error saving entity:', error);
        if (errorDiv) {
            errorDiv.textContent = 'An unexpected error occurred';
            errorDiv.classList.remove('d-none');
        }
        if (successDiv) successDiv.classList.add('d-none');
    });
}

function editEntity(type, id, currentName) {
    if (!type || !id) return;
    
    const newName = prompt('Enter new name:', currentName);
    if (!newName || newName.trim() === '') return;
    
    const errorDiv = document.getElementById(type + 'Error');
    const successDiv = document.getElementById(type + 'Success');
    const apiType = type === 'statistician' ? 'remark' : (type === 'defenseStatus' ? 'defense-status' : type);
    let selectIds = [];
    if (type === 'defenseStatus') {
        selectIds = ['pre_oral_defense_status_id', 'final_defense_status_id'];
    } else {
        selectIds = [type === 'researchTeacher' ? 'research_teacher_id' : (type === 'statistician' ? 'remark_id' : type + '_id')];
    }
    
    console.log('Editing entity:', { type, apiType, id, currentName, newName, selectIds });
    
    fetch('<?= base_url('admin/researchers/edit-') ?>' + apiType, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id=' + id + '&name=' + encodeURIComponent(newName.trim()) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => {
        console.log('Edit response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Edit response data:', data);
        if (data.success) {
            // Update selects
            selectIds.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    const option = select.querySelector('option[value="' + id + '"]');
                    if (option) option.textContent = data.name;
                }
            });
            
            // Update list
            const listItem = document.getElementById(type + 'List').querySelector('[data-id="' + id + '"]');
            if (listItem) {
                const nameSpan = listItem.querySelector('.' + type + '-name');
                if (nameSpan) nameSpan.textContent = data.name;
                const editBtn = listItem.querySelector('.edit-btn');
                if (editBtn) editBtn.dataset.name = data.name;
            }
            
            if (successDiv) {
                successDiv.textContent = 'Updated successfully!';
                successDiv.classList.remove('d-none');
            }
            if (errorDiv) errorDiv.classList.add('d-none');
        } else {
            if (errorDiv) {
                errorDiv.textContent = data.error || 'Failed to update';
                errorDiv.classList.remove('d-none');
            }
            if (successDiv) successDiv.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error editing entity:', error);
        if (errorDiv) {
            errorDiv.textContent = 'An unexpected error occurred';
            errorDiv.classList.remove('d-none');
        }
        if (successDiv) successDiv.classList.add('d-none');
    });
}

function deleteEntity(type, id) {
    if (!type || !id) return;
    
    if (!confirm('Are you sure you want to delete this?')) return;
    
    const errorDiv = document.getElementById(type + 'Error');
    const successDiv = document.getElementById(type + 'Success');
    const apiType = type === 'statistician' ? 'remark' : (type === 'defenseStatus' ? 'defense-status' : type);
    let selectIds = [];
    if (type === 'defenseStatus') {
        selectIds = ['pre_oral_defense_status_id', 'final_defense_status_id'];
    } else {
        selectIds = [type === 'researchTeacher' ? 'research_teacher_id' : (type === 'statistician' ? 'remark_id' : type + '_id')];
    }
    
    console.log('Deleting entity:', { type, apiType, id, selectIds });
    
    fetch('<?= base_url('admin/researchers/delete-') ?>' + apiType + '/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Delete response data:', data);
        if (data.success) {
            // Remove from selects
            selectIds.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    const option = select.querySelector('option[value="' + id + '"]');
                    if (option) option.remove();
                }
            });
            
            // Remove from list
            const listItem = document.getElementById(type + 'List').querySelector('[data-id="' + id + '"]');
            if (listItem) listItem.remove();
            
            if (successDiv) {
                successDiv.textContent = 'Deleted successfully!';
                successDiv.classList.remove('d-none');
            }
            if (errorDiv) errorDiv.classList.add('d-none');
        } else {
            if (errorDiv) {
                errorDiv.textContent = data.error || 'Failed to delete';
                errorDiv.classList.remove('d-none');
            }
            if (successDiv) successDiv.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error deleting entity:', error);
        if (errorDiv) {
            errorDiv.textContent = 'An unexpected error occurred';
            errorDiv.classList.remove('d-none');
        }
        if (successDiv) successDiv.classList.add('d-none');
    });
}
</script>
<?= $this->endSection() ?>
