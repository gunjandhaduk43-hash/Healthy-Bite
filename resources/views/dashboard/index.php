<?php $errors = $errors ?? []; ?>

<div class="gradient-banner text-white p-4 p-md-5 mb-4 shadow-lg d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <span class="text-success-light text-uppercase fw-bold small tracking-wider" style="color: #6ee7b7; font-size: 0.75rem; letter-spacing: 0.05em;">Overview</span>
        <h1 class="h2 mb-1 text-white font-display">Welcome back, <?= e($user['name']) ?></h1>
        <p class="mb-0 text-white-50 small">Manage your restaurant settings, QR menus, orders, and details from this portal.</p>
    </div>
    <div>
        <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-10 px-3 py-2 rounded-pill">
            <span class="badge-hb <?= $restaurant['approval_status'] === 'approved' ? 'status-approved' : ($restaurant['approval_status'] === 'suspended' ? 'status-suspended' : 'status-pending') ?>">
                <span class="badge-pulse"></span>
                <?= e(ucfirst($restaurant['approval_status'])) ?>
            </span>
            <span class="text-white small fw-semibold text-truncate max-w-150px"><?= e($restaurant['name']) ?></span>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><?= e($success) ?></div>
    </div>
<?php endif; ?>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div><?= e($errors['general']) ?></div>
    </div>
<?php endif; ?>

<!-- Stats Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <article class="dashboard-card card h-100 border-0 stat-card">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-2">Account Status</p>
                    <span class="badge-hb <?= $restaurant['approval_status'] === 'approved' ? 'status-approved' : ($restaurant['approval_status'] === 'suspended' ? 'status-suspended' : 'status-pending') ?> fs-8 px-3 py-2">
                        <span class="badge-pulse"></span>
                        <?= e(ucfirst($restaurant['approval_status'])) ?>
                    </span>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
        </article>
    </div>
    
    <div class="col-md-4">
        <article class="dashboard-card card h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Menu Items</p>
                    <p class="stat-value mb-1"><?= e($foodsCount ?? 0) ?></p>
                    <a href="<?= e(url('/dashboard/menu')) ?>" class="text-success fw-medium text-decoration-none small"><i class="bi bi-plus-circle me-1"></i>Manage Menu</a>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-egg-fried"></i>
                </div>
            </div>
        </article>
    </div>
    
    <div class="col-md-4">
        <article class="dashboard-card card h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Orders Count</p>
                    <p class="stat-value mb-1"><?= e($ordersCount ?? 0) ?></p>
                    <a href="<?= e(url('/dashboard/orders')) ?>" class="text-warning fw-medium text-decoration-none small"><i class="bi bi-clock-history me-1"></i>View Active Queue</a>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-cart3"></i>
                </div>
            </div>
        </article>
    </div>
</div>

<!-- Profile Form Section -->
<section class="dashboard-card card border-0">
    <div class="card-body p-4 p-lg-5">
        <div class="border-bottom pb-3 mb-4">
            <h2 class="h4 mb-1"><i class="bi bi-shop text-success me-2"></i>Restaurant Profile</h2>
            <p class="text-secondary mb-0">These details will be displayed to customers on the future digital QR menu.</p>
        </div>

        <form method="post" action="<?= e(url('/dashboard/restaurant')) ?>" novalidate>
            <?= csrf_field() ?>
            
            <h3 class="h6 text-uppercase text-success fw-bold mb-3">Basic Information</h3>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label" for="name">Restaurant Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-pencil-square"></i></span>
                        <input class="form-control border-start-0 ps-0 <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= e($restaurant['name']) ?>" required>
                        <?php if (!empty($errors['name'])): ?><div class="invalid-feedback d-block"><?= e($errors['name']) ?></div><?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label" for="cuisine_type">Cuisine Type</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-egg-fried"></i></span>
                        <input class="form-control border-start-0 ps-0" id="cuisine_type" name="cuisine_type" value="<?= e($restaurant['cuisine_type'] ?? '') ?>" placeholder="e.g. Italian, Cafe, Pan-Asian">
                    </div>
                </div>
            </div>

            <h3 class="h6 text-uppercase text-success fw-bold mb-3">Contact details</h3>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label" for="email">Restaurant Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input class="form-control border-start-0 ps-0 <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" value="<?= e($restaurant['email']) ?>" required>
                        <?php if (!empty($errors['email'])): ?><div class="invalid-feedback d-block"><?= e($errors['email']) ?></div><?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label" for="phone">Contact Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                        <input class="form-control border-start-0 ps-0 <?= !empty($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= e($restaurant['phone']) ?>" required>
                        <?php if (!empty($errors['phone'])): ?><div class="invalid-feedback d-block"><?= e($errors['phone']) ?></div><?php endif; ?>
                    </div>
                </div>
            </div>

            <h3 class="h6 text-uppercase text-success fw-bold mb-3">Location</h3>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label" for="city">City</label>
                    <input class="form-control" id="city" name="city" value="<?= e($restaurant['city'] ?? '') ?>" placeholder="e.g. New York">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label" for="state">State</label>
                    <input class="form-control" id="state" name="state" value="<?= e($restaurant['state'] ?? '') ?>" placeholder="e.g. NY">
                </div>
                
                <div class="col-12">
                    <label class="form-label" for="address">Full Address</label>
                    <textarea class="form-control <?= !empty($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" rows="2" required placeholder="Street detail and post code..."><?= e($restaurant['address']) ?></textarea>
                    <?php if (!empty($errors['address'])): ?><div class="invalid-feedback d-block"><?= e($errors['address']) ?></div><?php endif; ?>
                </div>
            </div>

            <h3 class="h6 text-uppercase text-success fw-bold mb-3">About Restaurant</h3>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" maxlength="1000" placeholder="Tell customers about your brand story, healthy ingredients focus, and vibe..."><?= e($restaurant['description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="mt-4 pt-2 border-top">
                <button class="btn btn-primary d-flex align-items-center gap-2" type="submit">
                    <i class="bi bi-save2-fill"></i> Save profile details
                </button>
            </div>
        </form>
    </div>
</section>
