<div class="row align-items-center mb-4 g-3">
    <div class="col-md-8">
        <div class="page-title-section">
            <span class="page-pretitle">Dining Area</span>
            <h1 class="h2 mb-1">Tables & QR Setup</h1>
            <p class="text-secondary mb-0">Manage physical seating tables, generate secure tokens, and download QR codes for tables.</p>
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

<?php if (!empty($new_qr_token)): ?>
    <div class="alert alert-success border-success p-4 mb-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center gap-3 mb-3">
            <i class="bi bi-qr-code-scan display-6 text-success animate-pulse"></i>
            <div>
                <h4 class="alert-heading mb-1 fw-bold">Secure QR Code Generated!</h4>
                <p class="mb-0 text-secondary">The token for <strong><?= e($new_qr_table) ?></strong> is active. You can print the table QR menu flyer right now.</p>
            </div>
        </div>
        <hr class="my-3 opacity-25">
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= e(url('/dashboard/tables/print-qr?token=' . urlencode($new_qr_token) . '&table=' . urlencode($new_qr_table))) ?>" target="_blank" class="btn btn-success d-inline-flex align-items-center gap-2">
                <i class="bi bi-printer-fill"></i> Open Printable Flyer
            </a>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Add Table Form -->
    <div class="col-lg-4">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-plus-circle text-success me-2"></i>Add Dining Table</h2>
                </div>
                
                <form method="post" action="<?= e(url('/dashboard/tables/create')) ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold" for="table_name">Table Name / Number</label>
                        <input class="form-control <?= !empty($errors['table_name']) ? 'is-invalid' : '' ?>" 
                               id="table_name" name="table_name" 
                               value="<?= e($old['table_name'] ?? '') ?>" 
                               placeholder="e.g. Table 1, Window Side" required>
                        <?php if (!empty($errors['table_name'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['table_name']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold" for="capacity">Seating Capacity</label>
                        <input class="form-control <?= !empty($errors['capacity']) ? 'is-invalid' : '' ?>" 
                               id="capacity" name="capacity" type="number" 
                               value="<?= e($old['capacity'] ?? '2') ?>" 
                               placeholder="2" required>
                        <?php if (!empty($errors['capacity'])): ?>
                            <div class="invalid-feedback d-block"><?= e($errors['capacity']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                        <i class="bi bi-check-lg"></i> Register Table
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table Grid / List -->
    <div class="col-lg-8">
        <div class="dashboard-card card border-0">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                    <h2 class="h5 mb-0"><i class="bi bi-grid-3x3 text-success me-2"></i>Dining Hall Seating</h2>
                </div>
                
                <?php if (empty($tables)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-griddisplay-4 text-secondary-subtle"></i>
                        <p class="mt-3 fs-5">No tables configured yet.</p>
                        <p class="small">Add tables on the left to start generating customer QR session menus.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($tables as $t): ?>
                            <div class="col-md-6">
                                <article class="grid-item-card h-100 p-4 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="h5 mb-1 text-dark fw-bold"><?= e($t['table_number'] ?? $t['table_name']) ?></h3>
                                            <div class="d-flex align-items-center gap-1.5 mt-2">
                                                <div class="text-muted small me-2"><i class="bi bi-people me-1"></i>Seats:</div>
                                                <div class="d-flex gap-0.5">
                                                    <?php for ($i = 0; $i < min(6, (int)$t['capacity']); $i++): ?>
                                                        <i class="bi bi-person-fill text-success" style="font-size: 0.85rem;" title="Seat"></i>
                                                    <?php endfor; ?>
                                                    <?php if ((int)$t['capacity'] > 6): ?>
                                                        <span class="text-success small fw-bold">+<?= (int)$t['capacity'] - 6 ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="post" action="<?= e(url('/dashboard/tables/status')) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="table_id" value="<?= e($t['id']) ?>">
                                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm border-0 bg-light fw-bold text-uppercase" style="font-size: 0.7rem; cursor: pointer;">
                                                <option value="available" <?= $t['status'] === 'available' ? 'selected' : '' ?>>🟢 Available</option>
                                                <option value="occupied" <?= $t['status'] === 'occupied' ? 'selected' : '' ?>>🔴 Occupied</option>
                                                <option value="cleaning" <?= $t['status'] === 'cleaning' ? 'selected' : '' ?>>🟡 Cleaning</option>
                                                <option value="out_of_service" <?= $t['status'] === 'out_of_service' ? 'selected' : '' ?>>⚪ Out of Service</option>
                                            </select>
                                        </form>
                                    </div>
                                    
                                    <div class="border-top pt-3 mt-4">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <span class="small text-muted d-flex align-items-center gap-1.5">
                                                <i class="bi bi-qr-code-scan text-secondary"></i> QR: 
                                                <?= (int)$t['token_count'] > 0 ? '<span class="badge bg-success-subtle text-success small">Active</span>' : '<span class="badge bg-light text-muted small">None</span>' ?>
                                            </span>
                                            
                                            <form method="post" action="<?= e(url('/dashboard/tables/issue-qr')) ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="table_id" value="<?= e($t['id']) ?>">
                                                <input type="hidden" name="table_name" value="<?= e($t['table_number'] ?? $t['table_name']) ?>">
                                                <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" type="submit">
                                                    <i class="bi bi-qr-code"></i> 
                                                    <?= (int)$t['token_count'] > 0 ? 'Regenerate' : 'Generate' ?>
                                                </button>
                                            </form>
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
</div>
