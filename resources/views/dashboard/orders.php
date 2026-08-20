<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Kitchen Panel</span>
            <h1 class="h2 mb-1">Live Kitchen Queue</h1>
            <p class="text-secondary mb-0">Monitor and update customer orders, chef prep timers, and table service status in real time.</p>
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

<!-- Tabs for Active vs Completed/Cancelled Archive -->
<ul class="nav nav-tabs mb-4 gap-2 border-bottom-0" id="orderTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 fw-semibold" id="active-orders-tab" data-bs-toggle="tab" data-bs-target="#active-orders" type="button" role="tab" aria-controls="active-orders" aria-selected="true">
            <i class="bi bi-fire text-success me-1.5 animate-pulse"></i>Active Queue
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 fw-semibold" id="archive-orders-tab" data-bs-toggle="tab" data-bs-target="#archive-orders" type="button" role="tab" aria-controls="archive-orders" aria-selected="false">
            <i class="bi bi-archive me-1.5"></i>Order Archive
        </button>
    </li>
</ul>

<div class="tab-content" id="orderTabsContent">
    <!-- Active Orders Tab -->
    <div class="tab-pane fade show active" id="active-orders" role="tabpanel" aria-labelledby="active-orders-tab">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <?php 
                    $activeOrders = array_filter($orders, function($o) {
                        return !in_array($o['status'], ['completed', 'cancelled'], true);
                    });
                ?>
                
                <?php if (empty($activeOrders)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-cup-hot display-4 text-secondary-subtle"></i>
                        <p class="mt-3 fs-5">No active orders right now.</p>
                        <p class="small">Scan tables and submit menu requests to see tickets pop up here.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($activeOrders as $o): ?>
                            <div class="col-md-6 col-xl-4">
                                <article class="grid-item-card h-100 p-4 bg-white d-flex flex-column justify-content-between" style="border-top: 4px solid <?= $o['status'] === 'pending' ? '#6c757d' : ($o['status'] === 'accepted' ? '#0dcaf0' : ($o['status'] === 'preparing' ? '#ffc107' : ($o['status'] === 'ready' ? '#198754' : '#0d6efd'))) ?>;">
                                    <div>
                                        <!-- Header: Order # & Table -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h3 class="h6 text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Ticket</h3>
                                                <span class="fw-bold text-dark font-display fs-5"><?= e($o['order_number']) ?></span>
                                            </div>
                                            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 fs-7"><i class="bi bi-geo-alt me-1"></i><?= e($o['table_name']) ?></span>
                                        </div>

                                        <!-- Ordered Time & Status -->
                                        <div class="d-flex justify-content-between align-items-center mb-3 text-muted small pb-2 border-bottom">
                                            <span><i class="bi bi-clock me-1"></i><?= e(date('h:i A', strtotime($o['created_at']))) ?></span>
                                            <?php
                                                $statusBadges = [
                                                    'pending' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                                    'accepted' => 'bg-info-subtle text-info border border-info-subtle',
                                                    'preparing' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                                    'ready' => 'bg-success-subtle text-success border border-success-subtle',
                                                    'served' => 'bg-primary-subtle text-primary border border-primary-subtle'
                                                ];
                                                $badgeStyle = $statusBadges[$o['status']] ?? 'bg-secondary text-secondary';
                                            ?>
                                            <span class="badge px-2.5 py-1.5 <?= $badgeStyle ?> text-uppercase" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em;">
                                                <?= e($o['status']) ?>
                                            </span>
                                        </div>

                                        <!-- Items List -->
                                        <div class="mb-3">
                                            <h4 class="h6 text-secondary fw-semibold mb-2" style="font-size: 0.75rem;">Items ordered:</h4>
                                            <div class="fw-bold text-dark fs-6" style="line-height: 1.5; white-space: pre-line;">
                                                <?= e(str_replace(', ', "\n• ", "• " . $o['items'])) ?>
                                            </div>
                                        </div>

                                        <!-- Customer Notes -->
                                        <?php if (!empty($o['customer_note'])): ?>
                                            <div class="p-3 border border-warning-subtle bg-warning bg-opacity-10 rounded-3 text-dark small mb-4">
                                                <div class="fw-bold text-warning-emphasis mb-1"><i class="bi bi-chat-left-text me-1"></i>Note from Table:</div>
                                                "<?= e($o['customer_note']) ?>"
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Actions & Amount -->
                                    <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted d-block small" style="font-size: 0.65rem;">Total Amount:</span>
                                            <span class="fw-bold text-dark">&#8377;<?= e(number_format((float)$o['total_amount'], 2)) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <!-- Pipeline Form Transitions -->
                                            <form method="post" action="<?= e(url('/dashboard/orders/update-status')) ?>" class="d-inline-flex">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="order_id" value="<?= e($o['id']) ?>">
                                                
                                                <?php if ($o['status'] === 'pending'): ?>
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button class="btn btn-sm btn-success d-flex align-items-center gap-1 px-3 py-1.5" type="submit">
                                                        <i class="bi bi-check-circle"></i> Accept
                                                    </button>
                                                <?php elseif ($o['status'] === 'accepted'): ?>
                                                    <input type="hidden" name="status" value="preparing">
                                                    <button class="btn btn-sm btn-warning d-flex align-items-center gap-1 px-3 py-1.5 text-dark" type="submit">
                                                        <i class="bi bi-fire"></i> Start Prep
                                                    </button>
                                                <?php elseif ($o['status'] === 'preparing'): ?>
                                                    <input type="hidden" name="status" value="ready">
                                                    <button class="btn btn-sm btn-success d-flex align-items-center gap-1 px-3 py-1.5" type="submit">
                                                        <i class="bi bi-bell-fill"></i> Ready
                                                    </button>
                                                <?php elseif ($o['status'] === 'ready'): ?>
                                                    <input type="hidden" name="status" value="served">
                                                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 py-1.5" type="submit">
                                                        <i class="bi bi-check2-all"></i> Serve
                                                    </button>
                                                <?php elseif ($o['status'] === 'served'): ?>
                                                    <input type="hidden" name="status" value="completed">
                                                    <button class="btn btn-sm btn-success d-flex align-items-center gap-1 px-3 py-1.5" type="submit">
                                                        <i class="bi bi-cash-stack"></i> Complete
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                            
                                            <!-- Cancel button for unfulfilled tickets -->
                                            <?php if (in_array($o['status'], ['pending', 'accepted', 'preparing'], true)): ?>
                                                <form method="post" action="<?= e(url('/dashboard/orders/update-status')) ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="order_id" value="<?= e($o['id']) ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button class="btn btn-sm btn-outline-danger px-2.5 py-1.5" type="submit" title="Cancel Ticket">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Order Archive Tab -->
    <div class="tab-pane fade" id="archive-orders" role="tabpanel" aria-labelledby="archive-orders-tab">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <?php 
                    $archivedOrders = array_filter($orders, function($o) {
                        return in_array($o['status'], ['completed', 'cancelled'], true);
                    });
                ?>
                
                <?php if (empty($archivedOrders)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-archive display-4 text-secondary-subtle"></i>
                        <p class="mt-3 fs-5">Archive is empty.</p>
                        <p class="small">Completed orders will end up here.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket / Seating</th>
                                    <th>Items Ordered</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($archivedOrders as $o): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block"><?= e($o['order_number']) ?></span>
                                            <span class="badge bg-secondary-subtle text-secondary fw-bold mt-1 px-2.5 py-1.5"><i class="bi bi-geo-alt me-1"></i><?= e($o['table_name']) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-dark small d-block" style="max-width: 300px;"><?= e($o['items']) ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">&#8377;<?= e(number_format((float)$o['total_amount'], 2)) ?></span>
                                        </td>
                                        <td>
                                            <span class="small text-muted"><?= e(date('d M Y, h:i A', strtotime($o['created_at']))) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge px-2.5 py-1.5 <?= $o['status'] === 'completed' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' ?> text-uppercase" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em;">
                                                <?= e($o['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php foreach ($archivedOrders as $o) {} ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Auto refresh active queue every 10 seconds -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = document.getElementById('active-orders-tab');
    
    // Set timer to reload only when viewing Active Queue tab
    const autoReloader = setInterval(function() {
        if (activeTab.classList.contains('active')) {
            window.location.reload();
        }
    }, 10000);
});
</script>
