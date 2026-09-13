<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

<div class="menu-client-container">
    <!-- Restaurant Atmospheric Hero Banner -->
    <header class="menu-hero-header position-relative text-white">
        <div class="hero-bg-cover" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&auto=format&fit=crop&q=80');"></div>
        <div class="hero-backdrop-overlay"></div>
        
        <div class="container position-relative py-4 py-md-5">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-auto text-center text-md-start">
                    <div class="restaurant-logo-circle shadow-lg mx-auto mx-md-0">
                        <i class="bi bi-shop"></i>
                    </div>
                </div>
                <div class="col-12 col-md text-center text-md-start">
                    <!-- Status Badges -->
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start align-items-center gap-2 mb-2">
                        <span class="badge glass-badge text-white px-3 py-1.5 rounded-pill fw-semibold">
                            <span class="status-pulse-dot me-1.5"></span> Open Now &bull; Fresh to Order
                        </span>
                        <span class="badge glass-badge text-warning-emphasis px-3 py-1.5 rounded-pill fw-bold bg-warning-subtle border-0">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Seated at <strong><?= e($tableName) ?></strong>
                        </span>
                        <span class="badge glass-badge text-white px-3 py-1.5 rounded-pill fw-bold">
                            <i class="bi bi-star-fill text-warning me-1"></i> <?= e(number_format((float)$restaurant['rating'], 1)) ?> <span class="fw-normal text-white-50 ms-1">(<?= e($restaurant['reviews_count']) ?> reviews)</span>
                        </span>
                    </div>

                    <!-- Restaurant Title & Description -->
                    <h1 class="restaurant-title mb-2 display-6 fw-bold font-display text-white"><?= e($restaurant['name']) ?></h1>
                    <p class="restaurant-description text-white-50 mb-3 max-w-700"><?= e($restaurant['description']) ?></p>

                    <!-- Cuisine & Contact Pills -->
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start align-items-center gap-2">
                        <span class="cuisine-pill glass-pill"><i class="bi bi-globe2 me-1.5 text-success-light"></i><?= e($restaurant['cuisine']) ?></span>
                        <?php if (!empty($restaurant['phone'])): ?>
                            <span class="glass-pill small"><i class="bi bi-telephone me-1.5 text-warning"></i><?= e($restaurant['phone']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($restaurant['address'])): ?>
                            <span class="glass-pill small d-none d-sm-inline-flex"><i class="bi bi-pin-map me-1.5 text-info"></i><?= e($restaurant['address']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Session Feedback Alerts -->
    <div class="container mt-3">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 shadow-sm rounded-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div><?= e($error) ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 shadow-sm rounded-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div><?= e($success) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sticky Live Search and Dietary Filter Bar -->
    <div class="search-sticky-wrapper py-2.5">
        <div class="container">
            <div class="search-input-group shadow-sm mb-2.5">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="menu-search-input" class="form-control border-0" placeholder="Search dishes, ingredients, cuisines (e.g. Pizza, Dosa, Ramen, Truffle)..." autocomplete="off">
                <button type="button" id="clear-search-btn" class="btn btn-sm btn-link text-muted d-none" title="Clear Search">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </button>
            </div>

            <!-- Instant Dietary Quick-Filters -->
            <div class="diet-filter-scroll-wrapper d-flex gap-2 overflow-x-auto text-nowrap pb-1">
                <button class="btn btn-diet-filter active" data-diet-filter="all">
                    <i class="bi bi-grid-fill me-1"></i> All Dishes
                </button>
                <button class="btn btn-diet-filter" data-diet-filter="veg">
                    <span class="diet-dot veg-dot me-1.5"></span> Pure Veg
                </button>
                <button class="btn btn-diet-filter" data-diet-filter="non_veg">
                    <span class="diet-dot non-veg-dot me-1.5"></span> Non-Veg
                </button>
                <button class="btn btn-diet-filter" data-diet-filter="vegan">
                    <span class="diet-dot vegan-dot me-1.5"></span> 100% Vegan
                </button>
                <button class="btn btn-diet-filter" data-diet-filter="jain">
                    <span class="diet-dot jain-dot me-1.5"></span> Jain Friendly
                </button>
                <button class="btn btn-diet-filter" data-diet-filter="featured">
                    <i class="bi bi-star-fill text-warning me-1"></i> Chef's Specials
                </button>
            </div>

            <div id="search-results-count" class="small text-muted mt-1 px-1 d-none"></div>
        </div>
    </div>

    <!-- Category Selector Navigation -->
    <nav class="category-nav">
        <div class="container">
            <div class="category-scroll-wrapper d-flex gap-2 py-2.5 overflow-x-auto text-nowrap">
                <button class="btn btn-category active" data-category="all">
                    <i class="bi bi-collection-fill me-1"></i> All Categories
                </button>
                <?php foreach ($categories as $cat): ?>
                    <?php 
                        $catId = (int) $cat['id'];
                        $count = count($groupedFoods[$catId] ?? []);
                        if ($count === 0) continue;
                    ?>
                    <button class="btn btn-category" data-category="<?= e($cat['id']) ?>">
                        <?= e($cat['name']) ?> <span class="cat-count-badge"><?= $count ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <!-- Main Dining Menu Content Area -->
    <main class="menu-main container my-4">

        <!-- Empty search result alert -->
        <div id="no-search-results" class="text-center py-5 d-none bg-white rounded-4 shadow-sm border p-4 my-4">
            <div class="empty-search-icon mb-3">
                <i class="bi bi-search text-muted display-4"></i>
            </div>
            <h3 class="h5 fw-bold text-dark mb-1">No dishes match your criteria</h3>
            <p class="text-muted small mb-3">We couldn't find any dish matching your filter. Try adjusting your search keyword or diet filter.</p>
            <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-semibold" id="reset-search-btn">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters & View All
            </button>
        </div>

        <?php 
            // Compute featured dishes for top spotlight
            $featuredFoods = array_values(array_filter($foods ?? [], fn($f) => !empty($f['is_featured'])));
        ?>

        <!-- Chef's Specials Showcase (Hero Carousel / Grid) -->
        <?php if (!empty($featuredFoods)): ?>
            <section class="chef-specials-section mb-5" id="section-chef-specials">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1 small fw-bold">
                                <i class="bi bi-fire me-1 text-danger"></i> Handpicked Highlights
                            </span>
                        </div>
                        <h2 class="h3 fw-bold text-dark font-display mb-0">Chef's Signature Recommendations</h2>
                    </div>
                    <span class="text-muted small d-none d-md-inline">Our most loved creations</span>
                </div>

                <div class="chef-specials-scroll-container d-flex gap-3 overflow-x-auto pb-3">
                    <?php foreach ($featuredFoods as $feat): 
                        $fId = (int) $feat['id'];
                        $fImg = !empty($feat['image']) ? $feat['image'] : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80';
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
                        $dClass = $dietColors[$feat['food_type']] ?? 'bg-secondary';
                        $dLabel = $dietLabels[$feat['food_type']] ?? strtoupper($feat['food_type']);
                        $hasOpts = !empty($feat['variants']) || !empty($feat['customizations']);
                    ?>
                        <div class="chef-special-card card border-0 shadow-sm rounded-4 overflow-hidden flex-shrink-0 cursor-pointer" onclick="openFoodModal(<?= $fId ?>)">
                            <div class="special-img-wrapper position-relative">
                                <img src="<?= e($fImg) ?>" alt="<?= e($feat['name']) ?>" class="special-food-img w-100" loading="lazy">
                                <div class="special-gradient-overlay"></div>
                                
                                <!-- Floating Diet and Bestseller Badges -->
                                <div class="special-badges-top position-absolute top-0 start-0 w-100 p-2.5 d-flex justify-content-between align-items-center">
                                    <span class="badge glass-badge-dark text-white rounded-pill px-2.5 py-1 small d-flex align-items-center gap-1.5">
                                        <span class="diet-icon-box <?= $dClass ?>"><span class="diet-inner-dot"></span></span>
                                        <span class="small fw-semibold"><?= $dLabel ?></span>
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-2.5 py-1 small shadow-sm">
                                        <i class="bi bi-star-fill me-0.5"></i> Chef's Choice
                                    </span>
                                </div>

                                <!-- Floating Bottom Info (Prep Time & Calories) -->
                                <div class="special-info-bottom position-absolute bottom-0 start-0 w-100 p-3 text-white">
                                    <h3 class="h5 fw-bold text-white mb-1 text-shadow"><?= e($feat['name']) ?></h3>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-white fs-5 fw-extrabold">&#8377;<?= e(number_format((float)$feat['base_price'], 2)) ?></span>
                                            <?php if (!empty($feat['preparation_time'])): ?>
                                                <span class="badge glass-badge-dark small py-1 px-2"><i class="bi bi-clock me-1"></i><?= e($feat['preparation_time']) ?>m</span>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light fw-bold rounded-pill px-3 py-1 text-success shadow-sm btn-quick-order" onclick="event.stopPropagation(); openFoodModal(<?= $fId ?>)">
                                            <?= $hasOpts ? 'Customise' : 'Add +' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Category Sections with Large Hero Food Cards -->
        <?php if (empty($categories)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-receipt display-4 text-secondary-subtle"></i>
                <p class="mt-3">The menu is currently being prepared. Please check back shortly.</p>
            </div>
        <?php else: ?>
            <div class="menu-items-container">
                <?php foreach ($categories as $cat): 
                    $catId = (int) $cat['id'];
                    $dishList = $groupedFoods[$catId] ?? [];
                    if ($dishList === []) continue;
                ?>
                    <section class="category-section mb-5" data-section-cat="<?= $catId ?>" id="cat-section-<?= $catId ?>">
                        <!-- Category Header -->
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2.5 mb-4">
                            <div>
                                <h2 class="category-section-title h4 mb-0 fw-bold font-display text-dark"><?= e($cat['name']) ?></h2>
                                <span class="text-muted small">Handcrafted fresh to order</span>
                            </div>
                            <span class="badge bg-light text-secondary border rounded-pill px-3 py-1.5"><?= count($dishList) ?> items</span>
                        </div>

                        <!-- 3-Column Image-First Food Cards Grid -->
                        <div class="row g-4">
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
                                
                                $hasOptions = !empty($food['variants']) || !empty($food['customizations']);
                                $foodImg = !empty($food['image']) ? $food['image'] : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80';
                            ?>
                                <div class="col-12 col-md-6 col-lg-4 menu-item-col" 
                                     data-food-id="<?= $foodId ?>"
                                     data-food-name="<?= e(strtolower($food['name'])) ?>"
                                     data-category-id="<?= $catId ?>"
                                     data-ingredients="<?= e(strtolower($food['ingredients'] ?? '')) ?>"
                                     data-diet="<?= e(strtolower($food['food_type'])) ?>"
                                     data-is-featured="<?= !empty($food['is_featured']) ? '1' : '0' ?>"
                                     data-description="<?= e(strtolower($food['description'] ?? '')) ?>">
                                    
                                    <!-- Image-Centric Food Card -->
                                    <div class="card h-100 food-hero-card border-0 shadow-sm rounded-4 overflow-hidden">
                                        <!-- Top Hero Photography -->
                                        <div class="food-hero-img-box position-relative cursor-pointer" onclick="openFoodModal(<?= $foodId ?>)">
                                            <img src="<?= e($foodImg) ?>" alt="<?= e($food['name']) ?>" class="food-hero-img w-100" loading="lazy">
                                            
                                            <!-- Vignette Overlays -->
                                            <div class="img-vignette-top"></div>
                                            <div class="img-vignette-bottom"></div>

                                            <!-- Floating Top Badges (Diet & Bestseller) -->
                                            <div class="position-absolute top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center z-2">
                                                <span class="diet-pill-badge glass-badge-dark text-white rounded-pill px-2.5 py-1 d-flex align-items-center gap-1.5 shadow-sm">
                                                    <span class="diet-icon-box <?= $dietClass ?>"><span class="diet-inner-dot"></span></span>
                                                    <span class="small fw-semibold text-uppercase" style="font-size: 0.7rem;"><?= $dietLabel ?></span>
                                                </span>

                                                <?php if (!empty($food['is_featured'])): ?>
                                                    <span class="badge bg-warning text-dark border-0 rounded-pill px-2.5 py-1 shadow-sm fw-bold" style="font-size: 0.7rem;">
                                                        <i class="bi bi-star-fill text-dark me-0.5"></i> Popular
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Floating Bottom Badges over photo (Prep Time & Calories & Customisable) -->
                                            <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex justify-content-between align-items-end z-2 text-white">
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                    <?php if (!empty($food['preparation_time'])): ?>
                                                        <span class="badge glass-badge-dark small py-1 px-2" style="font-size: 0.7rem;">
                                                            <i class="bi bi-clock me-1 text-warning"></i><?= e($food['preparation_time']) ?>m
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($food['calories'])): ?>
                                                        <span class="badge glass-badge-dark small py-1 px-2" style="font-size: 0.7rem;">
                                                            <i class="bi bi-fire me-1 text-danger"></i><?= e($food['calories']) ?> kcal
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if ($hasOptions): ?>
                                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-2.5 py-1 small fw-bold" style="font-size: 0.68rem;">
                                                        <i class="bi bi-sliders me-1"></i>Customise
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Card Details Body below photo -->
                                        <div class="card-body p-3 p-sm-4 d-flex flex-column justify-content-between">
                                            <div>
                                                <!-- Title -->
                                                <h3 class="h5 fw-bold text-dark food-card-title mb-1.5 cursor-pointer" onclick="openFoodModal(<?= $foodId ?>)">
                                                    <?= e($food['name']) ?>
                                                </h3>

                                                <!-- Description -->
                                                <?php if (!empty($food['description'])): ?>
                                                    <p class="text-secondary small mb-2.5 text-truncate-2" style="font-size: 0.83rem;"><?= e($food['description']) ?></p>
                                                <?php endif; ?>

                                                <!-- Ingredients preview if available -->
                                                <?php if (!empty($food['ingredients'])): ?>
                                                    <div class="ingredients-preview text-muted small mb-3 text-truncate" style="font-size: 0.75rem;">
                                                        <i class="bi bi-dot text-success me-0.5"></i><?= e($food['ingredients']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Bottom Price and Action Row -->
                                            <div class="d-flex justify-content-between align-items-center pt-2.5 border-top border-light-subtle mt-auto">
                                                <div>
                                                    <div class="d-flex align-items-baseline gap-1">
                                                        <span class="food-price fs-5 fw-extrabold text-success">&#8377;<?= e(number_format((float)$food['base_price'], 2)) ?></span>
                                                    </div>
                                                    <?php if (!empty($food['serving_size'])): ?>
                                                        <span class="text-muted small d-block" style="font-size: 0.7rem;"><?= e($food['serving_size']) ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Action Button -->
                                                <button type="button" 
                                                        class="btn btn-outline-success fw-bold rounded-pill px-3 py-1.5 btn-food-action d-flex align-items-center gap-1 shadow-xs"
                                                        onclick="openFoodModal(<?= $foodId ?>)">
                                                    <span><?= $hasOptions ? 'Customise' : 'Add' ?></span>
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Food Details & Customization Modal -->
                                <div class="modal fade food-details-modal" id="foodModal<?= $foodId ?>" tabindex="-1" aria-labelledby="foodModalLabel<?= $foodId ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                                            <!-- Immersive Top Hero Image in Modal -->
                                            <div class="position-relative modal-hero-wrapper">
                                                <img src="<?= e($foodImg) ?>" alt="<?= e($food['name']) ?>" class="modal-hero-img w-100">
                                                <button type="button" class="btn-close modal-close-overlay" data-bs-dismiss="modal" aria-label="Close"></button>
                                                
                                                <div class="modal-hero-overlay p-4 d-flex flex-column justify-content-end">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <span class="diet-icon-box <?= $dietClass ?>">
                                                            <span class="diet-inner-dot"></span>
                                                        </span>
                                                        <span class="badge glass-badge-dark text-white px-3 py-1 text-uppercase fw-semibold" style="font-size: 0.75rem;"><?= $dietLabel ?></span>
                                                        <?php if (!empty($food['serving_size'])): ?>
                                                            <span class="badge glass-badge-dark text-white px-2.5 py-1"><i class="bi bi-box-seam me-1"></i><?= e($food['serving_size']) ?></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($food['is_featured'])): ?>
                                                            <span class="badge bg-warning text-dark px-2.5 py-1 fw-bold"><i class="bi bi-star-fill me-1"></i>Chef's Recommendation</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h3 class="h3 fw-bold text-white mb-1 text-shadow font-display" id="foodModalLabel<?= $foodId ?>"><?= e($food['name']) ?></h3>
                                                    <div class="fs-4 fw-extrabold text-success-light text-shadow">&#8377;<?= e(number_format((float)$food['base_price'], 2)) ?></div>
                                                </div>
                                            </div>

                                            <div class="modal-body p-4">
                                                <!-- Description -->
                                                <?php if (!empty($food['description'])): ?>
                                                    <p class="text-secondary small mb-3 lead-sm"><?= e($food['description']) ?></p>
                                                <?php endif; ?>

                                                <!-- Macro Nutritional Breakdown Grid -->
                                                <div class="nutrition-pill-box p-3 rounded-4 bg-light border mb-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <span class="small fw-bold text-dark text-uppercase tracking-wider" style="font-size: 0.7rem;"><i class="bi bi-activity text-success me-1"></i>Nutritional Profile</span>
                                                        <span class="small text-muted" style="font-size: 0.7rem;">per portion</span>
                                                    </div>
                                                    <div class="row g-2 text-center">
                                                        <div class="col-3">
                                                            <div class="p-2 rounded-3 bg-white border-0 shadow-xs">
                                                                <span class="d-block text-muted text-uppercase" style="font-size: 0.65rem;">Calories</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['calories'] !== null ? e($food['calories']) : '-' ?></span>
                                                                <span class="text-muted d-block" style="font-size: 0.65rem;">kcal</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="p-2 rounded-3 bg-white border-0 shadow-xs">
                                                                <span class="d-block text-muted text-uppercase" style="font-size: 0.65rem;">Protein</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['protein'] !== null ? e($food['protein']) : '-' ?></span>
                                                                <span class="text-muted d-block" style="font-size: 0.65rem;">grams</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="p-2 rounded-3 bg-white border-0 shadow-xs">
                                                                <span class="d-block text-muted text-uppercase" style="font-size: 0.65rem;">Carbs</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['carbs'] !== null ? e($food['carbs']) : '-' ?></span>
                                                                <span class="text-muted d-block" style="font-size: 0.65rem;">grams</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="p-2 rounded-3 bg-white border-0 shadow-xs">
                                                                <span class="d-block text-muted text-uppercase" style="font-size: 0.65rem;">Fats</span>
                                                                <span class="fw-bold text-dark fs-6"><?= $food['fat'] !== null ? e($food['fat']) : '-' ?></span>
                                                                <span class="text-muted d-block" style="font-size: 0.65rem;">grams</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ingredients & Allergens -->
                                                <?php if (!empty($food['ingredients'])): ?>
                                                    <div class="mb-3">
                                                        <h4 class="h6 fw-bold text-dark mb-1"><i class="bi bi-egg me-1.5 text-success"></i>Ingredients & Prep</h4>
                                                        <p class="small text-secondary mb-0 bg-light p-2.5 rounded-3 border-0"><?= e($food['ingredients']) ?></p>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($food['allergens']) && strtolower($food['allergens']) !== 'none'): ?>
                                                    <div class="alert alert-warning border-warning-subtle d-flex gap-2 py-2 px-3 rounded-3 mb-3 small" role="alert">
                                                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                                        <div>Contains Allergens: <strong><?= e($food['allergens']) ?></strong></div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Customization Form -->
                                                <form id="foodForm<?= $foodId ?>" onsubmit="return false;">
                                                    <!-- Variants (Single Selection Radio) -->
                                                    <?php if (!empty($food['variants'])): ?>
                                                        <div class="customization-group mb-4">
                                                            <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-2">
                                                                <span><i class="bi bi-sliders me-1 text-success"></i> Choose Portion / Size</span>
                                                                <span class="badge bg-secondary-subtle text-secondary small">Required</span>
                                                            </label>
                                                            <div class="variants-options-grid d-flex flex-column gap-2">
                                                                <label class="option-card d-flex align-items-center justify-content-between p-3 rounded-3 border cursor-pointer active">
                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <input type="radio" name="variant_<?= $foodId ?>" value="" data-price="0" data-name="Regular Base" class="form-check-input" checked onchange="recalculateModalTotal(<?= $foodId ?>)">
                                                                        <span class="fw-medium text-dark">Regular Base (Default)</span>
                                                                    </div>
                                                                    <span class="text-muted small">&#8377;0.00</span>
                                                                </label>
                                                                <?php foreach ($food['variants'] as $v): ?>
                                                                    <label class="option-card d-flex align-items-center justify-content-between p-3 rounded-3 border cursor-pointer">
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <input type="radio" name="variant_<?= $foodId ?>" value="<?= e($v['id']) ?>" data-price="<?= (float)$v['price_adjustment'] ?>" data-name="<?= e($v['name']) ?>" class="form-check-input" onchange="recalculateModalTotal(<?= $foodId ?>)">
                                                                            <span class="fw-medium text-dark"><?= e($v['name']) ?></span>
                                                                        </div>
                                                                        <span class="text-success fw-bold small">+&#8377;<?= e(number_format((float)$v['price_adjustment'], 2)) ?></span>
                                                                    </label>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <!-- Customizations (Multiple Add-on Checkboxes) -->
                                                    <?php if (!empty($food['customizations'])): ?>
                                                        <div class="customization-group mb-4">
                                                            <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-2">
                                                                <span><i class="bi bi-plus-circle me-1 text-success"></i> Extra Add-ons & Toppings</span>
                                                                <span class="badge bg-light text-secondary border small">Optional</span>
                                                            </label>
                                                            <div class="customizations-options-grid d-flex flex-column gap-2">
                                                                <?php foreach ($food['customizations'] as $c): ?>
                                                                    <label class="option-card d-flex align-items-center justify-content-between p-3 rounded-3 border cursor-pointer">
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <input type="checkbox" name="customization_<?= $foodId ?>[]" value="<?= e($c['id']) ?>" data-price="<?= (float)$c['price_adjustment'] ?>" data-name="<?= e($c['name']) ?>" class="form-check-input" onchange="recalculateModalTotal(<?= $foodId ?>)">
                                                                            <span class="fw-medium text-dark"><?= e($c['name']) ?></span>
                                                                        </div>
                                                                        <span class="text-success fw-bold small">+&#8377;<?= e(number_format((float)$c['price_adjustment'], 2)) ?></span>
                                                                    </label>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <!-- Chef Special Request / Notes -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark small mb-1">
                                                            <i class="bi bi-chat-dots me-1 text-success"></i> Special Request for Chef (Optional)
                                                        </label>
                                                        <input type="text" id="itemNote<?= $foodId ?>" class="form-control form-control-sm rounded-3" placeholder="e.g. Less spicy, dressing on side, extra crispy, no garlic">
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Sticky Modal Footer with Quantity Stepper & Price CTA -->
                                            <div class="modal-footer border-top bg-light p-3 d-flex align-items-center justify-content-between">
                                                <div class="modal-qty-control d-flex align-items-center gap-2 bg-white px-3 py-1.5 rounded-pill border shadow-xs">
                                                    <button type="button" class="btn btn-sm btn-link p-0 text-dark" onclick="adjustModalQty(<?= $foodId ?>, -1)"><i class="bi bi-dash-lg"></i></button>
                                                    <span id="modalQtyDisplay<?= $foodId ?>" class="fw-bold px-2 text-dark">1</span>
                                                    <button type="button" class="btn btn-sm btn-link p-0 text-dark" onclick="adjustModalQty(<?= $foodId ?>, 1)"><i class="bi bi-plus-lg"></i></button>
                                                </div>
                                                <button type="button" class="btn btn-success fw-bold px-4 py-2.5 rounded-pill shadow-sm d-flex align-items-center gap-2" onclick="addItemFromModal(<?= $foodId ?>, '<?= e(addslashes($food['name'])) ?>', <?= (float)$food['base_price'] ?>)">
                                                    <span>Add to Cart &bull;</span> <span id="modalTotalDisplay<?= $foodId ?>">&#8377;<?= e(number_format((float)$food['base_price'], 2)) ?></span>
                                                </button>
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

    <!-- Floating Sticky Cart Bar (Animated Slide-up) -->
    <div id="sticky-cart-bar" class="sticky-cart-bar d-none">
        <div class="container py-2.5">
            <div class="d-flex align-items-center justify-content-between bg-dark text-white p-3 rounded-4 shadow-xl border border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-3">
                    <div class="cart-icon-circle bg-success text-white">
                        <i class="bi bi-bag-check-fill"></i>
                        <span id="cart-item-count" class="cart-badge-counter">0</span>
                    </div>
                    <div>
                        <span class="d-block small text-white-50" style="font-size: 0.72rem;">Current Order Total</span>
                        <span id="cart-total-display" class="fs-5 fw-extrabold text-white">&#8377;0.00</span>
                    </div>
                </div>
                <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-pill d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#cartDrawerModal">
                    <span>View Cart</span> <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Slide-up Cart Drawer Modal -->
    <div class="modal fade" id="cartDrawerModal" tabindex="-1" aria-labelledby="cartDrawerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
            <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                <div class="modal-header bg-light border-bottom py-3">
                    <div>
                        <h4 class="modal-title h5 fw-bold text-dark mb-0" id="cartDrawerModalLabel"><i class="bi bi-bag-check text-success me-2"></i>Table Order Cart</h4>
                        <span class="text-muted small">Seated at <strong><?= e($tableName) ?></strong> &bull; <?= e($restaurant['name']) ?></span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="post" action="<?= e(url('/menu/checkout')) ?>" id="cartCheckoutForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= e($token) ?>">
                    <input type="hidden" name="cart_json" id="cart_json_input" value="">

                    <div class="modal-body p-4">
                        <!-- Items List -->
                        <div id="cart-items-lines" class="mb-4">
                            <!-- Populated dynamically via Javascript -->
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-light p-3 rounded-4 border mb-4">
                            <h5 class="h6 fw-bold text-dark mb-3"><i class="bi bi-person-circle me-1.5 text-success"></i>Diner Contact Information</h5>
                            <div class="mb-2.5">
                                <label class="form-label small text-muted mb-1">Your Name</label>
                                <input type="text" name="customer_name" class="form-control form-control-sm rounded-3" placeholder="e.g. Rahul Sharma" value="Guest">
                            </div>
                            <div class="mb-2.5">
                                <label class="form-label small text-muted mb-1">Phone Number (For Digital Receipt / Status)</label>
                                <input type="tel" name="customer_phone" class="form-control form-control-sm rounded-3" placeholder="e.g. 9876543210" required pattern="[0-9]{10}">
                            </div>
                            <div>
                                <label class="form-label small text-muted mb-1">Kitchen / Table Note (Optional)</label>
                                <textarea name="note" rows="2" class="form-control form-control-sm rounded-3" placeholder="Any extra cutlery, water preferences, or service notes..."></textarea>
                            </div>
                        </div>

                        <!-- Bill Breakdown -->
                        <div class="bill-summary border-top pt-3">
                            <div class="d-flex justify-content-between text-secondary small mb-1.5">
                                <span>Items Subtotal</span>
                                <span id="drawer-subtotal" class="fw-semibold text-dark">&#8377;0.00</span>
                            </div>
                            <div class="d-flex justify-content-between text-secondary small mb-1.5">
                                <span>GST & Restaurant Taxes (5%)</span>
                                <span id="drawer-tax" class="fw-semibold text-dark">&#8377;0.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center fs-5 fw-extrabold text-dark border-top pt-2 mt-2">
                                <span>Grand Total</span>
                                <span id="drawer-total" class="text-success">&#8377;0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-top bg-light p-3">
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2" id="btn-place-order">
                            <i class="bi bi-check-circle-fill"></i> Place Order to Kitchen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stylistic Image-Centric Theme -->
