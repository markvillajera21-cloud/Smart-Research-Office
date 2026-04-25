<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Researcher Profile: <?= $researcher['username'] ?></h5>
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

                    <div class="mb-3">
                        <label class="form-label fw-medium">User Account</label>
                        <input type="text" class="form-control bg-light" value="<?= $researcher['username'] ?>" disabled>
                        <div class="form-text text-muted small">The linked user account cannot be changed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Full Name</label>
                        <input type="text" name="fullname" class="form-control" value="<?= old('fullname', $researcher['fullname']) ?>" placeholder="Enter researcher's full name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Institutional ID</label>
                            <input type="text" name="institutional_id" class="form-control" value="<?= old('institutional_id', $researcher['institutional_id']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium d-flex justify-content-between">
                                Category
                                <button type="button" class="btn btn-link p-0 text-decoration-none small" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="bi bi-plus-circle me-1"></i>Add New
                                </button>
                            </label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('category_id', $researcher['category_id']) == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Area of Expertise</label>
                        <input type="text" name="expertise" class="form-control" value="<?= old('expertise', $researcher['expertise']) ?>" placeholder="e.g. Artificial Intelligence, Molecular Biology">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Joining Date</label>
                        <input type="date" name="joined_at" class="form-control" value="<?= old('joined_at', date('Y-m-d', strtotime($researcher['joined_at']))) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Short Bio</label>
                        <textarea name="bio" class="form-control" rows="3"><?= old('bio', $researcher['bio']) ?></textarea>
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Research Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="categoryError" class="alert alert-danger d-none"></div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Category Name</label>
                    <input type="text" id="newCategoryName" class="form-control" placeholder="e.g. Artificial Intelligence">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveCategoryBtn" class="btn btn-primary px-4">Save Category</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('saveCategoryBtn').addEventListener('click', function() {
    const name = document.getElementById('newCategoryName').value;
    const errorDiv = document.getElementById('categoryError');
    
    if (!name) {
        errorDiv.textContent = 'Category name is required';
        errorDiv.classList.remove('d-none');
        return;
    }

    fetch('<?= base_url('admin/researchers/add-category') ?>', {
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
            // Add to dropdown
            const select = document.getElementById('category_id');
            const option = new Option(data.name, data.id);
            select.add(option);
            select.value = data.id;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            modal.hide();
            
            // Clear input
            document.getElementById('newCategoryName').value = '';
            errorDiv.classList.add('d-none');
        } else {
            errorDiv.textContent = data.error || 'Failed to save category';
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'An unexpected error occurred';
        errorDiv.classList.remove('d-none');
    });
});
</script>
<?= $this->endSection() ?>
