<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\CategoryController;
use App\Controllers\FoodController;
use App\Controllers\TableController;
use App\Controllers\OrderController;
use App\Controllers\ReportController;
use App\Controllers\MenuController;
use App\Controllers\StaffController;
use App\Controllers\ReviewController;
use App\Controllers\PaymentController;
use App\Controllers\LandingController;
use App\Controllers\AdminController;
use App\Middleware\Authenticate;
use App\Middleware\GuestOnly;
use App\Middleware\OwnerOnly;

$router->get('/', [LandingController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin'], [GuestOnly::class]);
$router->post('/login', [AuthController::class, 'login'], [GuestOnly::class]);
$router->get('/register', [AuthController::class, 'showRegister'], [GuestOnly::class]);
$router->post('/register', [AuthController::class, 'register'], [GuestOnly::class]);
$router->post('/logout', [AuthController::class, 'logout'], [Authenticate::class]);

// Super Admin Portal (admin_id = 1)
$router->get('/admin/dashboard', [AdminController::class, 'dashboard'], [Authenticate::class]);
$router->post('/admin/restaurants/status', [AdminController::class, 'updateStatus'], [Authenticate::class]);

// Owner Dashboard Core & Restaurant Profiles
$router->get('/dashboard', [DashboardController::class, 'index'], [Authenticate::class]);
$router->post('/dashboard/restaurant', [DashboardController::class, 'updateRestaurant'], [Authenticate::class, OwnerOnly::class]);

// Stage 2: Category Management
$router->get('/dashboard/categories', [CategoryController::class, 'index'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/categories/save', [CategoryController::class, 'save'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/categories/toggle', [CategoryController::class, 'toggle'], [Authenticate::class, OwnerOnly::class]);

// Stage 2: Food Menu Management
$router->get('/dashboard/menu', [FoodController::class, 'index'], [Authenticate::class, OwnerOnly::class]);
$router->get('/dashboard/menu/create', [FoodController::class, 'create'], [Authenticate::class, OwnerOnly::class]);
$router->get('/dashboard/menu/edit', [FoodController::class, 'edit'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/menu/save', [FoodController::class, 'save'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/menu/toggle', [FoodController::class, 'toggle'], [Authenticate::class, OwnerOnly::class]);
$router->get('/dashboard/menu/options', [FoodController::class, 'options'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/menu/options/variant/save', [FoodController::class, 'saveVariant'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/menu/options/customization/save', [FoodController::class, 'saveCustomization'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/menu/options/variant/delete', [FoodController::class, 'deleteVariant'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/menu/options/customization/delete', [FoodController::class, 'deleteCustomization'], [Authenticate::class, OwnerOnly::class]);

// Stage 2: Physical Seating & QR Codes
$router->get('/dashboard/tables', [TableController::class, 'index'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/tables/create', [TableController::class, 'create'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/tables/issue-qr', [TableController::class, 'issueQr'], [Authenticate::class, OwnerOnly::class]);
$router->get('/dashboard/tables/print-qr', [TableController::class, 'printQr'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/tables/status', [TableController::class, 'updateStatus'], [Authenticate::class]);

// Team Management (Staff Accounts)
$router->get('/dashboard/staff', [StaffController::class, 'index'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/staff/save', [StaffController::class, 'save'], [Authenticate::class, OwnerOnly::class]);
$router->post('/dashboard/staff/toggle', [StaffController::class, 'toggle'], [Authenticate::class, OwnerOnly::class]);

// Stage 3: Live Kitchen Orders Panel (Accessible by both Owner and Staff)
$router->get('/dashboard/orders', [OrderController::class, 'index'], [Authenticate::class]);
$router->post('/dashboard/orders/update-status', [OrderController::class, 'updateStatus'], [Authenticate::class]);

// Stage 4: Basic Reports & Analytics
$router->get('/dashboard/reports', [ReportController::class, 'index'], [Authenticate::class, OwnerOnly::class]);

// Customer Reviews Owner Dashboard
$router->get('/dashboard/reviews', [ReviewController::class, 'index'], [Authenticate::class, OwnerOnly::class]);

// Stage 3: Public QR Seating Menu & Dynamic Ordering (Public Access)
$router->get('/menu', [MenuController::class, 'index']);
$router->post('/menu/checkout', [MenuController::class, 'checkout']);
$router->get('/menu/order', [MenuController::class, 'status']);
$router->post('/menu/review/submit', [ReviewController::class, 'submit']);
$router->post('/menu/payment/simulate', [PaymentController::class, 'simulate']);
$router->get('/menu/order/poll', [MenuController::class, 'pollStatus']);
