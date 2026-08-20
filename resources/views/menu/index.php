<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

<div class="menu-client-container">
    <!-- Header banner -->
    <header class="menu-header">
        <div class="container text-center py-4">
            <span class="brand-badge animate-pulse">HB</span>
            <h1 class="restaurant-title"><?= e($restaurantName) ?></h1>
            <div class="table-tag">
                <i class="bi bi-geo-alt-fill text-success"></i> Seated at <strong><?= e($tableName) ?></strong>
            </div>
        </div>
    </header>

    <!-- Error/Success Alerts -->
    <div class="container my-2">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 shadow-sm rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div><?= e($error) ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 shadow-sm rounded-3" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div><?= e($success) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Category Selector Navigation -->
    <nav class="category-nav shadow-sm">
        <div class="container">
            <div class="category-scroll-wrapper d-flex gap-2 py-3 overflow-x-auto text-nowrap">
                <button class="btn btn-category active" data-category="all">All Dishes</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="btn btn-category" data-category="<?= e($cat['id']) ?>"><?= e($cat['name']) ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <!-- Menu Items List -->
    <main class="menu-main container my-4">
        <?php if (empty($categories)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-receipt display-4 text-secondary-subtle"></i>
                <p class="mt-3">The menu is currently empty. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="menu-items-grid">
                <?php foreach ($categories as $cat): 
                    $catId = (int) $cat['id'];
                    $dishList = $groupedFoods[$catId] ?? [];
                    if ($dishList === []) continue;
                ?>
                    <section class="category-section" data-section-cat="<?= $catId ?>">
                        <h2 class="category-section-title border-bottom pb-2 mb-3 mt-4"><?= e($cat['name']) ?></h2>
                        <div class="row g-3">
                            <?php foreach ($dishList as $food): 
                                $foodId = (int) $food['id'];
                                $dietColors = [
                                    'veg' => 'diet-veg',
                                    'non_veg' => 'diet-non-veg',
                                    'vegan' => 'diet-vegan',
                                    'jain' => 'diet-jain'
                                ];
                                $dietLabels = [
                                    'veg' => 'Veg',
                                    'non_veg' => 'Non-Veg',
                                    'vegan' => 'Vegan',
                                    'jain' => 'Jain'
                                ];
                                $dietClass = $dietColors[$food['food_type']] ?? 'bg-secondary';
                                $dietLabel = $dietLabels[$food['food_type']] ?? strtoupper($food['food_type']);
                            ?>
                                <div class="col-md-6 menu-item-card-wrapper" data-food-id="<?= $foodId ?>">
                                    <div class="card h-100 menu-item-card border-0 shadow-sm">
                                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                                            <div>
                                                <!-- Title and Tags -->
                                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="diet-dot <?= $dietClass ?>" title="<?= $dietLabel ?> Type"></span>
                                                        <h3 class="h6 fw-bold mb-0 text-dark"><?= e($food['name']) ?></h3>
                                                    </div>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <?php if ($food['is_featured']): ?>
                                                            <span class="badge bg-success text-white py-1 px-1.5" style="font-size: 0.6rem;"><i class="bi bi-star-fill"></i> Featured</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Description -->
                                                <?php if (!empty($food['description'])): ?>
                                                    <p class="text-secondary small mb-2 text-truncate-2"><?= e($food['description']) ?></p>
                                                <?php endif; ?>

                                                <!-- Serving & Prep specs -->
                                                <div class="d-flex flex-wrap gap-3 text-muted small mb-2" style="font-size: 0.75rem;">
                                                    <?php if (!empty($food['serving_size'])): ?>
                                                        <span><i class="bi bi-cup-hot me-1"></i><?= e($food['serving_size']) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($food['preparation_time'])): ?>
                                                        <span><i class="bi bi-clock me-1"></i><?= e($food['preparation_time']) ?> mins</span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Variants and Customizations -->
                                                <?php if (!empty($food['variants']) || !empty($food['customizations'])): ?>
                                                    <div class="my-2 p-2 bg-light rounded-3 border">
                                                        <?php if (!empty($food['variants'])): ?>
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small mb-1 fw-bold" style="font-size:0.7rem;">Select Size / Variant:</label>
                                                                <select class="form-select form-select-sm variant-select" data-food-id="<?= $foodId ?>">
                                                                    <option value="" data-price="0">Regular Base (Default)</option>
                                                                    <?php foreach ($food['variants'] as $var): ?>
                                                                        <option value="<?= e($var['id']) ?>" data-price="<?= (float)$var['price_adjustment'] ?>">
                                                                            <?= e($var['name']) ?> (+&#8377;<?= e(number_format((float)$var['price_adjustment'], 2)) ?>)
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($food['customizations'])): ?>
                                                            <div>
                                                                <label class="form-label text-muted small mb-1 fw-bold" style="font-size:0.7rem;">Add-ons / Customizations:</label>
                                                                <div>
                                                                    <?php foreach ($food['customizations'] as $cust): ?>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input customization-check" type="checkbox" 
                                                                                   id="cust_<?= $foodId ?>_<?= $cust['id'] ?>" 
                                                                                   data-food-id="<?= $foodId ?>" 
                                                                                   value="<?= e($cust['id']) ?>" 
                                                                                   data-price="<?= (float)$cust['price_adjustment'] ?>">
                                                                            <label class="form-check-label small text-dark" for="cust_<?= $foodId ?>_<?= $cust['id'] ?>" style="font-size:0.75rem;">
                                                                                <?= e($cust['name']) ?> (+&#8377;<?= e(number_format((float)$cust['price_adjustment'], 2)) ?>)
                                                                            </label>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                                <!-- Price and Nutrition link -->
                                                <div>
                                                    <span class="fs-5 fw-extrabold text-success">&#8377;<?= e(number_format((float)$food['base_price'], 2)) ?></span>
                                                    
                                                    <!-- Details / Nutrition Trigger -->
                                                    <button class="btn btn-link btn-sm text-success text-decoration-none d-block p-0 mt-0.5 text-start font-display" 
                                                            style="font-size: 0.72rem; font-weight: 600;" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#nutritionModal<?= $foodId ?>">
                                                        <i class="bi bi-heart-pulse"></i> Nutrition & Details
                                                    </button>
                                                </div>

                                                <!-- Add Button / Qty Control -->
                                                <div class="cart-control" data-item-price="<?= (float)$food['base_price'] ?>" data-item-name="<?= e($food['name']) ?>">
                                                    <button class="btn btn-sm btn-outline-success px-3 btn-add-item">Add</button>
                                                    <div class="d-none align-items-center gap-2 qty-wrapper">
                                                        <button class="btn btn-sm btn-success rounded-circle btn-qty-minus"><i class="bi bi-minus"></i></button>
                                                        <span class="fw-bold qty-display text-dark" style="min-width: 15px; text-align: center;">0</span>
                                                        <button class="btn btn-sm btn-success rounded-circle btn-qty-plus"><i class="bi bi-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nutrition Modal for each food item -->
                                <div class="modal fade" id="nutritionModal<?= $foodId ?>" tabindex="-1" aria-labelledby="nutritionModalLabel<?= $foodId ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h4 class="modal-title fw-bold text-success display-font" id="nutritionModalLabel<?= $foodId ?>"><?= e($food['name']) ?></h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pt-3">
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <span class="diet-dot <?= $dietClass ?>"></span>
                                                    <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 text-uppercase"><?= $dietLabel ?> Option</span>
                                                    <?php if (!empty($food['serving_size'])): ?>
                                                        <span class="text-muted small"><i class="bi bi-cup-hot me-1"></i>Portion: <?= e($food['serving_size']) ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Nutrition metrics grid -->
                                                <div class="bg-light p-3 rounded-4 mb-4">
                                                    <h5 class="h6 text-uppercase fw-bold text-success mb-3" style="letter-spacing: 0.05em;"><i class="bi bi-activity"></i> Nutrients per serving</h5>
                                                    <div class="row g-2 text-center">
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded bg-white">
                                                                <span class="text-muted small d-block" style="font-size: 0.65rem;">CALORIES</span>
                                                                <span class="fw-extrabold text-success fs-5"><?= $food['calories'] !== null ? e($food['calories']) . ' <small style="font-size: 0.5em;">kcal</small>' : '-' ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded bg-white">
                                                                <span class="text-muted small d-block" style="font-size: 0.65rem;">PROTEIN</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['protein'] !== null ? e($food['protein']) . 'g' : '-' ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded bg-white">
                                                                <span class="text-muted small d-block" style="font-size: 0.65rem;">CARBS</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['carbs'] !== null ? e($food['carbs']) . 'g' : '-' ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded bg-white">
                                                                <span class="text-muted small d-block" style="font-size: 0.65rem;">TOTAL FAT</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['fat'] !== null ? e($food['fat']) . 'g' : '-' ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ingredients & Allergens -->
                                                <?php if (!empty($food['ingredients'])): ?>
                                                    <div class="mb-3">
                                                        <h5 class="h6 fw-bold text-secondary mb-1">Key Ingredients</h5>
                                                        <p class="small text-dark mb-0 bg-light p-2.5 rounded-3"><?= e($food['ingredients']) ?></p>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($food['allergens'])): ?>
                                                    <div class="alert alert-warning border-warning-subtle d-flex gap-2 py-2 px-3 rounded-3 mb-0" role="alert">
                                                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                                        <div>
                                                            <span class="fw-bold text-dark small d-block" style="font-size: 0.78rem;">Allergen Warning</span>
                                                            <span class="small text-secondary" style="font-size: 0.72rem;">Contains: <strong><?= e($food['allergens']) ?></strong></span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Cart Floating Sticky Bar (Desktop & Mobile) -->
    <div id="sticky-cart-bar" class="sticky-cart-bar border-top shadow-lg d-none">
        <div class="container d-flex justify-content-between align-items-center py-2.5">
            <div>
                <span id="cart-item-count" class="fw-bold text-success fs-6">0 items</span>
                <span class="text-muted mx-1">|</span>
                <span id="cart-total-display" class="fw-extrabold text-dark fs-5">&#8377;0.00</span>
            </div>
            <button class="btn btn-success d-flex align-items-center gap-2 px-4 py-2" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                <i class="bi bi-cart-check-fill"></i> View Cart & Order
            </button>
        </div>
    </div>

    <!-- Checkout Cart Offcanvas Drawer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h4 class="offcanvas-title fw-bold text-success display-font" id="cartOffcanvasLabel"><i class="bi bi-bag-check-fill me-2"></i>Your Order Cart</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <div>
                <!-- Scrollable Order item lines -->
                <div id="cart-items-lines" class="mb-4">
                    <!-- Javascript populates lines here -->
                </div>
            </div>

            <!-- Checkout Form -->
            <form id="checkoutForm" method="post" action="<?= e(url('/menu/checkout')) ?>" class="border-top pt-3">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">
                
                <!-- Hidden inputs for cart lines, populated by JS -->
                <div id="hidden-cart-inputs"></div>

                <div class="mb-3 row g-2">
                    <div class="col-6">
                        <label class="form-label fw-semibold text-secondary" for="customer_name">Your Name</label>
                        <input class="form-control" id="customer_name" name="customer_name" value="" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold text-secondary" for="customer_phone">Phone (Optional)</label>
                        <input class="form-control" id="customer_phone" name="customer_phone" value="" placeholder="e.g. 9876543210">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary" for="note"><i class="bi bi-chat-left-dots text-success"></i> Special Request to Chef</label>
                    <textarea class="form-control" id="note" name="note" rows="2" maxlength="300" placeholder="e.g. No onion, make it extra spicy, etc."></textarea>
                </div>

                <div class="bg-light p-3 rounded-4 mb-3">
                    <div class="d-flex justify-content-between text-secondary mb-1.5 small">
                        <span>Items Subtotal</span>
                        <span id="drawer-subtotal">&#8377;0.00</span>
                    </div>
                    <div class="d-flex justify-content-between text-secondary mb-2 small">
                        <span>CGST & SGST (Inclusive)</span>
                        <span>&#8377;0.00</span>
                    </div>
                    <hr class="my-2 opacity-25">
                    <div class="d-flex justify-content-between fw-bold text-dark fs-5">
                        <span>Total Pay</span>
                        <span id="drawer-total">&#8377;0.00</span>
                    </div>
                </div>

                <button class="btn btn-success btn-lg w-100 py-3 d-flex align-items-center justify-content-center gap-2" type="submit" id="btn-submit-order">
                    <i class="bi bi-cursor-fill"></i> Place Order to Chef
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Styling -->
<style>
body {
    background-color: #fafbfc;
    font-family: 'Outfit', sans-serif;
    color: #4a5568;
}
.display-font {
    font-family: 'Playfair Display', serif;
}
.menu-client-container {
    padding-bottom: 90px;
}
.menu-header {
    background: linear-gradient(135deg, #e6f7eb 0%, #f4fbf7 100%);
    border-bottom: 1px solid #d4edda;
}
.brand-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bs-success);
    color: white;
    font-weight: 800;
    width: 3.2rem;
    height: 3.2rem;
    border-radius: 50%;
    font-size: 1.25rem;
}
.restaurant-title {
    font-family: 'Playfair Display', serif;
    font-weight: 750;
    color: #1a4d2e;
    margin-top: 0.5rem;
}
.table-tag {
    display: inline-block;
    background: white;
    border: 1px solid #d4edda;
    color: #1a4d2e;
    padding: 0.4rem 1.2rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 550;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
}
.category-nav {
    background-color: white;
    position: sticky;
    top: 0;
    z-index: 1020;
}
.category-scroll-wrapper::-webkit-scrollbar {
    height: 4px;
}
.category-scroll-wrapper::-webkit-scrollbar-thumb {
    background-color: #e2e8f0;
    border-radius: 4px;
}
.btn-category {
    background-color: #f7fafc;
    border: 1px solid #edf2f7;
    border-radius: 50px;
    color: #718096;
    font-weight: 550;
    padding: 0.5rem 1.4rem;
    font-size: 0.88rem;
    transition: all 0.25s ease;
}
.btn-category:hover {
    background-color: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}
