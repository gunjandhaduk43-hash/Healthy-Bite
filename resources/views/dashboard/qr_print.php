<?php
// Build the fully-qualified customer menu URL
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$menuUrl = $scheme . '://' . $host . url('/menu?token=' . $token);
$qrCodeApiUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($menuUrl);
?>

<div class="container text-center py-4 no-print">
    <div class="d-inline-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-printer"></i> Print Table Standee
        </button>
        <a href="<?= e(url('/dashboard/tables')) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Return to Tables
        </a>
    </div>
</div>

<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light-subtle p-3">
    <div class="card shadow-lg print-card border-success" style="max-width: 420px; width: 100%; border: 3px solid var(--bs-success); border-radius: 1.5rem;">
        <div class="card-body p-4 p-sm-5 text-center">
            
            <!-- Logo Brand header -->
            <div class="mb-4">
                <span class="d-inline-block bg-success text-white fw-bold rounded-circle mb-2" style="width: 3.5rem; height: 3.5rem; line-height: 3.5rem; font-size: 1.4rem; letter-spacing: -0.02em;">HB</span>
                <h1 class="h3 fw-bold display-font text-success mb-1"><?= e($restaurantName) ?></h1>
                <p class="text-uppercase text-muted fw-bold small mb-0" style="letter-spacing: 0.1em; font-size: 0.75rem;">Digital QR Menu</p>
            </div>
            
            <div class="py-2 border-top border-bottom border-secondary-subtle my-3">
                <p class="text-secondary small mb-1">WELCOME TO TABLE</p>
                <h2 class="h1 fw-extrabold display-font mb-0 text-dark" style="font-size: 2.8rem; letter-spacing: -0.03em;"><?= e($tableName) ?></h2>
            </div>
            
            <!-- QR code image -->
            <div class="my-4 p-3 bg-white d-inline-block border border-2 border-success-subtle rounded-4 shadow-sm">
                <img src="<?= e($qrCodeApiUrl) ?>" alt="QR Menu Code" class="img-fluid" style="width: 250px; height: 250px;">
            </div>
            
            <!-- Scanning Instructions -->
            <div>
                <h3 class="h6 text-uppercase fw-bold text-success mb-3" style="letter-spacing: 0.05em;"><i class="bi bi-phone-vibrate me-1.5 animate-pulse text-success"></i>Scan to Order</h3>
                
                <ol class="text-start text-secondary small px-3 mb-0" style="font-size: 0.85rem; line-height: 1.5;">
                    <li class="mb-1.5">Open your smartphone camera or any QR scanner application.</li>
                    <li class="mb-1.5">Scan the QR code to load the interactive digital healthy menu.</li>
                    <li>Browse calorie metrics, customize your meal, and check out directly.</li>
                </ol>
            </div>
            
        </div>
    </div>
</div>

<style>
/* Print-specific layout overrides */
@media print {
    .no-print {
        display: none !important;
    }
    body, .min-vh-100 {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        height: auto !important;
        min-height: auto !important;
    }
    .print-card {
        box-shadow: none !important;
        border: none !important;
        margin: 0 auto !important;
        padding: 0 !important;
    }
}
</style>
