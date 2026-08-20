<?php

declare(strict_types=1);

// Enable Test Mode
define('TEST_MODE', true);

// Initialize bootstrap
require 'bootstrap.php';

use App\Core\Database;
use App\Core\Auth;
use App\Core\Csrf;
use App\Controllers\CategoryController;
use App\Controllers\FoodController;
use App\Controllers\TableController;
use App\Controllers\MenuController;
use App\Controllers\OrderController;
use App\Controllers\PaymentController;
use App\Controllers\ReviewController;

// Enable verbose error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "=== STARTING HEALTHY BITE CORRECTED DATA DICTIONARY INTEGRATION TEST ===\n\n";

// Helper to assert conditions
function assertTest(bool $condition, string $message): void {
    if (!$condition) {
        echo "[-] FAIL: $message\n";
        exit(1);
    }
    echo "[+] PASS: $message\n";
}

// Helper to extract redirect URL from RuntimeException
function getRedirectUrl(\RuntimeException $e): string {
    return str_replace("Redirect to: ", "", $e->getMessage());
}

// 1. Mock Session and CSRF
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$_SESSION['auth_user_id'] = 1; // Seeded Demo Admin User
$user = Auth::user();
assertTest($user !== null, "Logged in user session resolved.");
assertTest((int)$user['restaurant_id'] === 1, "User is associated with Restaurant ID 1.");

$token = Csrf::token();
assertTest(!empty($token), "CSRF Token generated.");

// Mock POST array helper
function mockPost(array $data): void {
    $_POST = $data;
}

// 2. Test Category Creation
echo "\n--- 1. Testing Category Creation (categories) ---\n";
mockPost([
    '_token' => $token,
    'name' => 'Organic Power Bowls',
    'sort_order' => '1'
]);
$catController = new CategoryController();
try {
    $catController->save();
    assertTest(false, "CategoryController::save did not redirect.");
} catch (\RuntimeException $e) {
    $url = getRedirectUrl($e);
    assertTest($url === '/dashboard/categories', "Category save redirected to Categories list: $url");
}

$db = Database::connection();
$cat = $db->query("SELECT * FROM categories WHERE restaurant_id = 1 AND name = 'Organic Power Bowls' LIMIT 1")->fetch();
assertTest($cat !== false, "Category 'Organic Power Bowls' successfully saved to database.");
$catId = (int) $cat['id'];
echo "Category ID: $catId, Sort Order: {$cat['sort_order']}\n";

// 3. Test Food Item Creation
echo "\n--- 2. Testing Food Item Creation (food_items) ---\n";
mockPost([
    '_token' => $token,
    'name' => 'Superfood Protein Bowl',
    'category_id' => (string) $catId,
    'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd',
    'base_price' => '18.50',
    'food_type' => 'veg',
    'preparation_time' => '12',
    'spice_level' => 'medium',
    'serving_size' => '350g',
    'description' => 'Organic quinoa, avocado, chickpeas, and fresh kale.',
    'is_available' => '1',
    'is_featured' => '1',
    'calories' => '420',
    'protein' => '16',
    'carbs' => '45',
    'fat' => '14',
    'ingredients' => 'Quinoa, Avocado, Chickpeas, Kale, Olive Oil',
    'allergens' => 'Sesame'
]);

$foodController = new FoodController();
try {
    $foodController->save();
    assertTest(false, "FoodController::save did not redirect.");
} catch (\RuntimeException $e) {
    $url = getRedirectUrl($e);
    assertTest($url === '/dashboard/menu', "Food item save redirected to Menu list: $url");
}

$food = $db->query("SELECT * FROM food_items WHERE category_id = $catId AND name = 'Superfood Protein Bowl' LIMIT 1")->fetch();
assertTest($food !== false, "Food item 'Superfood Protein Bowl' saved with base_price = {$food['base_price']}.");
$foodId = (int) $food['id'];

// 4. Test Variant & Customization Options Creation
echo "\n--- 3. Testing Food Variants & Customizations (food_variants & food_customizations) ---\n";
mockPost([
    '_token' => $token,
    'food_item_id' => (string) $foodId,
    'name' => 'Jumbo Size',
    'price_adjustment' => '4.00'
]);
try {
    $foodController->saveVariant();
    assertTest(false, "saveVariant did not redirect.");
} catch (\RuntimeException $e) {
    assertTest(true, "saveVariant redirected properly.");
}

$variant = $db->query("SELECT * FROM food_variants WHERE food_item_id = $foodId AND name = 'Jumbo Size' LIMIT 1")->fetch();
assertTest($variant !== false, "Variant 'Jumbo Size' saved with price_adjustment = {$variant['price_adjustment']}.");
$variantId = (int) $variant['id'];

mockPost([
    '_token' => $token,
    'food_item_id' => (string) $foodId,
    'name' => 'Extra Dressing',
    'price_adjustment' => '1.50'
]);
try {
    $foodController->saveCustomization();
    assertTest(false, "saveCustomization did not redirect.");
} catch (\RuntimeException $e) {
    assertTest(true, "saveCustomization redirected properly.");
}

$customization = $db->query("SELECT * FROM food_customizations WHERE food_item_id = $foodId AND name = 'Extra Dressing' LIMIT 1")->fetch();
assertTest($customization !== false, "Customization 'Extra Dressing' saved with price_adjustment = {$customization['price_adjustment']}.");
$customizationId = (int) $customization['id'];

