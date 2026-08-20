<section class="auth-card glass-card card border-0 shadow-lg">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 3.5rem; height: 3.5rem;">
                <i class="bi bi-shield-lock-fill fs-3"></i>
            </div>
            <h1 class="h3 mb-2">Owner Sign In</h1>
            <p class="text-secondary mb-0">Manage your Healthy Bite restaurant account.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><?= e($error) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($success = \App\Core\Flash::get('success')): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div><?= e($success) ?></div>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('/login')) ?>" novalidate>
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input class="form-control border-start-0 ps-0" id="email" name="email" type="email" autocomplete="email" required placeholder="name@restaurant.com">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input class="form-control border-start-0 ps-0" id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••">
                </div>
            </div>
            <button class="btn btn-primary w-100 py-2.5 d-flex align-items-center justify-content-center gap-2" type="submit">
                <span>Sign In</span> <i class="bi bi-arrow-right-short fs-5"></i>
            </button>
        </form>
        
        <div class="text-center mt-4">
            <p class="mb-0 text-secondary">New to Healthy Bite? <a class="text-success fw-semibold text-decoration-none" href="<?= e(url('/register')) ?>">Create your restaurant account</a></p>
        </div>
    </div>
</section>
