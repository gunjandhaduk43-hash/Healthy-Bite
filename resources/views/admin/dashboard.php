<div class="gradient-banner text-white p-4 p-md-5 mb-4 shadow-lg d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%) !important;">
    <div>
        <span class="text-indigo-light text-uppercase fw-bold small tracking-wider" style="color: #a5b4fc; font-size: 0.75rem; letter-spacing: 0.05em;">Platform Oversight</span>
        <h1 class="h2 mb-1 text-white font-display">Super Admin Portal</h1>
        <p class="mb-0 text-white-50 small">Manage SaaS platform restaurant tenants, approval statuses, and multi-restaurant sales analytics.</p>
    </div>
    <div>
        <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-10 px-3 py-2 rounded-pill">
            <i class="bi bi-shield-check text-warning fs-5"></i>
            <span class="text-white small fw-semibold">Platform Super Admin</span>
        </div>
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

<!-- System Metric Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <article class="dashboard-card card h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Total Restaurants</p>
                    <p class="stat-value mb-0 text-dark"><?= e($restaurantsCount ?? 0) ?></p>
                    <small class="text-muted"><?= e($approvedCount ?? 0) ?> Approved</small>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-buildings"></i>
                </div>
            </div>
        </article>
    </div>

    <div class="col-md-3">
        <article class="dashboard-card card h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Pending Approval</p>
                    <p class="stat-value mb-0 text-warning"><?= e($pendingCount ?? 0) ?></p>
                    <small class="text-muted">Awaiting review</small>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </article>
    </div>

    <div class="col-md-3">
        <article class="dashboard-card card h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">System Orders</p>
                    <p class="stat-value mb-0 text-info"><?= e($ordersCount ?? 0) ?></p>
                    <small class="text-muted">Across all tenants</small>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </article>
    </div>

    <div class="col-md-3">
        <article class="dashboard-card card h-100 border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Gross GMV Revenue</p>
                    <p class="stat-value mb-0 text-success">&#8377;<?= e(number_format((float)($totalSales ?? 0), 2)) ?></p>
                    <small class="text-muted">System sales total</small>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </article>
    </div>
</div>

<!-- Tenants List Table -->
<section class="dashboard-card card border-0">
    <div class="card-body p-4">
        <div class="border-bottom pb-3 mb-4">
            <h2 class="h5 mb-1"><i class="bi bi-shop text-indigo me-2"></i>Platform Restaurant Tenants</h2>
            <p class="text-secondary small mb-0">Approve, suspend, or audit restaurant owner registrations on Healthy Bite.</p>
        </div>

        <?php if (empty($restaurants)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-buildings display-4 text-secondary-subtle"></i>
                <p class="mt-3">No restaurants registered yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-secondary small">
                        <tr>
                            <th class="py-3">Restaurant</th>
                            <th class="py-3">Owner Details</th>
                            <th class="py-3">Cuisine &amp; Location</th>
                            <th class="py-3 text-center">Orders</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($restaurants as $r): ?>
                            <tr>
                                <td class="py-3">
                                    <div class="fw-bold text-dark font-display fs-6"><?= e($r['name']) ?></div>
                                    <div class="text-muted small">ID: #<?= e($r['id']) ?> &bull; Created <?= e(date('M d, Y', strtotime($r['created_at']))) ?></div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark"><?= e($r['owner_name'] ?? 'N/A') ?></div>
                                    <div class="text-muted small"><i class="bi bi-envelope me-1"></i><?= e($r['owner_email'] ?? $r['email']) ?></div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border me-1"><?= e($r['cuisine_type'] ?? 'General') ?></span>
                                    <div class="text-muted small mt-1"><?= e($r['city'] ?? '') ?><?= !empty($r['state']) ? ', ' . e($r['state']) : '' ?></div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-info-subtle text-info fw-bold fs-7 px-3 py-1.5"><?= e($r['total_orders'] ?? 0) ?> orders</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge-hb <?= $r['approval_status'] === 'approved' ? 'status-approved' : ($r['approval_status'] === 'suspended' ? 'status-suspended' : 'status-pending') ?>">
                                        <span class="badge-pulse"></span>
                                        <?= e(ucfirst($r['approval_status'])) ?>
                                    </span>
                                </td>
                                <td class="py-3 text-end">
                                    <form method="post" action="<?= e(url('/admin/restaurants/status')) ?>" class="d-inline-flex gap-1">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="restaurant_id" value="<?= e($r['id']) ?>">
                                        <?php if ($r['approval_status'] !== 'approved'): ?>
                                            <button class="btn btn-sm btn-success d-inline-flex align-items-center gap-1" name="approval_status" value="approved" type="submit">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($r['approval_status'] !== 'suspended'): ?>
                                            <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" name="approval_status" value="suspended" type="submit">
                                                <i class="bi bi-slash-circle"></i> Suspend
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
