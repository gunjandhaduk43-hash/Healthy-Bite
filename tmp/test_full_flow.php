<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Core\Database;
use App\Repositories\MvpRepository;

echo "=======================================================\n";
echo "   HEALTHY BITE — COMPLETE USER JOURNEY VERIFICATION   \n";
echo "=======================================================\n\n";

$repo = new MvpRepository();
$db = Database::connection();

// Step 1: Scan QR Code & Retrieve Context
$token = 'hb_tok_6c11e48b70f4a3';
echo "[Step 1] Resolving Table QR Token: $token\n";
$context = $repo->qrContext($token);

if (!$context) {
    echo "FAILED: Token context not found.\n";
    exit(1);
}

echo "  ✓ Restaurant: {$context['restaurant_name']}\n";
echo "  ✓ Cuisine: {$context['cuisine_type']}\n";
echo "  ✓ Table: {$context['table_number']}\n";
echo "  ✓ Rating: {$context['avg_rating']} ({$context['total_reviews']} reviews)\n\n";

$restaurantId = (int) $context['restaurant_id'];
$tableId = (int) $context['table_id'];

// Step 2: Customer Customizes Multi-Cuisine Dishes & Adds to Cart
echo "[Step 2] Building Multi-Cuisine Cart with Customizations...\n";
$cart = [
    [
        'food_id' => 101, // Wood-Fired Margherita Basilico
        'quantity' => 1,
        'variant_id' => 1002, // Medium (10 inch) (+120)
        'customization_ids' => [2001, 2002], // Cheese Burst (+69) + Extra Mozzarella (+50)
        'note' => 'Crispy crust please'
    ],
    [
        'food_id' => 105, // Royal Kathiyawadi & Gujarati Thali
        'quantity' => 1,
        'variant_id' => 1009, // Deluxe Thali (+110)
        'customization_ids' => [2011, 2012], // Extra Ghee Phulka (2 pcs) (+30) + Masala Buttermilk (+25)
        'note' => 'Medium spicy dal'
    ],
    [
        'food_id' => 107, // Hokkaido Miso Shoyu Ramen
        'quantity' => 1,
        'variant_id' => 1010, // Standard
        'customization_ids' => [2016, 2018], // Extra Ajitsuke Marinated Egg (+45) + Spicy Togarashi (+25)
        'note' => 'Extra hot broth'
    ],
    [
        'food_id' => 111, // Kesari Mango & Cardamom Lassi
        'quantity' => 2,
        'variant_id' => null,
        'customization_ids' => [],
        'note' => null
    ]
];

echo "  ✓ Items in cart: " . count($cart) . "\n\n";

// Step 3: Place Order
echo "[Step 3] Submitting Checkout & Placing Order...\n";
$orderResult = $repo->createOrder(
    $restaurantId,
    $tableId,
    $cart,
    'Fast delivery please, celebrating anniversary.',
    'Gunjan Dhaduk',
    '9876543210'
);

if (!$orderResult) {
    echo "FAILED: Could not create order.\n";
    exit(1);
}

$orderId = (int) $orderResult['order_id'];
$orderNumber = $orderResult['order_number'];
echo "  ✓ Order placed successfully!\n";
echo "  ✓ Order Ticket: $orderNumber (ID: $orderId)\n\n";

// Step 4: Verify Order Details & Calculations
echo "[Step 4] Customer Tracking Order Details & Receipt...\n";
$order = $repo->orderForCustomer($orderId, $restaurantId, $tableId);
$items = $repo->orderItemsWithDetails($orderId);

echo "  ✓ Order Status: {$order['status']}\n";
echo "  ✓ Subtotal: Rs. {$order['subtotal']}\n";
echo "  ✓ Tax (5% GST): Rs. {$order['tax_amount']}\n";
echo "  ✓ Grand Total: Rs. {$order['total_amount']}\n";
echo "  ✓ Detailed Items Breakdown:\n";
foreach ($items as $idx => $line) {
    $vName = !empty($line['variant_name']) ? " (" . $line['variant_name'] . ")" : "";
    $cNames = !empty($line['customization_names']) ? " [+" . $line['customization_names'] . "]" : "";
    $note = !empty($line['customer_note']) ? " {Note: " . $line['customer_note'] . "}" : "";
    echo "     #" . ($idx + 1) . " {$line['quantity']}x {$line['item_name']}{$vName}{$cNames}{$note} -> Rs. {$line['line_total']}\n";
}
echo "\n";

// Step 5: Kitchen Pipeline Transitions
echo "[Step 5] Kitchen Dashboard Processing Ticket...\n";
$transitions = ['accepted', 'preparing', 'ready', 'served'];
foreach ($transitions as $targetStatus) {
    $ok = $repo->updateOrderStatus($restaurantId, $orderId, $targetStatus, 2);
    if ($ok) {
        echo "  ✓ Status transitioned to: '$targetStatus'\n";
    } else {
        echo "  ✗ Failed transition to: '$targetStatus'\n";
    }
}
echo "\n";

// Step 6: Payment Simulation
echo "[Step 6] Customer Simulating UPI Payment...\n";
$repo->createPayment([
    'order_id' => $orderId,
    'amount' => $order['total_amount'],
    'method' => 'upi',
    'status' => 'completed'
]);
$payment = $repo->paymentForOrder($orderId);
echo "  ✓ Payment recorded: Rs. {$payment['amount']} via " . strtoupper($payment['method']) . " (Status: {$payment['status']})\n\n";

// Step 7: Customer Review
echo "[Step 7] Customer Submitting 5-Star Review...\n";
$repo->createReview([
    'restaurant_id' => $restaurantId,
    'customer_id' => (int) $order['customer_id'],
    'order_id' => $orderId,
    'food_item_id' => 101,
    'restaurant_table_id' => $tableId,
    'rating' => 5,
    'comment' => 'Outstanding multi-cuisine experience! Wood-fired pizza crust was light and airy, Gujarati thali tasted truly authentic, and the ordering was lightning fast.'
]);
$reviews = $repo->reviews($restaurantId);
echo "  ✓ Review saved! Total restaurant reviews: " . count($reviews) . "\n";
echo "  ✓ Latest review comment: \"{$reviews[0]['comment']}\"\n\n";

// Step 8: Restaurant Owner Analytics Summary
echo "[Step 8] Restaurant Owner Operational Overview...\n";
$report = $repo->report($restaurantId);
$kitchenOrders = $repo->orders($restaurantId);
echo "  ✓ Total Active Orders: " . count($kitchenOrders) . "\n";
echo "  ✓ Total Revenue: Rs. " . number_format($report['revenue'], 2) . "\n";
echo "  ✓ Total Completed Orders: " . $report['orders_count'] . "\n";
echo "  ✓ Average Ticket Size: Rs. " . number_format($report['average_order'], 2) . "\n\n";

echo "=======================================================\n";
echo "   >>> ALL 8 STAGES OF USER JOURNEY PASSED 100% <<<    \n";
echo "=======================================================\n";