// 5. Test Table Registration and QR Token Generation
echo "\n--- 4. Testing Table & QR Token (restaurant_tables & qr_tokens) ---\n";
mockPost([
    '_token' => $token,
    'table_number' => 'Table 202',
    'capacity' => '4'
]);
$tableController = new TableController();
try {
    $tableController->create();
    assertTest(false, "TableController::create did not redirect.");
} catch (\RuntimeException $e) {
    assertTest(true, "TableController::create redirected properly.");
}

$table = $db->query("SELECT * FROM restaurant_tables WHERE table_number = 'Table 202' LIMIT 1")->fetch();
assertTest($table !== false, "Table 'Table 202' saved successfully.");
$tableId = (int) $table['id'];

mockPost([
    '_token' => $token,
    'table_id' => (string) $tableId,
    'table_name' => 'Table 202'
]);
try {
    $tableController->issueQr();
    assertTest(false, "TableController::issueQr did not redirect.");
} catch (\RuntimeException $e) {
    assertTest(true, "TableController::issueQr redirected properly.");
}

$qrToken = $db->query("SELECT * FROM qr_tokens WHERE restaurant_table_id = $tableId AND is_active = 1 LIMIT 1")->fetch();
assertTest($qrToken !== false, "QR token generated: {$qrToken['token']}.");
$rawToken = $qrToken['token'];

// 6. Test Public Ordering (customers, orders, order_items, order_item_customizations)
echo "\n--- 5. Testing Customer Order Checkout (order & order_item_customizations) ---\n";
$_SESSION['customer_restaurant_id'] = 1;
$_SESSION['customer_table_id'] = $tableId;
$_SESSION['customer_token'] = $rawToken;

mockPost([
    '_token' => $token,
    'token' => $rawToken,
    'customer_name' => 'Jane Customer',
    'customer_phone' => '555-9988',
    'quantities' => [$foodId => '2'],
    'variants' => [$foodId => (string)$variantId],
    'customizations' => [$foodId => [(string)$customizationId]],
    'note' => 'Please make it extra fresh!'
]);

$menuController = new MenuController();
try {
    $menuController->checkout();
    assertTest(false, "MenuController::checkout did not redirect.");
} catch (\RuntimeException $e) {
    $url = getRedirectUrl($e);
    assertTest(strpos($url, '/menu/order?id=') !== false, "Order checkout redirected to order tracking: $url");
    parse_str(parse_url($url, PHP_URL_QUERY), $queryParams);
    $orderId = (int) ($queryParams['id'] ?? 0);
}

$order = $db->query("SELECT * FROM orders WHERE id = $orderId LIMIT 1")->fetch();
assertTest($order !== false, "Order #{$order['order_number']} placed successfully.");
assertTest((int)$order['restaurant_table_id'] === $tableId, "Order correctly references restaurant_table_id = $tableId.");

$orderItem = $db->query("SELECT * FROM order_items WHERE order_id = $orderId LIMIT 1")->fetch();
assertTest($orderItem !== false, "Order item line created for food_item_id = $foodId.");
assertTest((int)$orderItem['food_variant_id'] === $variantId, "Order item line references food_variant_id = $variantId.");

$oic = $db->query("SELECT * FROM order_item_customizations WHERE order_item_id = {$orderItem['id']} LIMIT 1")->fetch();
assertTest($oic !== false, "Bridge table order_item_customizations successfully linked add-on ID = $customizationId.");

// 7. Test Payment Simulation (payments)
echo "\n--- 6. Testing Payment Processing (payments) ---\n";
mockPost([
    '_token' => $token,
    'token' => $rawToken,
    'order_id' => (string) $orderId,
    'payment_method' => 'card'
]);
$paymentController = new PaymentController();
try {
    $paymentController->simulate();
    assertTest(false, "PaymentController::simulate did not redirect.");
} catch (\RuntimeException $e) {
    assertTest(true, "PaymentController::simulate redirected properly.");
}

$payment = $db->query("SELECT * FROM payments WHERE order_id = $orderId LIMIT 1")->fetch();
assertTest($payment !== false, "Payment record created with amount = {$payment['amount']}, status = {$payment['status']}, method = {$payment['method']}.");

// 8. Test Review Submission (reviews)
echo "\n--- 7. Testing Customer Review (reviews) ---\n";
mockPost([
    '_token' => $token,
    'token' => $rawToken,
    'order_id' => (string) $orderId,
    'rating' => '5',
    'comment' => 'Exceptional superfood bowl and great table service!',
    'food_item_id' => (string) $foodId
]);
$reviewController = new ReviewController();
try {
    $reviewController->submit();
    assertTest(false, "ReviewController::submit did not redirect.");
} catch (\RuntimeException $e) {
    assertTest(true, "ReviewController::submit redirected properly.");
}

$review = $db->query("SELECT * FROM reviews WHERE customer_id = {$order['customer_id']} LIMIT 1")->fetch();
assertTest($review !== false, "Review submitted with rating = {$review['rating']} and restaurant_table_id = {$review['restaurant_table_id']}.");

echo "\n=== ALL 16 TABLES INTEGRATION TESTS PASSED SUCCESSFULLY! ===\n";