<style>
:root {
    --bs-font-sans-serif: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    --bs-success: #059669;
    --bs-success-rgb: 5, 150, 105;
}
body {
    background-color: #f8fafc;
    font-family: 'Outfit', sans-serif;
    color: #1e293b;
    padding-bottom: 100px;
}
.font-display {
    font-family: 'Playfair Display', Georgia, serif;
}
.cursor-pointer { cursor: pointer; }
.shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.shadow-xl { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
.shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.6); }

/* Atmospheric Hero Header */
.menu-hero-header {
    overflow: hidden;
    background-color: #0f172a;
}
.hero-bg-cover {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    filter: blur(2px) brightness(0.4);
    transform: scale(1.05);
}
.hero-backdrop-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(15,23,42,0.6) 0%, rgba(15,23,42,0.92) 100%);
}
.restaurant-logo-circle {
    width: 80px;
    height: 80px;
    border-radius: 24px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: 3px solid rgba(255,255,255,0.25);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
}
.glass-badge {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.2);
}
.glass-pill {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    color: #e2e8f0;
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
}
.glass-badge-dark {
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.text-success-light { color: #6ee7b7; }
.status-pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #34d399;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.35);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7); }
    70% { box-shadow: 0 0 0 6px rgba(52, 211, 153, 0); }
    100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0); }
}
.max-w-700 { max-width: 700px; }

