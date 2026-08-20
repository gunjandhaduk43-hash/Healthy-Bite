<section class="auth-card glass-card card border-0 shadow-lg">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 3.5rem; height: 3.5rem;">
                <i class="bi bi-shop fs-3"></i>
            </div>
            <h1 class="h3 mb-2">Create your account</h1>
            <p class="text-secondary mb-0">Start with your owner and restaurant details.</p>
        </div>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><?= e($errors['general']) ?></div>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('/register')) ?>" novalidate>
            <?= csrf_field() ?>

            <!-- Owner Section -->
            <div class="p-4 mb-4 border rounded-3 bg-light bg-opacity-50">
                <h2 class="h6 text-uppercase text-success fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge"></i> Owner details
                </h2>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="name">Full name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input class="form-control border-start-0 ps-0 <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" type="text" value="<?= e($old['name'] ?? '') ?>" autocomplete="name" required placeholder="John Doe">
                            <?php if (!empty($errors['name'])): ?><div class="invalid-feedback d-block"><?= e($errors['name']) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="email">Owner email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input class="form-control border-start-0 ps-0 <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email" required placeholder="john@example.com">
                            <?php if (!empty($errors['email'])): ?><div class="invalid-feedback d-block"><?= e($errors['email']) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input class="form-control border-start-0 ps-0 <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" type="password" minlength="8" autocomplete="new-password" required placeholder="Min. 8 characters">
                            <?php if (!empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= e($errors['password']) ?></div><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restaurant Section -->
            <div class="p-4 mb-4 border rounded-3 bg-light bg-opacity-50">
                <h2 class="h6 text-uppercase text-success fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-building"></i> Restaurant details
                </h2>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="restaurant_name">Restaurant name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-card-text"></i></span>
                            <input class="form-control border-start-0 ps-0 <?= !empty($errors['restaurant_name']) ? 'is-invalid' : '' ?>" id="restaurant_name" name="restaurant_name" type="text" value="<?= e($old['restaurant_name'] ?? '') ?>" required placeholder="Green Bite Cafe">
                            <?php if (!empty($errors['restaurant_name'])): ?><div class="invalid-feedback d-block"><?= e($errors['restaurant_name']) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="restaurant_email">Restaurant email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope-at"></i></span>
                            <input class="form-control border-start-0 ps-0 <?= !empty($errors['restaurant_email']) ? 'is-invalid' : '' ?>" id="restaurant_email" name="restaurant_email" type="email" value="<?= e($old['restaurant_email'] ?? '') ?>" required placeholder="info@greenbite.com">
                            <?php if (!empty($errors['restaurant_email'])): ?><div class="invalid-feedback d-block"><?= e($errors['restaurant_email']) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Contact number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                            <input class="form-control border-start-0 ps-0 <?= !empty($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" type="tel" value="<?= e($old['phone'] ?? '') ?>" autocomplete="tel" required placeholder="+1234567890">
                            <?php if (!empty($errors['phone'])): ?><div class="invalid-feedback d-block"><?= e($errors['phone']) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="address">Address</label>
                        <textarea class="form-control <?= !empty($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" rows="3" required placeholder="Street address, City, Country"><?= e($old['address'] ?? '') ?></textarea>
                        <?php if (!empty($errors['address'])): ?><div class="invalid-feedback d-block"><?= e($errors['address']) ?></div><?php endif; ?>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary w-100 py-2.5 d-flex align-items-center justify-content-center gap-2 mt-4" type="submit">
                <span>Create Account</span> <i class="bi bi-arrow-right-short fs-5"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="mb-0 text-secondary">Already registered? <a class="text-success fw-semibold text-decoration-none" href="<?= e(url('/login')) ?>">Sign in</a></p>
        </div>
    </div>
</section>
