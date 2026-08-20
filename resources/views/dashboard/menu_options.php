<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Menu Options Configuration</span>
            <h1 class="h2 mb-1">Variants & Customizations</h1>
            <p class="text-secondary mb-0">Configure sizes/variants (price differentials) and addon customizations for <strong><?= e($food['name']) ?></strong>.</p>
        </div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= e(url('/dashboard/menu')) ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Menu
        </a>
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
    <!-- Left column: Variants -->
    <div class="col-lg-6">
        <div class="dashboard-card card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold text-dark mb-4"><i class="bi bi-layers text-success me-2"></i>Variants & Sizes</h3>
                <p class="text-secondary small mb-4">Variants represent mutually exclusive options (e.g. Small, Large) with relative price differentials compared to the base price of &#8377;<?= e(number_format((float)$food['base_price'], 2)) ?>.</p>

                <!-- List of Variants -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr class="text-secondary small">
                                <th>Variant Name</th>
                                <th>Price Differential</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($variants)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No variants configured for this dish.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($variants as $var): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= e($var['name']) ?></td>
                                        <td>
                                            <span class="text-success fw-semibold">
                                                <?= (float)$var['price_differential'] >= 0 ? '+' : '' ?>&#8377;<?= e(number_format((float)$var['price_differential'], 2)) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form method="post" action="<?= e(url('/dashboard/menu/options/variant/delete')) ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this variant?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="food_item_id" value="<?= e($food['id']) ?>">
                                                <input type="hidden" name="id" value="<?= e($var['id']) ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Variant Form -->
                <div class="bg-light p-3 rounded-3 border">
                    <h4 class="h6 fw-bold text-secondary mb-3">Add Size/Variant</h4>
                    <form method="post" action="<?= e(url('/dashboard/menu/options/variant/save')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="food_item_id" value="<?= e($food['id']) ?>">

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-sm <?= !empty($errors['variant_name']) ? 'is-invalid' : '' ?>" 
                                       name="name" placeholder="e.g. Large / 500ml" required>
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" class="form-control form-control-sm <?= !empty($errors['price_differential']) ? 'is-invalid' : '' ?>" 
                                       name="price_differential" placeholder="+/- Price" required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success btn-sm w-100" type="submit"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Right column: Customizations -->
    <div class="col-lg-6">
        <div class="dashboard-card card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold text-dark mb-4"><i class="bi bi-sliders text-success me-2"></i>Customizations & Addons</h3>
                <p class="text-secondary small mb-4">Customizations represent optional addons or modifications (e.g. Extra Cheese, Gluten Free) with absolute additional prices.</p>

                <!-- List of Customizations -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr class="text-secondary small">
                                <th>Customization Name</th>
                                <th>Addon Price</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($customizations)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No customizations configured for this dish.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($customizations as $cust): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= e($cust['name']) ?></td>
                                        <td>
                                            <span class="text-success fw-semibold">
                                                +&#8377;<?= e(number_format((float)$cust['price'], 2)) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form method="post" action="<?= e(url('/dashboard/menu/options/customization/delete')) ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customization?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="food_item_id" value="<?= e($food['id']) ?>">
                                                <input type="hidden" name="id" value="<?= e($cust['id']) ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Customization Form -->
                <div class="bg-light p-3 rounded-3 border">
                    <h4 class="h6 fw-bold text-secondary mb-3">Add Customization Addon</h4>
                    <form method="post" action="<?= e(url('/dashboard/menu/options/customization/save')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="food_item_id" value="<?= e($food['id']) ?>">

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-sm <?= !empty($errors['customization_name']) ? 'is-invalid' : '' ?>" 
                                       name="name" placeholder="e.g. Extra Cheese / Tofu" required>
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm <?= !empty($errors['price']) ? 'is-invalid' : '' ?>" 
                                       name="price" placeholder="Price" required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success btn-sm w-100" type="submit"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