/* Search and Filters Sticky Section */
.search-sticky-wrapper {
    position: sticky;
    top: 0;
    z-index: 1020;
    background: rgba(248, 250, 252, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #e2e8f0;
}
.search-input-group {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 50px;
    padding: 0.4rem 1.1rem;
    border: 1.5px solid #e2e8f0;
    transition: all 0.2s ease;
}
.search-input-group:focus-within {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
}
.search-icon { color: #94a3b8; font-size: 1.1rem; }

/* Dietary Quick Filters */
.diet-filter-scroll-wrapper::-webkit-scrollbar { height: 2px; }
.btn-diet-filter {
    background-color: white;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    color: #475569;
    font-weight: 600;
    padding: 0.35rem 0.85rem;
    font-size: 0.78rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}
.btn-diet-filter:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}
.btn-diet-filter.active {
    background-color: #0f172a;
    border-color: #0f172a;
    color: white;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.25);
}
.diet-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}
.veg-dot { background-color: #16a34a; }
.non-veg-dot { background-color: #dc2626; border-radius: 2px; }
.vegan-dot { background-color: #0d9488; }
.jain-dot { background-color: #d97706; }

/* Category Navigation */
.category-nav {
    background-color: white;
    border-bottom: 1px solid #edf2f7;
}
.category-scroll-wrapper::-webkit-scrollbar { height: 3px; }
.category-scroll-wrapper::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 3px; }
.btn-category {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    color: #475569;
    font-weight: 600;
    padding: 0.45rem 1.1rem;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-category:hover { background-color: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
.btn-category.active {
    background-color: #059669;
    border-color: #059669;
    color: white;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);
}
.cat-count-badge {
    background: rgba(0,0,0,0.08);
    font-size: 0.7rem;
    padding: 0.1rem 0.45rem;
    border-radius: 50px;
}
.btn-category.active .cat-count-badge {
    background: rgba(255,255,255,0.25);
    color: white;
}

/* Chef Specials Spotlight */
.chef-specials-scroll-container::-webkit-scrollbar { height: 4px; }
.chef-specials-scroll-container::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
.chef-special-card {
    width: 310px;
    background: #0f172a;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.chef-special-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 30px rgba(0,0,0,0.15) !important;
}
.special-img-wrapper {
    height: 195px;
    overflow: hidden;
}
.special-food-img {
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.chef-special-card:hover .special-food-img {
    transform: scale(1.08);
}
.special-gradient-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.85) 100%);
}

/* 3-Column Image-First Hero Food Cards */
.food-hero-card {
    background: white;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.food-hero-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 32px -8px rgba(0,0,0,0.12) !important;
}
.food-hero-img-box {
    height: 215px;
    overflow: hidden;
    border-radius: 1rem 1rem 0 0;
}
.food-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), filter 0.3s ease;
}
.food-hero-card:hover .food-hero-img {
    transform: scale(1.08);
    filter: brightness(1.03);
}
.img-vignette-top {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 70px;
    background: linear-gradient(180deg, rgba(0,0,0,0.55) 0%, transparent 100%);
    pointer-events: none;
}
.img-vignette-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: linear-gradient(0deg, rgba(0,0,0,0.75) 0%, transparent 100%);
    pointer-events: none;
}
.food-card-title {
    font-size: 1.05rem;
    transition: color 0.15s ease;
}
.food-card-title:hover { color: #059669 !important; }
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.45;
}
.fw-extrabold { font-weight: 800; }

