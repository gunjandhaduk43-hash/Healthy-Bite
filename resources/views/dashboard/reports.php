<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Business Insights</span>
            <h1 class="h2 mb-1">Reports & Analytics</h1>
            <p class="text-secondary mb-0">Evaluate restaurant sales volume, average tickets, and customer food item popularity.</p>
        </div>
    </div>
</div>

<!-- Date Filter Form -->
<section class="dashboard-card card border-0 mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= e(url('/dashboard/reports')) ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-secondary fw-semibold small" for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= e($startDate) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label text-secondary fw-semibold small" for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= e($endDate) ?>" required>
            </div>
            <div class="col-md-4 d-grid d-md-block">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-filter"></i> Apply Date Filter
                </button>
                <a href="<?= e(url('/dashboard/reports')) ?>" class="btn btn-outline-secondary ms-md-2 mt-2 mt-md-0">Reset</a>
            </div>
        </form>
    </div>
</section>

<!-- Stats Metrics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <article class="dashboard-card card h-100 border-0 shadow-sm" style="border-left: 4px solid var(--bs-success) !important;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Sales Revenue</p>
                    <p class="stat-value mb-0 text-success">&#8377;<?= e(number_format((float)($metrics['revenue'] ?? 0), 2)) ?></p>
                    <small class="text-muted">Total gross (excl. cancelled)</small>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
        </article>
    </div>
    
    <div class="col-md-4">
        <article class="dashboard-card card h-100 border-0 shadow-sm" style="border-left: 4px solid #0dcaf0 !important;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Orders Count</p>
                    <p class="stat-value mb-0 text-info"><?= e($metrics['orders_count'] ?? 0) ?></p>
                    <small class="text-muted">Paid tickets served</small>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
            </div>
        </article>
    </div>
    
    <div class="col-md-4">
        <article class="dashboard-card card h-100 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Average Ticket Size</p>
                    <p class="stat-value mb-0 text-warning">&#8377;<?= e(number_format((float)($metrics['average_order'] ?? 0), 2)) ?></p>
                    <small class="text-muted">Average spend per table order</small>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </article>
    </div>
</div>

<div class="row g-4">
    <!-- Popular Items Section -->
    <div class="col-lg-7">
        <div class="dashboard-card card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-award text-success me-2"></i>Most Popular Dishes</h2>
                </div>
                
                <?php if (empty($popularItems)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-emoji-neutral display-4 text-secondary-subtle"></i>
                        <p class="mt-3">No sales registered in this date range.</p>
                    </div>
                <?php else: ?>
                    <div class="visual-list">
                        <!-- Header Row -->
                        <div class="row py-2.5 px-3 mb-2 visual-list-header border-bottom bg-light rounded text-uppercase text-secondary fw-bold" style="font-size: 0.75rem;">
                            <div class="col-5">Dish Name</div>
                            <div class="col-2 text-center">Tickets</div>
                            <div class="col-2 text-center">Qty Sold</div>
                            <div class="col-3 text-end">Sales Value</div>
                        </div>
                        <!-- List Items -->
                        <?php 
                            $maxQty = (int) ($popularItems[0]['total_quantity'] ?? 1); 
                            if ($maxQty <= 0) $maxQty = 1;
                        ?>
                        <?php foreach ($popularItems as $item): 
                            $qtyPct = round(((int)$item['total_quantity'] / $maxQty) * 100);
                        ?>
                            <div class="row align-items-center py-3 px-3 visual-list-item">
                                <div class="col-5">
                                    <span class="fw-bold text-dark fs-6 d-block mb-1"><?= e($item['item_name']) ?></span>
                                    <div class="progress" style="height: 6px; width: 80%; background-color: #f1f5f9; border-radius: 10px; overflow: hidden;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $qtyPct ?>%; border-radius: 10px; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);" aria-valuenow="<?= $qtyPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-2 text-center">
                                    <span class="text-secondary small fw-medium"><?= e($item['order_occurrences']) ?></span>
                                </div>
                                <div class="col-2 text-center">
                                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-2.5 py-1.5"><?= e($item['total_quantity']) ?></span>
                                </div>
                                <div class="col-3 text-end">
                                    <span class="fw-extrabold text-success font-display">&#8377;<?= e(number_format((float)$item['total_sales'], 2)) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown Section -->
    <div class="col-lg-5">
        <div class="dashboard-card card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-calendar-event text-success me-2"></i>Daily Sales Ledger</h2>
                </div>
                
                <?php if (empty($dailySales)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-calendar display-4 text-secondary-subtle"></i>
                        <p class="mt-3">No sales volume registered.</p>
                    </div>
                <?php else: ?>
                    <div class="visual-list" style="max-height: 380px; overflow-y: auto;">
                        <!-- Header Row -->
                        <div class="row py-2.5 px-3 mb-2 visual-list-header border-bottom bg-light rounded text-uppercase text-secondary fw-bold sticky-top" style="font-size: 0.75rem;">
                            <div class="col-5">Date</div>
                            <div class="col-3 text-center">Tickets</div>
                            <div class="col-4 text-end">Daily Sales</div>
                        </div>
                        <!-- List Items -->
                        <?php foreach ($dailySales as $day): ?>
                            <div class="row align-items-center py-3 px-3 visual-list-item">
                                <div class="col-5">
                                    <span class="fw-semibold text-dark"><?= e(date('d M Y', strtotime($day['order_date']))) ?></span>
                                </div>
                                <div class="col-3 text-center">
                                    <span class="badge bg-light text-muted fw-bold border px-2.5 py-1.5"><?= e($day['day_order_count']) ?></span>
                                </div>
                                <div class="col-4 text-end">
                                    <span class="fw-extrabold text-success font-display">&#8377;<?= e(number_format((float)$day['day_revenue'], 2)) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
