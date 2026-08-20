<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Healthy Bite restaurant dashboard">
    <title><?= e($title ?? 'Healthy Bite') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Desktop Sidebar -->
        <aside class="sidebar">
            <div class="d-flex flex-column gap-4 w-100">
                <a class="d-flex align-items-center gap-2 text-decoration-none text-white px-2" href="<?= e(url('/dashboard')) ?>">
                    <span class="brand-mark">HB</span>
                    <span class="fs-4 fw-bold display-font">Healthy Bite</span>
                </a>
                
                <nav class="d-flex flex-column">
                    <?php
                        $uri = $_SERVER['REQUEST_URI'] ?? '';
                        $role = $user['role'] ?? 'owner';
                        $isDashboard = (strpos($uri, '/categories') === false && strpos($uri, '/menu') === false && strpos($uri, '/tables') === false && strpos($uri, '/orders') === false && strpos($uri, '/reports') === false && strpos($uri, '/staff') === false && strpos($uri, '/reviews') === false);
                        $isCategories = (strpos($uri, '/categories') !== false);
                        $isMenu = (strpos($uri, '/menu') !== false);
                        $isTables = (strpos($uri, '/tables') !== false);
                        $isOrders = (strpos($uri, '/orders') !== false);
                        $isReports = (strpos($uri, '/reports') !== false);
                        $isStaff = (strpos($uri, '/staff') !== false);
                        $isReviews = (strpos($uri, '/reviews') !== false);
                    ?>

                    <?php if ($role === 'superadmin'): ?>
                        <a class="sidebar-link active" href="<?= e(url('/admin/dashboard')) ?>">
                            <i class="bi bi-shield-check"></i>
                            <span>Admin Portal</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role === 'owner'): ?>
                        <a class="sidebar-link <?= $isDashboard ? 'active' : '' ?>" href="<?= e(url('/dashboard')) ?>">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                        <a class="sidebar-link <?= $isCategories ? 'active' : '' ?>" href="<?= e(url('/dashboard/categories')) ?>">
                            <i class="bi bi-tags-fill"></i>
                            <span>Categories</span>
                        </a>
                        <a class="sidebar-link <?= $isMenu ? 'active' : '' ?>" href="<?= e(url('/dashboard/menu')) ?>">
                            <i class="bi bi-egg-fried"></i>
                            <span>Food Menu</span>
                        </a>
                        <a class="sidebar-link <?= $isTables ? 'active' : '' ?>" href="<?= e(url('/dashboard/tables')) ?>">
                            <i class="bi bi-qr-code-scan"></i>
                            <span>Table QRs</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role !== 'superadmin'): ?>
                        <a class="sidebar-link <?= $isOrders ? 'active' : '' ?>" href="<?= e(url('/dashboard/orders')) ?>">
                            <i class="bi bi-clock-history"></i>
                            <span>Live Orders</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role === 'owner'): ?>
                        <a class="sidebar-link <?= $isReviews ? 'active' : '' ?>" href="<?= e(url('/dashboard/reviews')) ?>">
                            <i class="bi bi-star-half"></i>
                            <span>Customer Reviews</span>
                        </a>
                        <a class="sidebar-link <?= $isStaff ? 'active' : '' ?>" href="<?= e(url('/dashboard/staff')) ?>">
                            <i class="bi bi-people-fill"></i>
                            <span>Staff Accounts</span>
                        </a>
                        <a class="sidebar-link <?= $isReports ? 'active' : '' ?>" href="<?= e(url('/dashboard/reports')) ?>">
                            <i class="bi bi-bar-chart-line-fill"></i>
                            <span>Reports</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
            
            <div class="border-top border-secondary-subtle pt-3 w-100">
                <div class="d-flex align-items-center gap-3 mb-3 px-2">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 2.2rem; height: 2.2rem; font-weight: 700; font-size: 0.9rem;">
                        <?= e(strtoupper(substr($user['name'] ?? 'U', 0, 1))) ?>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-white mb-0 text-truncate fw-semibold small"><?= e($user['name'] ?? 'User') ?></p>
                        <p class="text-secondary mb-0 small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em; color: #a7f3d0 !important;">
                            <?= $role === 'owner' ? 'Restaurant Owner' : 'Restaurant Staff' ?>
                        </p>
                    </div>
                </div>
                <form class="w-100 px-2" method="post" action="<?= e(url('/logout')) ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn-outline-light w-100 btn-sm d-flex align-items-center justify-content-center gap-2" type="submit">
                        <i class="bi bi-box-arrow-right"></i> Sign out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Header Bar -->
        <nav class="mobile-nav navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(url($role === 'owner' ? '/dashboard' : '/dashboard/orders')) ?>">
                    <span class="brand-mark" style="height: 2rem; width: 2rem; font-size: 0.9rem;">HB</span>
                    <span class="fw-bold font-display" style="font-size: 1.15rem;">Healthy Bite</span>
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavMenu" aria-controls="mobileNavMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mobileNavMenu">
                    <ul class="navbar-nav ms-auto mt-3 gap-2 align-items-stretch">
                        <?php if ($role === 'owner'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $isDashboard ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard')) ?>">
                                    <i class="bi bi-grid-fill me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $isCategories ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/categories')) ?>">
                                    <i class="bi bi-tags-fill me-2"></i>Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $isMenu ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/menu')) ?>">
                                    <i class="bi bi-egg-fried me-2"></i>Food Menu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $isTables ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/tables')) ?>">
                                    <i class="bi bi-qr-code-scan me-2"></i>Table QRs
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link <?= $isOrders ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/orders')) ?>">
                                <i class="bi bi-clock-history me-2"></i>Live Orders
                            </a>
                        </li>

                        <?php if ($role === 'owner'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $isReviews ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/reviews')) ?>">
                                    <i class="bi bi-star-half me-2"></i>Reviews
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $isStaff ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/staff')) ?>">
                                    <i class="bi bi-people-fill me-2"></i>Staff Accounts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $isReports ? 'active bg-dark-subtle rounded px-3' : '' ?>" href="<?= e(url('/dashboard/reports')) ?>">
                                    <i class="bi bi-bar-chart-line-fill me-2"></i>Reports
                                </a>
                            </li>
                        <?php endif; ?>

                        <hr class="text-white opacity-25 my-2">
                        <li class="nav-item d-flex align-items-center justify-content-between px-3 text-secondary py-1">
                            <span class="small text-truncate me-2"><?= e($user['name'] ?? '') ?></span>
                            <span class="badge bg-success-subtle text-success small" style="font-size: 0.65rem;">
                                <?= $role === 'owner' ? 'OWNER' : 'STAFF' ?>
                            </span>
                        </li>
                        <li class="nav-item mt-2">
                            <form method="post" action="<?= e(url('/logout')) ?>">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline-light w-100 btn-sm" type="submit">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <?= $content ?>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
