<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Team Management</span>
            <h1 class="h2 mb-1">Staff Accounts</h1>
            <p class="text-secondary mb-0">Create and manage operational roles for your kitchen and waiting staff.</p>
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
    <!-- Add Staff Form -->
    <div class="col-lg-4">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-person-plus text-success me-2"></i>Add Staff Member</h2>
                </div>
                
                <form method="post" action="<?= e(url('/dashboard/staff/save')) ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold" for="name">Full Name</label>
                        <input class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" 
                               value="<?= e($old['name'] ?? '') ?>" 
                               placeholder="e.g. Mike Johnson" required>
                        <?php if (!empty($errors['name'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold" for="email">Email Address</label>
                        <input type="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" 
                               value="<?= e($old['email'] ?? '') ?>" 
                               placeholder="e.g. mike@healthybite.test" required>
                        <?php if (!empty($errors['email'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold" for="password">Login Password</label>
                        <input type="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" 
                               id="password" name="password" 
                               placeholder="Min. 8 characters" required>
                        <?php if (!empty($errors['password'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                        <i class="bi bi-check-lg"></i> Register Member
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Existing Staff List -->
    <div class="col-lg-8">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-people text-success me-2"></i>Active Staff accounts</h2>
                </div>
                
                <?php if (empty($staffList)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-people-fill display-4 text-secondary-subtle"></i>
                        <p class="mt-3 fs-5">No staff members registered yet.</p>
                        <p class="small">Add your kitchen or waiting staff on the left to authorize their access.</p>
                    </div>
                <?php else: ?>
                    <div class="visual-list">
                        <!-- Header Row -->
                        <div class="row py-2.5 px-3 mb-2 visual-list-header border-bottom bg-light rounded text-uppercase text-secondary fw-bold" style="font-size: 0.75rem;">
                            <div class="col-5">Staff Info</div>
                            <div class="col-4">Email</div>
                            <div class="col-2">Status</div>
                            <div class="col-1 text-end">Action</div>
                        </div>
                        <!-- List Items -->
                        <?php foreach ($staffList as $s): ?>
                            <div class="row align-items-center py-3 px-3 visual-list-item">
                                <div class="col-5">
                                    <span class="fw-bold text-dark fs-6 d-block"><?= e($s['name']) ?></span>
                                    <span class="text-muted small">Registered: <?= e(date('d M Y', strtotime($s['created_at']))) ?></span>
                                </div>
                                <div class="col-4 text-truncate">
                                    <span class="text-secondary small fw-medium"><?= e($s['email']) ?></span>
                                </div>
                                <div class="col-2">
                                    <span class="badge-hb <?= $s['status'] === 'active' ? 'status-approved' : 'status-suspended' ?>">
                                        <span class="badge-pulse"></span>
                                        <?= e(ucfirst($s['status'])) ?>
                                    </span>
                                </div>
                                <div class="col-1 text-end">
                                    <form method="post" action="<?= e(url('/dashboard/staff/toggle')) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="staff_id" value="<?= e($s['id']) ?>">
                                        <button class="btn btn-sm <?= $s['status'] === 'active' ? 'btn-outline-danger' : 'btn-outline-success' ?> d-inline-flex align-items-center gap-1.5" type="submit" title="<?= $s['status'] === 'active' ? 'Disable Account' : 'Enable Account' ?>">
                                            <i class="bi <?= $s['status'] === 'active' ? 'bi-toggle-off' : 'bi-toggle-on' ?>"></i>
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
