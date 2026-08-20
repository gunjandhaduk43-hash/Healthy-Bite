<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

<div class="container py-5">
    <div class="row">
        <div class="col-lg-7 mx-auto">
            <!-- Error/Success Alerts -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 shadow-sm rounded-3 mb-3 animate-pulse" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div><?= e($error) ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 shadow-sm rounded-3 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div><?= e($success) ?></div>
                </div>
            <?php endif; ?>

            <!-- Header status card -->
            <div class="card shadow-lg border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-success text-white py-4 text-center">
                    <span class="d-inline-flex align-items-center justify-content-center bg-white text-success rounded-circle mb-2" style="width: 3.5rem; height: 3.5rem; line-height: 3.5rem; font-size: 1.4rem;">
                        <i class="bi bi-clock-history animate-pulse"></i>
                    </span>
                    <h1 class="h3 fw-bold display-font mb-1"><?= e($restaurantName) ?></h1>
                    <p class="mb-0 text-white-50 small">Order Status Tracker</p>
                </div>
                <div class="card-body p-4 p-sm-5 text-center">
                    <p class="text-secondary small mb-1">ORDER NUMBER</p>
                    <h2 class="h4 fw-bold text-dark mb-4" style="letter-spacing: 0.05em;"><?= e($order['order_number']) ?></h2>
                    
                    <!-- Visual Progress Stepper -->
                    <?php 
                        $status = $order['status'];
                        $isCancelled = ($status === 'cancelled');
                        
                        // Map status levels to stepper active index
                        $steps = ['pending' => 1, 'accepted' => 2, 'preparing' => 3, 'ready' => 4, 'served' => 5, 'completed' => 5];
                        $currentStep = $steps[$status] ?? 1;
                    ?>

                    <?php if ($isCancelled): ?>
                        <div class="alert alert-danger border-danger d-flex align-items-center gap-3 p-4 rounded-4 mb-4 text-start" role="alert">
                            <i class="bi bi-x-circle-fill display-6 text-danger"></i>
                            <div>
                                <h4 class="alert-heading mb-1 fw-bold">Order Cancelled</h4>
                                <p class="mb-0 small text-secondary">This order has been cancelled by the kitchen staff. Please scan the QR code to order again or contact staff.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="stepper-wrapper mb-5">
                            <div class="stepper-item <?= $currentStep >= 1 ? 'completed' : '' ?> <?= $status === 'pending' ? 'active' : '' ?>">
                                <div class="step-counter"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="step-name">Received</div>
                            </div>
                            <div class="stepper-item <?= $currentStep >= 3 ? 'completed' : ($currentStep === 2 ? 'active' : '') ?>">
                                <div class="step-counter"><i class="bi bi-fire"></i></div>
                                <div class="step-name">Cooking</div>
                            </div>
                            <div class="stepper-item <?= $currentStep >= 4 ? 'completed' : '' ?>">
                                <div class="step-counter"><i class="bi bi-bell"></i></div>
                                <div class="step-name">Ready</div>
                            </div>
                            <div class="stepper-item <?= $currentStep >= 5 ? 'completed' : '' ?>">
                                <div class="step-counter"><i class="bi bi-check2-all"></i></div>
                                <div class="step-name">Served</div>
                            </div>
                        </div>

                        <!-- Status Description Text -->
                        <div class="py-3 border bg-light rounded-4 mb-4">
                            <?php if ($status === 'pending'): ?>
                                <span class="fw-bold text-success"><i class="bi bi-hourglass-split"></i> Order Received</span>
                                <p class="text-secondary small mb-0 mt-1">Waiting for the kitchen to accept your order...</p>
                            <?php elseif ($status === 'accepted' || $status === 'preparing'): ?>
                                <span class="fw-bold text-success animate-pulse"><i class="bi bi-fire"></i> Chef is Cooking</span>
                                <p class="text-secondary small mb-0 mt-1">Your healthy food is being freshly prepared with ingredients.</p>
                            <?php elseif ($status === 'ready'): ?>
                                <span class="fw-bold text-warning animate-bounce"><i class="bi bi-bell-fill text-warning"></i> Ready to Serve!</span>
                                <p class="text-secondary small mb-0 mt-1">Your order is ready. A waiter is bringing it to <strong><?= e($tableName) ?></strong> shortly.</p>
                            <?php elseif ($status === 'served' || $status === 'completed'): ?>
                                <span class="fw-bold text-success"><i class="bi bi-check-circle-fill"></i> Served & Enjoy!</span>
                                <p class="text-secondary small mb-0 mt-1">Your order has been served. Enjoy your healthy bite!</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-start border-top pt-4">
                        <h3 class="h6 fw-bold mb-3 text-secondary text-uppercase" style="letter-spacing: 0.05em;">Order Items</h3>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr class="text-secondary small border-bottom">
                                        <th class="py-2 border-0">Item</th>
                                        <th class="py-2 border-0 text-center" style="width: 80px;">Qty</th>
                                        <th class="py-2 border-0 text-end" style="width: 100px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $line): ?>
                                        <tr>
                                            <td class="py-2.5 border-0 fw-semibold text-dark"><?= e($line['item_name']) ?></td>
                                            <td class="py-2.5 border-0 text-center text-secondary"><?= e($line['quantity']) ?></td>
                                            <td class="py-2.5 border-0 text-end text-dark fw-bold">&#8377;<?= e(number_format((float)$line['line_total'], 2)) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="border-top fw-bold text-dark">
                                        <td colspan="2" class="py-3 border-0">Total Amount</td>
                                        <td class="py-3 border-0 text-end fs-6">&#8377;<?= e(number_format((float)$order['total_amount'], 2)) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($order['customer_note'])): ?>
                            <div class="mt-4 p-3 bg-light rounded-3 border-start border-3 border-success">
                                <span class="small fw-bold text-secondary d-block"><i class="bi bi-chat-left-dots"></i> Chef Note:</span>
                                <span class="small text-dark">"<?= e($order['customer_note']) ?>"</span>
                            </div>
                        <?php endif; ?>

                        <!-- 1. Payment Simulation Section -->
                        <?php if (empty($payment)): ?>
                            <div class="bg-light border border-warning p-4 rounded-4 text-center my-4">
                                <span class="fw-bold text-warning d-block mb-2"><i class="bi bi-wallet2"></i> Bill Payment Pending</span>
                                <p class="small text-secondary mb-3">Please pay your bill of <strong>&#8377;<?= e(number_format((float)$order['total_amount'], 2)) ?></strong> to settle the order.</p>
                                
                                <form method="post" action="<?= e(url('/menu/payment/simulate')) ?>" class="d-inline-block w-100" style="max-width: 360px;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="token" value="<?= e($token) ?>">
                                    <input type="hidden" name="order_id" value="<?= e($order['id']) ?>">
                                    <div class="input-group">
                                        <select class="form-select" name="method" required>
                                            <option value="upi">Simulate UPI Pay</option>
                                            <option value="card">Simulate Card Pay</option>
                                            <option value="cash">Simulate Cash Pay</option>
                                        </select>
                                        <button class="btn btn-success" type="submit">Pay Now</button>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="bg-success-subtle border border-success p-3 rounded-4 text-center my-4">
                                <span class="fw-bold text-success d-block mb-1"><i class="bi bi-check-circle-fill"></i> Bill Settled & Paid</span>
                                <p class="small text-secondary mb-0">Amount: <strong>&#8377;<?= e(number_format((float)$payment['amount'], 2)) ?></strong> via <?= e(strtoupper($payment['method'])) ?> at <?= e(date('M d, H:i', strtotime($payment['created_at']))) ?>.</p>
                            </div>
                        <?php endif; ?>

                        <!-- 2. Star Review & Comment Form (Show once order is preparing, ready, served, or completed) -->
                        <?php if ($status === 'served' || $status === 'completed'): ?>
                            <div class="card border-0 shadow-sm rounded-4 my-4 p-4 text-start bg-light border border-success-subtle">
                                <h4 class="h5 fw-bold text-success mb-3"><i class="bi bi-chat-left-heart"></i> Share your experience</h4>
                                <form method="post" action="<?= e(url('/menu/review/submit')) ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="token" value="<?= e($token) ?>">
                                    <input type="hidden" name="order_id" value="<?= e($order['id']) ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-secondary">What would you like to review?</label>
                                        <select name="food_item_id" class="form-select">
                                            <option value="">Rate General Restaurant</option>
                                            <?php foreach ($items as $item): ?>
                                                <option value="<?= e($item['food_item_id']) ?>">Rate: <?= e($item['item_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-secondary d-block">Rating</label>
                                        <div class="btn-group w-100" role="group">
                                            <?php for ($star = 1; $star <= 5; $star++): ?>
                                                <input type="radio" class="btn-check" name="rating" id="star<?= $star ?>" value="<?= $star ?>" <?= $star === 5 ? 'checked' : '' ?>>
                                                <label class="btn btn-outline-warning" for="star<?= $star ?>"><?= $star ?> <i class="bi bi-star-fill"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-secondary" for="comment">Your Comment</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Tell us what you liked or how we can improve..." required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 py-2.5">Submit Review</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Footer actions -->
            <div class="text-center no-print">
                <a href="<?= e(url('/menu?token=' . $token)) ?>" class="btn btn-outline-success d-inline-flex align-items-center gap-2 px-4 py-2">
                    <i class="bi bi-arrow-left"></i> Return to Menu
                </a>
            </div>
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

/* Stepper styles */
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    position: relative;
}
.stepper-wrapper::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 20px;
    right: 20px;
    height: 4px;
    background-color: #edf2f7;
    z-index: 1;
}
.stepper-item {
    display: flex;
    flex-column: column;
    align-items: center;
    flex: 1;
    z-index: 2;
    position: relative;
}
.stepper-item .step-counter {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #edf2f7;
    border: 3px solid #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    color: #a0aec0;
    transition: all 0.4s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.03);
}
.stepper-item .step-name {
    margin-top: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #a0aec0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-align: center;
}
.stepper-item.completed .step-counter {
    background-color: var(--bs-success);
    color: white;
}
.stepper-item.completed .step-name {
    color: var(--bs-success);
    font-weight: 700;
}
.stepper-item.active .step-counter {
    background-color: var(--bs-success);
    color: white;
    box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.25);
}
.stepper-item.active .step-name {
    color: var(--bs-success);
    font-weight: 700;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}

.animate-bounce {
    animation: bounce 1s infinite;
}
@keyframes bounce {
    0%, 100% { transform: translateY(-5%); animation-timing-function: cubic-bezier(0.8,0,1,1); }
    50% { transform: none; animation-timing-function: cubic-bezier(0,0,0.2,1); }
}
</style>

<!-- Polling script -->
<?php if (!$isCancelled && $status !== 'completed' && $status !== 'served'): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderId = <?= $order['id'] ?>;
        const token = "<?= e($token) ?>";
        
        // Poll status every 5 seconds
        const poller = setInterval(function() {
            fetch(`/menu/order/poll?id=${orderId}&token=${token}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const currentStatus = "<?= e($status) ?>";
                        if (data.status !== currentStatus) {
                            clearInterval(poller);
                            window.location.reload();
                        }
                    }
                })
                .catch(err => console.error("Polling error: ", err));
        }, 5000);
    });
    </script>
<?php endif; ?>