/* Diet Indicators */
.diet-icon-box {
    width: 15px;
    height: 15px;
    border: 1.5px solid;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
}
.diet-inner-dot {
    width: 6.5px;
    height: 6.5px;
    border-radius: 50%;
}
.diet-veg { border-color: #22c55e; }
.diet-veg .diet-inner-dot { background-color: #22c55e; }
.diet-non-veg { border-color: #ef4444; }
.diet-non-veg .diet-inner-dot { background-color: #ef4444; border-radius: 1px; }
.diet-vegan { border-color: #14b8a6; }
.diet-vegan .diet-inner-dot { background-color: #14b8a6; }
.diet-jain { border-color: #f59e0b; }
.diet-jain .diet-inner-dot { background-color: #f59e0b; }

/* Food Action Buttons */
.btn-food-action {
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    font-size: 0.85rem;
}
.btn-food-action:hover {
    background-color: #059669;
    border-color: #059669;
    color: white;
    transform: scale(1.04);
}

/* Modal Hero */
.modal-hero-wrapper {
    height: 270px;
    overflow: hidden;
}
.modal-hero-img {
    height: 100%;
    object-fit: cover;
}
.modal-close-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255,255,255,0.92);
    border-radius: 50%;
    padding: 0.6rem;
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.modal-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.85) 100%);
}

/* Option Cards (Variants & Addons) */
.option-card {
    transition: all 0.2s ease;
    background: #ffffff;
}
.option-card:hover {
    border-color: #059669 !important;
    background-color: #f0fdf4;
}
.option-card input:checked ~ span {
    color: #059669;
}

/* Floating Sticky Cart Bar */
.sticky-cart-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1040;
    animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.cart-icon-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    position: relative;
}
.cart-badge-counter {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 0.72rem;
    font-weight: 800;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}
</style>

<!-- Client Interactivity JS -->
<script>
// Dynamic Cart Store
const cartStore = []; // array of { food_id, name, base_price, unit_price, quantity, variant_id, variant_name, customization_ids, customization_names, note }

// Open Food Customisation Modal
function openFoodModal(foodId) {
    const modalEl = document.getElementById('foodModal' + foodId);
    if (modalEl) {
        // Reset qty
        const qtyDisplay = document.getElementById('modalQtyDisplay' + foodId);
        if (qtyDisplay) qtyDisplay.textContent = '1';
        recalculateModalTotal(foodId);
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

// Adjust quantity inside Food Modal
function adjustModalQty(foodId, delta) {
    const qtyDisplay = document.getElementById('modalQtyDisplay' + foodId);
    if (!qtyDisplay) return;
    let qty = parseInt(qtyDisplay.textContent) || 1;
    qty = Math.max(1, qty + delta);
    qtyDisplay.textContent = qty;
    recalculateModalTotal(foodId);
}

// Recalculate Modal Total Price based on selected variant + customizations
function recalculateModalTotal(foodId) {
    const modal = document.getElementById('foodModal' + foodId);
    if (!modal) return;

    let base = parseFloat(modal.querySelector('.text-success-light')?.textContent?.replace(/[^\d.]/g, '') || 0);
    // Find variant
    const variantRadio = modal.querySelector(`input[name="variant_${foodId}"]:checked`);
    let varPrice = variantRadio ? parseFloat(variantRadio.getAttribute('data-price') || 0) : 0;

    // Find customizations
    let custPrice = 0;
    const custChecks = modal.querySelectorAll(`input[name="customization_${foodId}[]"]:checked`);
    custChecks.forEach(c => {
        custPrice += parseFloat(c.getAttribute('data-price') || 0);
    });

    const qtyDisplay = document.getElementById('modalQtyDisplay' + foodId);
    const qty = parseInt(qtyDisplay?.textContent || 1);

    const singleUnitPrice = base + varPrice + custPrice;
    const grandTotal = singleUnitPrice * qty;

    const display = document.getElementById('modalTotalDisplay' + foodId);
    if (display) {
        display.innerHTML = '&#8377;' + grandTotal.toFixed(2);
    }
}

// Add Item From Modal to Cart
function addItemFromModal(foodId, foodName, basePrice) {
    const modalEl = document.getElementById('foodModal' + foodId);
    if (!modalEl) return;

    const qty = parseInt(document.getElementById('modalQtyDisplay' + foodId)?.textContent || 1);
    
    // Variant
    const variantRadio = modalEl.querySelector(`input[name="variant_${foodId}"]:checked`);
    let variantId = variantRadio && variantRadio.value ? parseInt(variantRadio.value) : null;
    let variantName = variantRadio ? variantRadio.getAttribute('data-name') : '';
    let variantPrice = variantRadio ? parseFloat(variantRadio.getAttribute('data-price') || 0) : 0;

    // Customizations
    const custChecks = modalEl.querySelectorAll(`input[name="customization_${foodId}[]"]:checked`);
    let customizationIds = [];
    let customizationNames = [];
    let custTotal = 0;
    custChecks.forEach(c => {
        customizationIds.push(parseInt(c.value));
        customizationNames.push(c.getAttribute('data-name'));
        custTotal += parseFloat(c.getAttribute('data-price') || 0);
    });

    // Note
    const noteInput = document.getElementById('itemNote' + foodId);
    const itemNote = noteInput ? noteInput.value.trim() : '';

    const unitPrice = basePrice + variantPrice + custTotal;

    // Check if an identical item configuration already exists in cartStore
    const existingIndex = cartStore.findIndex(item => 
        item.food_id === foodId &&
        item.variant_id === variantId &&
        JSON.stringify(item.customization_ids) === JSON.stringify(customizationIds) &&
        item.note === itemNote
    );

    if (existingIndex > -1) {
        cartStore[existingIndex].quantity += qty;
    } else {
        cartStore.push({
            food_id: foodId,
            name: foodName,
            base_price: basePrice,
            unit_price: unitPrice,
            quantity: qty,
            variant_id: variantId,
            variant_name: variantName,
            customization_ids: customizationIds,
            customization_names: customizationNames,
            note: itemNote
        });
    }

    // Close modal
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();

    // Reset item note
    if (noteInput) noteInput.value = '';

    // Update UI
    syncCartUI();
}

// Adjust quantity in Cart Drawer
function adjustCartQty(index, delta) {
    if (cartStore[index]) {
        cartStore[index].quantity += delta;
        if (cartStore[index].quantity <= 0) {
            cartStore.splice(index, 1);
        }
        syncCartUI();
    }
}

// Remove item from Cart Drawer
function removeCartItem(index) {
    if (cartStore[index]) {
        cartStore.splice(index, 1);
        syncCartUI();
    }
}

// Synchronize Cart UI (Floating Bar, Drawer Lines, Totals, Hidden JSON)
function syncCartUI() {
    const stickyBar = document.getElementById('sticky-cart-bar');
    const itemCountDisplay = document.getElementById('cart-item-count');
    const totalDisplay = document.getElementById('cart-total-display');
    const drawerLines = document.getElementById('cart-items-lines');
    const drawerSubtotal = document.getElementById('drawer-subtotal');
    const drawerTax = document.getElementById('drawer-tax');
    const drawerTotal = document.getElementById('drawer-total');
    const jsonInput = document.getElementById('cart_json_input');

    let totalItems = 0;
    let subtotal = 0;

    cartStore.forEach(item => {
        totalItems += item.quantity;
        subtotal += item.unit_price * item.quantity;
    });

    const tax = Math.round(subtotal * 0.05 * 100) / 100;
    const grandTotal = Math.round((subtotal + tax) * 100) / 100;

    // Sticky Bar
    if (totalItems > 0) {
        stickyBar.classList.remove('d-none');
        itemCountDisplay.textContent = totalItems;
        totalDisplay.innerHTML = '&#8377;' + grandTotal.toFixed(2);
    } else {
        stickyBar.classList.add('d-none');
    }

    // Drawer Lines
    if (drawerLines) {
        if (cartStore.length === 0) {
            drawerLines.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bag-x display-5 text-muted mb-2"></i>
                    <p class="mb-0">Your table cart is empty. Tap any dish to begin!</p>
                </div>
            `;
            const placeBtn = document.getElementById('btn-place-order');
            if (placeBtn) placeBtn.disabled = true;
        } else {
            let html = '<div class="d-flex flex-column gap-3">';
            cartStore.forEach((item, idx) => {
                const lineTotal = item.unit_price * item.quantity;
                html += `
                    <div class="cart-line-card p-3 rounded-4 bg-white border shadow-xs">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h5 class="h6 fw-bold text-dark mb-0">${escapeHtml(item.name)}</h5>
                                ${item.variant_name && item.variant_name !== 'Regular Base' ? `<span class="badge bg-secondary-subtle text-secondary me-1" style="font-size:0.7rem;">${escapeHtml(item.variant_name)}</span>` : ''}
                                ${item.customization_names && item.customization_names.length > 0 ? `<div class="small text-muted mt-0.5" style="font-size:0.75rem;">+ ${escapeHtml(item.customization_names.join(', '))}</div>` : ''}
                                ${item.note ? `<div class="small text-warning-emphasis bg-warning-subtle px-2 py-0.5 rounded mt-1" style="font-size:0.72rem;"><i class="bi bi-pencil me-1"></i>${escapeHtml(item.note)}</div>` : ''}
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="removeCartItem(${idx})" title="Remove item">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2.5 pt-2 border-top">
                            <div class="d-flex align-items-center gap-2 bg-light px-2.5 py-1 rounded-pill border">
                                <button type="button" class="btn btn-sm btn-link p-0 text-dark" onclick="adjustCartQty(${idx}, -1)"><i class="bi bi-dash"></i></button>
                                <span class="fw-bold px-1.5 text-dark small">${item.quantity}</span>
                                <button type="button" class="btn btn-sm btn-link p-0 text-dark" onclick="adjustCartQty(${idx}, 1)"><i class="bi bi-plus"></i></button>
                            </div>
                            <span class="fw-bold text-success fs-6">&#8377;${lineTotal.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            drawerLines.innerHTML = html;
            const placeBtn = document.getElementById('btn-place-order');
            if (placeBtn) placeBtn.disabled = false;
        }
    }

    // Bill numbers
    if (drawerSubtotal) drawerSubtotal.innerHTML = '&#8377;' + subtotal.toFixed(2);
    if (drawerTax) drawerTax.innerHTML = '&#8377;' + tax.toFixed(2);
    if (drawerTotal) drawerTotal.innerHTML = '&#8377;' + grandTotal.toFixed(2);

    // Update JSON input for server submission
    if (jsonInput) jsonInput.value = JSON.stringify(cartStore);
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
}

// Setup Live Search, Category Tabs & Dietary Filter Chips
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('menu-search-input');
    const clearSearchBtn = document.getElementById('clear-search-btn');
    const resetSearchBtn = document.getElementById('reset-search-btn');
    const noResults = document.getElementById('no-search-results');
    const searchCount = document.getElementById('search-results-count');
    const foodCards = document.querySelectorAll('.menu-item-col');
    const categoryBtns = document.querySelectorAll('.btn-category');
    const dietFilterBtns = document.querySelectorAll('.btn-diet-filter');
    const categorySections = document.querySelectorAll('.category-section');
    const chefSpecialsSection = document.getElementById('section-chef-specials');

    let currentCategory = 'all';
    let currentDietFilter = 'all';

    function applyFilter() {
        const query = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        if (query.length > 0) {
            clearSearchBtn.classList.remove('d-none');
        } else {
            clearSearchBtn.classList.add('d-none');
        }

        // Hide chef specials banner if filtering or searching
        if (chefSpecialsSection) {
            if (query !== '' || currentCategory !== 'all' || currentDietFilter !== 'all') {
                chefSpecialsSection.classList.add('d-none');
            } else {
                chefSpecialsSection.classList.remove('d-none');
            }
        }

        foodCards.forEach(card => {
            const cardCat = card.getAttribute('data-category-id');
            const name = card.getAttribute('data-food-name') || '';
            const ingredients = card.getAttribute('data-ingredients') || '';
            const description = card.getAttribute('data-description') || '';
            const diet = card.getAttribute('data-diet') || '';
            const isFeatured = card.getAttribute('data-is-featured') === '1';

            // Check Category filter
            const matchesCategory = (currentCategory === 'all' || cardCat === currentCategory);

            // Check Diet filter
            let matchesDiet = true;
            if (currentDietFilter === 'veg') {
                matchesDiet = (diet === 'veg' || diet === 'vegan' || diet === 'jain');
            } else if (currentDietFilter === 'non_veg') {
                matchesDiet = (diet === 'non_veg');
            } else if (currentDietFilter === 'vegan') {
                matchesDiet = (diet === 'vegan');
            } else if (currentDietFilter === 'jain') {
                matchesDiet = (diet === 'jain');
            } else if (currentDietFilter === 'featured') {
                matchesDiet = isFeatured;
            }

            // Check Search query
            const matchesQuery = query === '' || 
                                 name.includes(query) || 
                                 ingredients.includes(query) || 
                                 description.includes(query) || 
                                 diet.includes(query);

            if (matchesCategory && matchesDiet && matchesQuery) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });

        // Toggle category sections based on whether any dish inside is visible
        categorySections.forEach(sec => {
            const visibleInside = sec.querySelectorAll('.menu-item-col:not(.d-none)').length;
            if (visibleInside > 0) {
                sec.classList.remove('d-none');
            } else {
                sec.classList.add('d-none');
            }
        });

        // Show/Hide No Results
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
            searchCount.classList.add('d-none');
        } else {
            noResults.classList.add('d-none');
            if (query !== '' || currentDietFilter !== 'all') {
                searchCount.classList.remove('d-none');
                searchCount.textContent = `Showing ${visibleCount} delicious matching dish${visibleCount > 1 ? 'es' : ''}`;
            } else {
                searchCount.classList.add('d-none');
            }
        }
    }

    // Search events
    searchInput.addEventListener('input', applyFilter);
    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        applyFilter();
        searchInput.focus();
    });
    if (resetSearchBtn) {
        resetSearchBtn.addEventListener('click', () => {
            searchInput.value = '';
            currentCategory = 'all';
            currentDietFilter = 'all';
            categoryBtns.forEach(b => b.classList.toggle('active', b.getAttribute('data-category') === 'all'));
            dietFilterBtns.forEach(b => b.classList.toggle('active', b.getAttribute('data-diet-filter') === 'all'));
            applyFilter();
        });
    }

    // Category button events
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            applyFilter();

            // Smooth scroll to category section if specific category clicked
            if (currentCategory !== 'all') {
                const targetSec = document.getElementById('cat-section-' + currentCategory);
                if (targetSec) {
                    targetSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    // Diet Filter button events
    dietFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            dietFilterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentDietFilter = this.getAttribute('data-diet-filter');
            applyFilter();
        });
    });

    // Form submission validation
    const checkoutForm = document.getElementById('cartCheckoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            if (cartStore.length === 0) {
                e.preventDefault();
                alert('Your cart is empty. Please add delicious items before placing an order.');
                return false;
            }
            const submitBtn = document.getElementById('btn-place-order');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Sending Order to Kitchen...';
            }
        });
    }
});
</script>
