<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Guest Relations</span>
            <h1 class="h2 mb-1">Customer Reviews & Sentiment</h1>
            <p class="text-secondary mb-0">Track customer ratings, review feedback comments, and monitor dish-specific quality control.</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Summary Metrics Card -->
    <div class="col-md-4">
        <div class="dashboard-card card border-0 shadow-sm h-100 text-center p-4" style="background: linear-gradient(135deg, #1a4d2e 0%, #11341e 100%); color: white;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <span class="text-white-50 text-uppercase small fw-bold mb-2" style="letter-spacing: 0.05em;">Average Star Rating</span>
                <span class="display-3 fw-extrabold font-display text-white mb-2"><?= e(number_format((float)$summary['avg_rating'], 1)) ?></span>
                
                <div class="text-warning mb-3">
                    <?php 
                        $fullStars = (int) floor($summary['avg_rating']);
                        $hasHalf = ($summary['avg_rating'] - $fullStars) >= 0.5;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $fullStars) {
                                echo '<i class="bi bi-star-fill fs-4 mx-0.5"></i>';
                            } elseif ($i === $fullStars + 1 && $hasHalf) {
                                echo '<i class="bi bi-star-half fs-4 mx-0.5"></i>';
                            } else {
                                echo '<i class="bi bi-star fs-4 mx-0.5"></i>';
                            }
                        }
                    ?>
                </div>
                
                <span class="small text-white-50">Based on <strong><?= e($summary['total_reviews']) ?></strong> verified guest reviews</span>
            </div>
        </div>
    </div>

    <!-- Rating Distribution (Histogram) -->
    <div class="col-md-8">
        <div class="dashboard-card card border-0 shadow-sm h-100 p-4">
            <div class="card-body">
                <h3 class="h5 fw-bold text-dark mb-4"><i class="bi bi-bar-chart-fill text-success me-2"></i>Rating Distributions</h3>
                
                <div class="d-flex flex-column gap-3">
                    <?php 
                        $total = $summary['total_reviews'] ?: 1;
                        for ($star = 5; $star >= 1; $star--):
                            $count = $starsCount[$star] ?? 0;
                            $pct = round(($count / $total) * 100);
                            $progressClass = $star >= 4 ? 'bg-success' : ($star === 3 ? 'bg-warning' : 'bg-danger');
                    ?>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-secondary small fw-bold" style="width: 50px;"><?= $star ?> Stars</span>
                            <div class="progress flex-grow-1" style="height: 10px; border-radius: 50px;">
                                <div class="progress-bar <?= $progressClass ?>" role="progressbar" style="width: <?= $pct ?>%; border-radius: 50px;" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-secondary small" style="width: 50px; text-align: right;"><?= $count ?> (<?= $pct ?>%)</span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Feed -->
<div class="dashboard-card card border-0 shadow-sm">
    <div class="card-body p-4">
        <h3 class="h5 fw-bold text-dark mb-4"><i class="bi bi-chat-left-heart-fill text-success me-2"></i>Live Feedback Feed</h3>
        
        <?php if (empty($reviews)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-chat-left-quote display-4 text-secondary-subtle"></i>
                <p class="mt-3 fs-5">No customer reviews received yet.</p>
                <p class="small">Reviews submitted by guests once orders are completed will show up here.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($reviews as $review): ?>
                    <div class="p-3 border rounded-3 bg-light-subtle shadow-sm hover-shadow transition">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="h6 fw-bold mb-1 text-dark"><?= e($review['customer_name']) ?></h5>
                                <span class="text-muted small" style="font-size: 0.72rem;"><i class="bi bi-clock me-1"></i><?= e(date('M d, Y H:i', strtotime($review['created_at']))) ?></span>
                            </div>
                            <div class="text-warning">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi <?= $i <= (int)$review['rating'] ? 'bi-star-fill' : 'bi-star' ?> small mx-0.5"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <p class="text-dark small mb-2 text-secondary-emphasis" style="line-height: 1.5; font-style: italic;">
                            "<?= e($review['comment']) ?>"
                        </p>
                        
                        <?php if (!empty($review['food_name'])): ?>
                            <div class="d-flex align-items-center gap-1.5 mt-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle small px-2 py-1" style="font-size: 0.68rem;">
                                    <i class="bi bi-egg-fried me-1"></i>Dish Reviewed: <strong><?= e($review['food_name']) ?></strong>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
