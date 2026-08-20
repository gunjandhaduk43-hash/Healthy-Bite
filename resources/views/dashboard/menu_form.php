<?php
$isEdit = $food !== null;
$actionUrl = url('/dashboard/menu/save');
$titleText = $isEdit ? 'Edit Menu Item' : 'Add New Menu Item';

// Helper to resolve values from old input or database model
$val = static function (string $key, mixed $default = '') use ($food, $old) {
    if (isset($old[$key])) {
        return $old[$key];
    }
    if ($food !== null && isset($food[$key])) {
        return $food[$key];
    }
    return $default;
};

$checked = static function (string $key, bool $default = false) use ($food, $old) {
    if (isset($old['_token'])) {
        // Form was submitted, check if key is set in post
        return isset($old[$key]);
    }
    if ($food !== null && isset($food[$key])) {
        return (bool) $food[$key];
    }
    return $default;
};
?>

<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Menu System</span>
            <h1 class="h2 mb-1"><?= e($titleText) ?></h1>
            <p class="text-secondary mb-0"><?= $isEdit ? 'Modify details for "' . e($food['name']) . '"' : 'Onboard a new recipe with macros and ingredients.' ?></p>
        </div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= e(url('/dashboard/menu')) ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Menu
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-9 mx-auto">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4 p-lg-5">
                <form method="post" action="<?= e($actionUrl) ?>" novalidate>
                    <?= csrf_field() ?>
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= e($food['id']) ?>">
                    <?php endif; ?>

                    <!-- Error Alert -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <div>Please correct the errors marked in red below.</div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4 gap-2 border-bottom-0" id="formTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 fw-semibold" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true">
                                <i class="bi bi-info-circle me-1.5"></i>Basic Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-semibold" id="nutrition-tab" data-bs-toggle="tab" data-bs-target="#nutrition" type="button" role="tab" aria-controls="nutrition" aria-selected="false">
                                <i class="bi bi-calculator me-1.5"></i>Nutrition (Macros)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-semibold" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false">
                                <i class="bi bi-journal-text me-1.5"></i>Ingredients & Allergens
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content border p-4 rounded bg-white shadow-sm" id="formTabsContent">
                        <!-- TAB 1: BASIC DETAILS -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                            <h3 class="h6 text-uppercase text-success fw-bold mb-3 border-bottom pb-2">Dish Metadata</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Dish Name</label>
                                    <input class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                                           id="name" name="name" value="<?= e($val('name')) ?>" placeholder="e.g. Avocado Toast" required>
                                    <?php if (!empty($errors['name'])): ?><div class="invalid-feedback d-block"><?= e($errors['name']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="image">Image URL</label>
                                    <input class="form-control <?= !empty($errors['image']) ? 'is-invalid' : '' ?>" 
                                           id="image" name="image" value="<?= e($val('image')) ?>" placeholder="e.g. https://images.unsplash.com/...">
                                    <?php if (!empty($errors['image'])): ?><div class="invalid-feedback d-block"><?= e($errors['image']) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label" for="category_id">Menu Category</label>
                                    <select class="form-select <?= !empty($errors['category_id']) ? 'is-invalid' : '' ?>" id="category_id" name="category_id" required>
                                        <option value="">-- Choose Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= e($cat['id']) ?>" <?= (int) $val('category_id') === (int) $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!empty($errors['category_id'])): ?><div class="invalid-feedback d-block"><?= e($errors['category_id']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="base_price">Base Price (&#8377;)</label>
                                    <input class="form-control <?= !empty($errors['base_price']) ? 'is-invalid' : '' ?>" 
                                           id="base_price" name="base_price" type="number" step="0.01" value="<?= e($val('base_price')) ?>" placeholder="0.00" required>
                                    <?php if (!empty($errors['base_price'])): ?><div class="invalid-feedback d-block"><?= e($errors['base_price']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="food_type">Dietary Type</label>
                                    <select class="form-select <?= !empty($errors['food_type']) ? 'is-invalid' : '' ?>" id="food_type" name="food_type" required>
                                        <option value="veg" <?= $val('food_type') === 'veg' ? 'selected' : '' ?>>Vegetarian (Veg)</option>
                                        <option value="non_veg" <?= $val('food_type') === 'non_veg' ? 'selected' : '' ?>>Non-Vegetarian</option>
                                        <option value="vegan" <?= $val('food_type') === 'vegan' ? 'selected' : '' ?>>Vegan</option>
                                        <option value="jain" <?= $val('food_type') === 'jain' ? 'selected' : '' ?>>Jain</option>
                                    </select>
                                    <?php if (!empty($errors['food_type'])): ?><div class="invalid-feedback d-block"><?= e($errors['food_type']) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label" for="preparation_time">Prep Time (mins)</label>
                                    <input class="form-control <?= !empty($errors['preparation_time']) ? 'is-invalid' : '' ?>" 
                                           id="preparation_time" name="preparation_time" type="number" value="<?= e($val('preparation_time')) ?>" placeholder="e.g. 15">
                                    <?php if (!empty($errors['preparation_time'])): ?><div class="invalid-feedback d-block"><?= e($errors['preparation_time']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="spice_level">Spice Level</label>
                                    <select class="form-select <?= !empty($errors['spice_level']) ? 'is-invalid' : '' ?>" id="spice_level" name="spice_level" required>
                                        <option value="low" <?= $val('spice_level') === 'low' ? 'selected' : '' ?>>Low</option>
                                        <option value="medium" <?= $val('spice_level', 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                        <option value="high" <?= $val('spice_level') === 'high' ? 'selected' : '' ?>>High</option>
                                        <option value="extra_spicy" <?= $val('spice_level') === 'extra_spicy' ? 'selected' : '' ?>>Extra Spicy</option>
                                    </select>
                                    <?php if (!empty($errors['spice_level'])): ?><div class="invalid-feedback d-block"><?= e($errors['spice_level']) ?></div><?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="serving_size">Serving Size / Weight</label>
                                    <input class="form-control <?= !empty($errors['serving_size']) ? 'is-invalid' : '' ?>" 
                                           id="serving_size" name="serving_size" value="<?= e($val('serving_size')) ?>" placeholder="e.g. 1 bowl, 250g">
                                    <?php if (!empty($errors['serving_size'])): ?><div class="invalid-feedback d-block"><?= e($errors['serving_size']) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="description">Short Description</label>
                                <textarea class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" 
                                          id="description" name="description" rows="3" placeholder="Tell customers about the taste..."><?= e($val('description')) ?></textarea>
                                <?php if (!empty($errors['description'])): ?><div class="invalid-feedback d-block"><?= e($errors['description']) ?></div><?php endif; ?>
                            </div>

                            <h3 class="h6 text-uppercase text-success fw-bold mb-3 border-bottom pb-2">Status & Promotions</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-3 border rounded shadow-sm">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" id="is_available" name="is_available" value="1" <?= $checked('is_available', true) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold" for="is_available">Available for Order</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch p-3 border rounded shadow-sm">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" id="is_featured" name="is_featured" value="1" <?= $checked('is_featured') ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-semibold text-success" for="is_featured"><i class="bi bi-star-fill me-1"></i>Featured Dish</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: NUTRITION INFO -->
                        <div class="tab-pane fade" id="nutrition" role="tabpanel" aria-labelledby="nutrition-tab">
                            <h3 class="h6 text-uppercase text-success fw-bold mb-3 border-bottom pb-2">Macronutrient Profile</h3>
                            <p class="text-secondary small mb-4">Values are optional, but display micro-rings on the mobile client menu to encourage healthy choices.</p>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label" for="calories">Calories (kcal)</label>
                                    <input class="form-control <?= !empty($errors['calories']) ? 'is-invalid' : '' ?>" 
                                           id="calories" name="calories" type="number" value="<?= e($val('calories')) ?>" placeholder="e.g. 350">
                                    <?php if (!empty($errors['calories'])): ?><div class="invalid-feedback d-block"><?= e($errors['calories']) ?></div><?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label" for="protein">Protein (grams)</label>
                                    <input class="form-control <?= !empty($errors['protein']) ? 'is-invalid' : '' ?>" 
                                           id="protein" name="protein" type="number" step="0.1" value="<?= e($val('protein')) ?>" placeholder="e.g. 15.5">
                                    <?php if (!empty($errors['protein'])): ?><div class="invalid-feedback d-block"><?= e($errors['protein']) ?></div><?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label" for="carbs">Carbohydrates (grams)</label>
                                    <input class="form-control <?= !empty($errors['carbs']) ? 'is-invalid' : '' ?>" 
                                           id="carbs" name="carbs" type="number" step="0.1" value="<?= e($val('carbs')) ?>" placeholder="e.g. 42.0">
                                    <?php if (!empty($errors['carbs'])): ?><div class="invalid-feedback d-block"><?= e($errors['carbs']) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="fat">Total Fat (grams)</label>
                                    <input class="form-control <?= !empty($errors['fat']) ? 'is-invalid' : '' ?>" 
                                           id="fat" name="fat" type="number" step="0.1" value="<?= e($val('fat')) ?>" placeholder="e.g. 8.2">
                                    <?php if (!empty($errors['fat'])): ?><div class="invalid-feedback d-block"><?= e($errors['fat']) ?></div><?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label" for="fiber_g">Dietary Fiber (grams)</label>
                                    <input class="form-control <?= !empty($errors['fiber_g']) ? 'is-invalid' : '' ?>" 
                                           id="fiber_g" name="fiber_g" type="number" step="0.1" value="<?= e($val('fiber_g')) ?>" placeholder="e.g. 5.0">
                                    <?php if (!empty($errors['fiber_g'])): ?><div class="invalid-feedback d-block"><?= e($errors['fiber_g']) ?></div><?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label" for="sugar_g">Sugars (grams)</label>
                                    <input class="form-control <?= !empty($errors['sugar_g']) ? 'is-invalid' : '' ?>" 
                                           id="sugar_g" name="sugar_g" type="number" step="0.1" value="<?= e($val('sugar_g')) ?>" placeholder="e.g. 2.1">
                                    <?php if (!empty($errors['sugar_g'])): ?><div class="invalid-feedback d-block"><?= e($errors['sugar_g']) ?></div><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: INGREDIENTS & ALLERGENS -->
                        <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <h3 class="h6 text-uppercase text-success fw-bold mb-3 border-bottom pb-2">Recipe Constituents</h3>
                            <p class="text-secondary small mb-4">Enter lists as comma-separated values (e.g. "Avocado, Sourdough, Chia Seeds").</p>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary" for="ingredients">Ingredients List</label>
                                <textarea class="form-control" id="ingredients" name="ingredients" rows="3" placeholder="Spinach, Quinoa, Organic Cucumbers, Lemon Vinaigrette..."><?= e($val('ingredients')) ?></textarea>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label fw-semibold text-danger" for="allergens"><i class="bi bi-exclamation-triangle me-1"></i>Allergen Information</label>
                                <textarea class="form-control" id="allergens" name="allergens" rows="2" placeholder="Nuts, Dairy, Gluten, Soy..."><?= e($val('allergens')) ?></textarea>
                                <div class="form-text text-muted">A clear warning will display to customers flag-marked with these allergens.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button class="btn btn-primary d-flex align-items-center gap-2" type="submit">
                            <i class="bi bi-save2-fill"></i> Save Dish Details
                        </button>
                        <a href="<?= e(url('/dashboard/menu')) ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
