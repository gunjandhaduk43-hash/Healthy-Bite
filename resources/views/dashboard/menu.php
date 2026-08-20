<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Menu System</span>
            <h1 class="h2 mb-1">Dishes & Menu Management</h1>
            <p class="text-secondary mb-0">Create and modify menu items, nutritional macros, ingredients list, and allergen warnings.</p>
        </div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= e(url('/dashboard/menu/create')) ?>" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> Add New Dish
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

<div class="dashboard-card card border-0">
    <div class="card-body p-4">
        <?php if (empty($foods)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-egg-fried display-4 text-secondary-subtle"></i>
                <p class="mt-3 fs-5">No dishes in your menu yet.</p>
                <p class="small">Click "Add New Dish" to setup your first healthy recipe!</p>
                <a href="<?= e(url('/dashboard/menu/create')) ?>" class="btn btn-primary btn-sm mt-2">Add First Dish</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                <?php foreach ($foods as $dish): ?>
                    <div class="col">
                        <div class="grid-item-card h-100 d-flex flex-column justify-content-between p-4 bg-white">
                            <div>
                                <!-- Header with Category and Availability -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 fw-semibold small text-uppercase" style="font-size: 0.65rem;"><?= e($dish['category_name']) ?></span>
                                    <span class="badge-hb <?= $dish['is_available'] ? 'status-approved' : 'status-suspended' ?>">
                                        <span class="badge-pulse"></span>
                                        <?= $dish['is_available'] ? 'Available' : 'Sold Out' ?>
                                    </span>
                                </div>

                                <!-- Name and Diet Type -->
                                <h3 class="h5 mb-2 fw-extrabold text-dark d-flex align-items-center gap-2">
                                    <?php 
                                        $dietBadges = [
                                            'veg' => 'diet-badge-veg',
                                            'non_veg' => 'diet-badge-non-veg',
                                            'vegan' => 'diet-badge-vegan',
                                            'jain' => 'diet-badge-jain'
                                        ];
                                        $dietLabels = [
                                            'veg' => 'Veg',
                                            'non_veg' => 'Non-Veg',
                                            'vegan' => 'Vegan',
                                            'jain' => 'Jain'
                                        ];
                                        $dietClass = $dietBadges[$dish['food_type']] ?? 'bg-secondary';
                                        $dietLabel = $dietLabels[$dish['food_type']] ?? strtoupper($dish['food_type']);
                                    ?>
                                    <span class="diet-badge <?= $dietClass ?>" style="font-size: 0.6rem; letter-spacing: 0.02em; padding: 0.25rem 0.5rem; text-transform: uppercase;"><?= $dietLabel ?></span>
                                    <span class="text-truncate" title="<?= e($dish['name']) ?>"><?= e($dish['name']) ?></span>
                                </h3>

                                <!-- Portions size and preparation -->
                                <div class="d-flex gap-3 text-muted small mb-3" style="font-size: 0.75rem;">
                                    <?php if (!empty($dish['serving_size'])): ?>
                                        <span><i class="bi bi-cup-hot me-1"></i><?= e($dish['serving_size']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($dish['preparation_time'])): ?>
                                        <span><i class="bi bi-clock me-1"></i><?= e($dish['preparation_time']) ?> mins</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Features/Badges -->
                                <div class="d-flex flex-wrap gap-1.5 mb-4">
                                    <?php if ($dish['is_featured']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.65rem;"><i class="bi bi-star-fill me-0.5"></i>Featured</span>
                                    <?php endif; ?>
                                    <?php if (!$dish['is_featured']): ?>
                                        <span class="badge bg-light text-muted px-2 py-1" style="font-size: 0.65rem; border: 1px solid rgba(0,0,0,0.05);">Standard</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Footer with Price and Actions -->
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                                <span class="fs-5 fw-extrabold text-success font-display">&#8377;<?= e(number_format((float)$dish['base_price'], 2)) ?></span>
                                <div class="d-inline-flex gap-1.5">
                                    <a href="<?= e(url('/dashboard/menu/options?id=' . $dish['id'])) ?>" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" title="Manage Options">
                                        <i class="bi bi-gear"></i> Options
                                    </a>
                                    <a href="<?= e(url('/dashboard/menu/edit?id=' . $dish['id'])) ?>" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="Edit Dish">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="post" action="<?= e(url('/dashboard/menu/toggle')) ?>" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="food_id" value="<?= e($dish['id']) ?>">
                                        <button class="btn btn-sm <?= $dish['is_available'] ? 'btn-outline-danger' : 'btn-outline-success' ?> d-inline-flex align-items-center gap-1" type="submit" title="<?= $dish['is_available'] ? 'Mark as Out of Stock' : 'Mark as Available' ?>">
                                            <i class="bi <?= $dish['is_available'] ? 'bi-toggle-off' : 'bi-toggle-on' ?>"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
