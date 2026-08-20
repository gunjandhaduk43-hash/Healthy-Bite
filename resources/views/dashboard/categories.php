<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Menu System</span>
            <h1 class="h2 mb-1">Categories Management</h1>
            <p class="text-secondary mb-0">Group your healthy dishes, beverages, and appetizers logically for customers.</p>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><?= e($success) ?></div>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div><?= e($error) ?></div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Add Category Form Card -->
    <div class="col-lg-4">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-plus-circle text-success me-2"></i>Add New Category</h2>
                </div>
                
                <form method="post" action="<?= e(url('/dashboard/categories/save')) ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold" for="name">Category Name</label>
                        <input class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" 
                               value="<?= e($old['name'] ?? '') ?>" 
                               placeholder="e.g. Fresh Salads, Vegan Bowls" required>
                        <?php if (!empty($errors['name'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold" for="sort_order">Sort Order</label>
                        <input class="form-control <?= !empty($errors['sort_order']) ? 'is-invalid' : '' ?>" 
                               id="sort_order" name="sort_order" type="number" 
                               value="<?= e($old['sort_order'] ?? '0') ?>" 
                               placeholder="0" required>
                        <div class="form-text text-muted">Lower values appear first in the menu list.</div>
                        <?php if (!empty($errors['sort_order'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['sort_order']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                        <i class="bi bi-check-lg"></i> Save Category
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Existing Categories List -->
    <div class="col-lg-8">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-tags text-success me-2"></i>Active & Inactive Categories</h2>
                </div>
                
                <?php if (empty($categories)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-tag-fill display-4 text-secondary-subtle"></i>
                        <p class="mt-3 fs-5">No categories created yet.</p>
                        <p class="small">Add your first category on the left to start building your menu!</p>
                    </div>
                <?php else: ?>
                    <div class="visual-list">
                        <!-- Header Row -->
                        <div class="row py-2.5 px-3 mb-2 visual-list-header border-bottom bg-light rounded text-uppercase text-secondary fw-bold" style="font-size: 0.75rem;">
                            <div class="col-3 col-sm-2">Sort</div>
                            <div class="col-5 col-sm-6">Category Name</div>
                            <div class="col-2">Status</div>
                            <div class="col-2 text-end">Action</div>
                        </div>
                        <!-- List Items -->
                        <?php foreach ($categories as $cat): ?>
                            <div class="row align-items-center py-3 px-3 visual-list-item">
                                <div class="col-3 col-sm-2">
                                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-2.5 py-1.5"><?= e($cat['sort_order']) ?></span>
                                </div>
                                <div class="col-5 col-sm-6">
                                    <span class="fw-bold text-dark fs-6"><?= e($cat['name']) ?></span>
                                </div>
                                <div class="col-2">
                                    <span class="badge-hb <?= $cat['is_active'] ? 'status-approved' : 'status-suspended' ?>">
                                        <span class="badge-pulse"></span>
                                        <span class="d-none d-sm-inline"><?= $cat['is_active'] ? 'Active' : 'Disabled' ?></span>
                                        <span class="d-inline d-sm-none"><?= $cat['is_active'] ? 'Act' : 'Dis' ?></span>
                                    </span>
                                </div>
                                <div class="col-2 text-end">
                                    <form method="post" action="<?= e(url('/dashboard/categories/toggle')) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="category_id" value="<?= e($cat['id']) ?>">
                                        <button class="btn btn-sm <?= $cat['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?> d-inline-flex align-items-center gap-1.5" type="submit" title="<?= $cat['is_active'] ? 'Disable Category' : 'Enable Category' ?>">
                                            <i class="bi <?= $cat['is_active'] ? 'bi-toggle-off' : 'bi-toggle-on' ?>"></i>
                                            <span class="d-none d-md-inline"><?= $cat['is_active'] ? 'Disable' : 'Enable' ?></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
