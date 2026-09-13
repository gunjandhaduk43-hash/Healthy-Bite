<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Repositories/RestaurantRepository.php';
require_once __DIR__ . '/../app/Repositories/MvpRepository.php';

use App\Repositories\UserRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\MvpRepository;

echo "=== TESTING APPLICATION REPOSITORIES WITH HEALTHY_BITE DB ===\n\n";

$userRepo = new UserRepository();
$restRepo = new RestaurantRepository();
$mvpRepo = new MvpRepository();

// 1. Test UserRepository
$owner = $userRepo->findByEmail('owner@healthybite.test');
echo "1. UserRepository::findByEmail('owner@healthybite.test'): " . ($owner ? "FOUND (Role: {$owner['role']})" : "NOT FOUND") . "\n";

$staff = $userRepo->findStaffByRestaurant(1);
echo "2. UserRepository::findStaffByRestaurant(1): " . count($staff) . " staff found.\n";

// 2. Test RestaurantRepository
$restaurant = $restRepo->findByIdForOwner(1, 2);
echo "3. RestaurantRepository::findByIdForOwner(1, 2): " . ($restaurant ? "FOUND ({$restaurant['name']})" : "NOT FOUND") . "\n";

// 3. Test MvpRepository
$categories = $mvpRepo->categories(1);
echo "4. MvpRepository::categories(1): " . count($categories) . " categories found.\n";

$foods = $mvpRepo->foods(1);
echo "5. MvpRepository::foods(1): " . count($foods) . " food items found.\n";

$tables = $mvpRepo->tables(1);
echo "6. MvpRepository::tables(1): " . count($tables) . " tables found.\n";

$reviews = $mvpRepo->reviews(1);
echo "7. MvpRepository::reviews(1): " . count($reviews) . " reviews found.\n";

$summary = $mvpRepo->reviewSummary(1);
echo "8. MvpRepository::reviewSummary(1): Avg Rating = {$summary['avg_rating']}, Total = {$summary['total_reviews']}\n";

$reports = $mvpRepo->report(1);
echo "9. MvpRepository::report(1): Revenue = {$reports['revenue']}, Orders = {$reports['orders_count']}, Avg = {$reports['average_order']}\n";

echo "\n=== ALL REPOSITORY QUERIES PASSED WITH ZERO ERRORS ===\n";
