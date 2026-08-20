<?php $demoToken = $demoToken ?? ''; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<div class="landing-page bg-dark text-white min-vh-100 d-flex flex-column justify-content-between position-relative overflow-hidden" style="background: #04120e !important;">

    <!-- Background glowing ambient light orbs -->
    <div class="position-absolute top-0 start-50 translate-middle-x rounded-circle opacity-25 pointer-events-none" style="width: 800px; height: 800px; background: radial-gradient(circle, #10b981 0%, transparent 65%); filter: blur(100px);"></div>
    <div class="position-absolute top-50 start-0 translate-middle-y rounded-circle opacity-15 pointer-events-none" style="width: 500px; height: 500px; background: radial-gradient(circle, #84cc16 0%, transparent 70%); filter: blur(90px);"></div>

    <!-- Navigation Header -->
    <header class="navbar navbar-expand-md navbar-dark py-3 glass-navbar sticky-top position-relative z-3">
        <div class="container px-3 px-sm-4">
            <a class="navbar-brand d-flex align-items-center gap-2.5 fw-bold text-white text-decoration-none font-display fs-4 me-auto" href="<?= e(url('/')) ?>">
                <span class="brand-mark shadow-sm">HB</span>
                <span>Healthy<span class="gradient-text-emerald">Bite</span></span>
            </a>

            <div class="d-flex align-items-center gap-2 gap-sm-3 ms-auto">
                <a href="<?= e(url('/login')) ?>" class="btn btn-outline-light btn-sm px-3.5 py-2 fw-semibold rounded-pill" style="font-size: 0.85rem; border-color: rgba(255,255,255,0.2);">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                </a>
                <a href="<?= e(url('/register')) ?>" class="btn glowing-btn-emerald btn-sm px-4 py-2 fw-semibold rounded-pill shadow-sm" style="font-size: 0.85rem;">
                    <i class="bi bi-rocket-takeoff-fill me-1"></i> Get Started
                </a>
            </div>
        </div>
    </header>

    <!-- Main Hero Section -->
    <main class="container py-4 py-lg-5 position-relative z-2 px-3 px-sm-4">
        
        <!-- Hero Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-10 col-xl-9">
                <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-15 mb-4 shadow-sm">
                    <span class="badge bg-success rounded-pill px-2.5 py-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;"><span class="badge-pulse me-1"></span> Digital Menu SaaS</span>
                    <span class="text-white-50 small fw-medium">Next-Gen Restaurant Tech</span>
                </div>
                
                <h1 class="hero-title text-white font-display mb-4">
                    Contactless QR Menus &amp; <br class="d-none d-md-inline"><span class="gradient-text-emerald">Real-Time Kitchen Automation</span>
                </h1>
                
                <p class="hero-subtitle mb-4 mb-lg-5 mx-auto" style="max-width: 760px;">
                    Transform table dining with instant QR menu tokens, real-time kitchen order display tickets, calorie &amp; macro nutrition calculation, and live sales analytics.
                </p>

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3">
                    <?php if (!empty($demoToken)): ?>
                        <a href="<?= e(url('/menu?token=' . $demoToken)) ?>" class="btn glowing-btn-emerald btn-lg px-4.5 py-3 fw-bold rounded-pill shadow-lg d-inline-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto" style="font-size: 1rem;">
                            <i class="bi bi-qr-code-scan fs-5"></i> Try Live Customer Menu
                        </a>
                    <?php endif; ?>
                    <a href="<?= e(url('/register')) ?>" class="btn btn-outline-light btn-lg px-4.5 py-3 fw-semibold rounded-pill d-inline-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto" style="font-size: 1rem; border-color: rgba(255,255,255,0.25);">
                        <i class="bi bi-shop"></i> Register Your Restaurant
                    </a>
                </div>
            </div>
        </div>

        <!-- Interactive Live Browser Mockup Preview -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-11 col-xl-10">
                <div class="mockup-window">
                    <div class="mockup-header">
                        <div class="mockup-dot mockup-dot-red"></div>
                        <div class="mockup-dot mockup-dot-yellow"></div>
                        <div class="mockup-dot mockup-dot-green"></div>
                        <div class="ms-2 text-white-50 small font-monospace d-none d-sm-inline" style="font-size: 0.75rem;"><i class="bi bi-lock-fill me-1 text-success"></i> https://healthybite.app/dashboard</div>
                    </div>
                    <div class="p-3 p-md-4 text-start bg-dark bg-opacity-50">
                        <div class="row g-3 align-items-center">
                            <!-- Left: Live Stats -->
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10 mb-3">
                                    <div class="text-uppercase text-white-50 small fw-bold mb-1">Today's Revenue</div>
                                    <div class="h3 fw-bold text-success mb-0 font-display">&#8377;12,450.00</div>
                                    <small class="text-white-50"><i class="bi bi-graph-up-arrow me-1 text-success"></i>+18.4% from yesterday</small>
                                </div>
                                <div class="p-3 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10">
                                    <div class="text-uppercase text-white-50 small fw-bold mb-1">Active Seating Tables</div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="h4 fw-bold text-white mb-0 font-display">8 / 12 Occupied</div>
                                        <span class="badge bg-success rounded-pill px-2.5 py-1">66% Rate</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right: Live Kitchen Order Ticket Preview -->
                            <div class="col-md-8">
                                <div class="p-3.5 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-warning text-dark fw-bold text-uppercase px-2.5 py-1"><i class="bi bi-fire me-1"></i> Live Kitchen Order #HB-84FF</span>
                                        <span class="text-white-50 small"><i class="bi bi-clock me-1"></i> 2 mins ago</span>
                                    </div>
                                    <h2 class="h6 text-white fw-bold mb-1">Table 1 &bull; Customer: Jane Doe</h2>
                                    <p class="text-white-50 small mb-2">• 2x Superfood Protein Bowl (Extra Dressing)</p>
                                    <div class="d-flex align-items-center justify-content-between border-top border-white border-opacity-10 pt-2">
                                        <span class="text-success fw-bold font-display">&#8377;48.00 &bull; Paid via UPI</span>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle text-uppercase px-2 py-1" style="font-size: 0.65rem;">Cooking in Progress</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Accounts Quick Access Cards -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-11 col-xl-10">
                <div class="card bg-white text-dark rounded-4 shadow-lg border-0 p-2">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                            <div>
                                <span class="badge bg-success-subtle text-success fw-bold text-uppercase px-2.5 py-1" style="font-size: 0.65rem;">One-Click Access</span>
                                <h2 class="h5 mb-0 fw-bold font-display text-dark mt-1"><i class="bi bi-key-fill text-success me-2"></i>Instant Demo Accounts</h2>
                            </div>
                            <span class="brand-mark bg-success text-white" style="width: 2.2rem; height: 2.2rem; font-size: 0.9rem;">HB</span>
                        </div>

                        <p class="text-secondary small mb-4">Click any persona below to quickly test Healthy Bite without registering:</p>

                        <!-- Demo Personas Grid -->
                        <div class="row g-3">
                            <!-- 1. Restaurant Owner -->
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between hover-shadow transition-all">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1.5 mb-1">
                                            <i class="bi bi-person-badge text-success fs-5"></i> Owner Portal
                                        </div>
                                        <p class="text-muted small mb-2" style="font-size: 0.78rem;">Manage menu, pricing, QR tables &amp; staff.</p>
                                        <div class="font-monospace text-dark small bg-white p-1.5 rounded border text-truncate" style="font-size: 0.72rem;">owner@healthybite.com</div>
                                    </div>
                                    <a href="<?= e(url('/login')) ?>" class="btn btn-sm btn-success w-100 fw-semibold mt-3">Sign In as Owner</a>
                                </div>
                            </div>

                            <!-- 2. Super Admin -->
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between hover-shadow transition-all">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1.5 mb-1">
                                            <i class="bi bi-shield-lock text-primary fs-5"></i> Super Admin
                                        </div>
                                        <p class="text-muted small mb-2" style="font-size: 0.78rem;">Platform oversight &amp; tenant management.</p>
                                        <div class="font-monospace text-dark small bg-white p-1.5 rounded border text-truncate" style="font-size: 0.72rem;">admin@healthybite.com</div>
                                    </div>
                                    <a href="<?= e(url('/login')) ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold mt-3">Sign In as Admin</a>
                                </div>
                            </div>

                            <!-- 3. Kitchen Staff Cook -->
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between hover-shadow transition-all">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1.5 mb-1">
                                            <i class="bi bi-fire text-warning fs-5"></i> Kitchen Staff
                                        </div>
                                        <p class="text-muted small mb-2" style="font-size: 0.78rem;">Real-time order tickets &amp; status queue.</p>
                                        <div class="font-monospace text-dark small bg-white p-1.5 rounded border text-truncate" style="font-size: 0.72rem;">staff@healthybite.com</div>
                                    </div>
                                    <a href="<?= e(url('/login')) ?>" class="btn btn-sm btn-outline-warning text-dark w-100 fw-semibold mt-3">Sign In as Staff</a>
                                </div>
                            </div>

                            <!-- 4. Customer QR Menu -->
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 border bg-success bg-opacity-10 border-success border-opacity-25 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="fw-bold text-success-emphasis d-flex align-items-center gap-1.5 mb-1">
                                            <i class="bi bi-qr-code-scan text-success fs-5"></i> Customer Menu
                                        </div>
                                        <p class="text-success-emphasis small mb-2" style="font-size: 0.78rem;">Direct table QR menu seating session.</p>
                                        <div class="font-monospace text-success small bg-white p-1.5 rounded border text-truncate" style="font-size: 0.72rem;">Table 1 &bull; Active Token</div>
                                    </div>
                                    <?php if (!empty($demoToken)): ?>
                                        <a href="<?= e(url('/menu?token=' . $demoToken)) ?>" class="btn btn-sm btn-success w-100 fw-semibold mt-3">Scan Table QR</a>
                                    <?php else: ?>
                                        <span class="btn btn-sm btn-secondary disabled w-100 mt-3">QR Menu</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- 4-Column Feature Highlights Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="glass-card-dark p-4 h-100 text-start">
                    <div class="brand-mark bg-success bg-opacity-25 text-success mb-3" style="width: 3rem; height: 3rem; font-size: 1.25rem;">
                        <i class="bi bi-qr-code"></i>
                    </div>
                    <h2 class="h5 text-white fw-bold mb-2">QR Seating Tokens</h2>
                    <p class="text-white-50 small mb-0">Cryptographically secure table menu access. Customers scan and order instantly without downloading apps.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="glass-card-dark p-4 h-100 text-start">
                    <div class="brand-mark bg-info bg-opacity-25 text-info mb-3" style="width: 3rem; height: 3rem; font-size: 1.25rem;">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <h2 class="h5 text-white fw-bold mb-2">Nutrition Macros</h2>
                    <p class="text-white-50 small mb-0">Display calories, protein, carbs, fat, ingredients, and allergen warnings directly on food item cards.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="glass-card-dark p-4 h-100 text-start">
                    <div class="brand-mark bg-warning bg-opacity-25 text-warning mb-3" style="width: 3rem; height: 3rem; font-size: 1.25rem;">
                        <i class="bi bi-display-fill"></i>
                    </div>
                    <h2 class="h5 text-white fw-bold mb-2">Kitchen Display (KDS)</h2>
                    <p class="text-white-50 small mb-0">Real-time status progression from cooking to table serving with audio notifications and chef timers.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="glass-card-dark p-4 h-100 text-start">
                    <div class="brand-mark bg-primary bg-opacity-25 text-primary mb-3" style="width: 3rem; height: 3rem; font-size: 1.25rem;">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <h2 class="h5 text-white fw-bold mb-2">Revenue Analytics</h2>
                    <p class="text-white-50 small mb-0">Executive dashboards tracking total sales volume, popular food item rankings, and payment breakdowns.</p>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="py-4 border-top border-white border-opacity-10 text-center text-white-50 small position-relative z-2">
        <div class="container px-3">
            <p class="mb-0">&copy; 2026 Healthy Bite Digital Menu &amp; Food Ordering System. Built with Clean PHP MVC Architecture &amp; 16-Table Data Dictionary.</p>
        </div>
    </footer>

</div>