.btn-category.active {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
    color: white;
    box-shadow: 0 4px 6px rgba(25, 135, 84, 0.2);
}
.category-section-title {
    font-family: 'Playfair Display', serif;
    color: #2d3748;
    font-weight: 650;
}
.menu-item-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 1.25rem;
    overflow: hidden;
}
.menu-item-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.02) !important;
}
.diet-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}
.diet-veg { background-color: #25d366; }
.diet-non-veg { background-color: #dc3545; }
.diet-vegan { background-color: #0dcaf0; }
.diet-jain { background-color: #ffc107; }

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    max-height: 2.8em;
}
.fw-extrabold {
    font-weight: 800;
}
.sticky-cart-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    z-index: 1030;
    animation: slideUp 0.3s ease-out;
}
@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.cart-line-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #edf2f7;
}
</style>

<!-- Dynamic Cart Javascript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cart = {}; // maps foodId -> {name, price, qty, variantId, customizationIds: []}
    
    // Elements
    const categoryButtons = document.querySelectorAll('.btn-category');
    const categorySections = document.querySelectorAll('.category-section');
    const stickyCartBar = document.getElementById('sticky-cart-bar');
    const cartItemCount = document.getElementById('cart-item-count');
    const cartTotalDisplay = document.getElementById('cart-total-display');
    const cartItemsLines = document.getElementById('cart-items-lines');
    const hiddenCartInputs = document.getElementById('hidden-cart-inputs');
    const drawerSubtotal = document.getElementById('drawer-subtotal');
    const drawerTotal = document.getElementById('drawer-total');

    // Category Filtering
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const selectedCat = this.getAttribute('data-category');
            if (selectedCat === 'all') {
                categorySections.forEach(sec => sec.classList.remove('d-none'));
            } else {
                categorySections.forEach(sec => {
                    if (sec.getAttribute('data-section-cat') === selectedCat) {
                        sec.classList.remove('d-none');
                    } else {
                        sec.classList.add('d-none');
                    }
                });
            }
        });
    });

    // Handle Item Addition & Qty adjustments
    const cardWrappers = document.querySelectorAll('.menu-item-card-wrapper');
    cardWrappers.forEach(card => {
        const foodId = parseInt(card.getAttribute('data-food-id'));
        const ctrl = card.querySelector('.cart-control');
        const basePrice = parseFloat(ctrl.getAttribute('data-item-price'));
        const name = ctrl.getAttribute('data-item-name');
        
        const btnAdd = ctrl.querySelector('.btn-add-item');
        const qtyWrapper = ctrl.querySelector('.qty-wrapper');
        const qtyDisplay = ctrl.querySelector('.qty-display');
        const btnPlus = ctrl.querySelector('.btn-qty-plus');
        const btnMinus = ctrl.querySelector('.btn-qty-minus');

        const variantSelect = card.querySelector('.variant-select');
        const customizationChecks = card.querySelectorAll('.customization-check');

        function calculateItemUnitPrice() {
            let unit = basePrice;
            if (variantSelect && variantSelect.value) {
                const opt = variantSelect.options[variantSelect.selectedIndex];
                unit += parseFloat(opt.getAttribute('data-price') || 0);
            }
            if (customizationChecks) {
                customizationChecks.forEach(chk => {
                    if (chk.checked) {
                        unit += parseFloat(chk.getAttribute('data-price') || 0);
                    }
                });
            }
            return unit;
        }

        function getSelectedOptions() {
            let variantId = variantSelect ? variantSelect.value : null;
            let custIds = [];
            if (customizationChecks) {
                customizationChecks.forEach(chk => {
                    if (chk.checked) custIds.push(chk.value);
                });
            }
            return { variantId, custIds };
        }

        function updateCardDisplay(qty) {
            if (qty > 0) {
                btnAdd.classList.add('d-none');
                qtyWrapper.classList.remove('d-none');
                qtyWrapper.classList.add('d-flex');
                qtyDisplay.textContent = qty;
            } else {
                btnAdd.classList.remove('d-none');
                qtyWrapper.classList.add('d-none');
                qtyWrapper.classList.remove('d-flex');
                qtyDisplay.textContent = '0';
            }
        }

        btnAdd.addEventListener('click', () => {
            const unitPrice = calculateItemUnitPrice();
            const opts = getSelectedOptions();
            cart[foodId] = { name: name, price: unitPrice, qty: 1, variantId: opts.variantId, customizationIds: opts.custIds };
            updateCardDisplay(1);
            updateCartUI();
        });

        btnPlus.addEventListener('click', () => {
            const unitPrice = calculateItemUnitPrice();
            const opts = getSelectedOptions();
            if (!cart[foodId]) {
                cart[foodId] = { name: name, price: unitPrice, qty: 0, variantId: opts.variantId, customizationIds: opts.custIds };
            }
            cart[foodId].qty += 1;
            cart[foodId].price = unitPrice;
            cart[foodId].variantId = opts.variantId;
            cart[foodId].customizationIds = opts.custIds;
            updateCardDisplay(cart[foodId].qty);
            updateCartUI();
        });

        btnMinus.addEventListener('click', () => {
            if (cart[foodId] && cart[foodId].qty > 0) {
                cart[foodId].qty -= 1;
                const newQty = cart[foodId].qty;
                updateCardDisplay(newQty);
                if (newQty === 0) {
                    delete cart[foodId];
                }
                updateCartUI();
            }
        });
    });

    // Update Global Cart UI (Sticky bar & Drawer lines)
    function updateCartUI() {
        let totalItems = 0;
        let totalPrice = 0.0;
        cartItemsLines.innerHTML = '';
        hiddenCartInputs.innerHTML = '';

        const keys = Object.keys(cart);
        if (keys.length === 0) {
            stickyCartBar.classList.add('d-none');
            cartItemsLines.innerHTML = `
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-cart3 display-5 text-secondary-subtle"></i>
                    <p class="mt-2 mb-0">Your cart is empty.</p>
                </div>
            `;
            return;
        }

        keys.forEach(id => {
            const item = cart[id];
            totalItems += item.qty;
            totalPrice += item.price * item.qty;

            // Render drawer lines
            const line = document.createElement('div');
            line.className = 'cart-line-item';
            line.innerHTML = `
                <div>
                    <h5 class="h6 mb-0 text-dark fw-bold">${item.name}</h5>
                    <small class="text-muted">${item.qty} x &#8377;${item.price.toFixed(2)}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-success">&#8377;${(item.price * item.qty).toFixed(2)}</span>
                    <button class="btn btn-sm btn-link text-danger p-0 ms-2 btn-remove-line" data-remove-id="${id}"><i class="bi bi-trash"></i></button>
                </div>
            `;
            cartItemsLines.appendChild(line);

            // Populate checkout form input
            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = `quantities[${id}]`;
            qtyInput.value = item.qty;
            hiddenCartInputs.appendChild(qtyInput);

            if (item.variantId) {
                const varInput = document.createElement('input');
                varInput.type = 'hidden';
                varInput.name = `variants[${id}]`;
                varInput.value = item.variantId;
                hiddenCartInputs.appendChild(varInput);
            }

            if (item.customizationIds && item.customizationIds.length > 0) {
                item.customizationIds.forEach(cId => {
                    const custInput = document.createElement('input');
                    custInput.type = 'hidden';
                    custInput.name = `customizations[${id}][]`;
                    custInput.value = cId;
                    hiddenCartInputs.appendChild(custInput);
                });
            }
        });

        // Register trash button event in drawer
        document.querySelectorAll('.btn-remove-line').forEach(btn => {
            btn.addEventListener('click', function() {
                const idToRemove = parseInt(this.getAttribute('data-remove-id'));
                delete cart[idToRemove];
                
                // Reset card qty display
                const card = document.querySelector(`.menu-item-card-wrapper[data-food-id="${idToRemove}"]`);
                if (card) {
                    const btnAdd = card.querySelector('.btn-add-item');
                    const qtyWrapper = card.querySelector('.qty-wrapper');
                    btnAdd.classList.remove('d-none');
                    qtyWrapper.classList.add('d-none');
                    qtyWrapper.classList.remove('d-flex');
                    card.querySelector('.qty-display').textContent = '0';
                }

                updateCartUI();
            });
        });

        // Update Sticky bar labels
        cartItemCount.textContent = `${totalItems} dish${totalItems > 1 ? 'es' : ''} in cart`;
        cartTotalDisplay.innerHTML = `&#8377;${totalPrice.toFixed(2)}`;
        stickyCartBar.classList.remove('d-none');

        // Update Drawer labels
        drawerSubtotal.innerHTML = `&#8377;${totalPrice.toFixed(2)}`;
        drawerTotal.innerHTML = `&#8377;${totalPrice.toFixed(2)}`;
    }
});
</script>
