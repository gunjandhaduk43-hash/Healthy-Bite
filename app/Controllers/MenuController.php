<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\MvpRepository;

final class MenuController extends Controller
{
    private MvpRepository $mvpRepository;

    public function __construct()
    {
        $this->mvpRepository = new MvpRepository();
    }

    public function index(): void
    {
        $token = trim((string) ($_GET['token'] ?? ''));

        if ($token === '') {
            $this->renderCustomerError('Session Token Missing', 'Please scan the table QR code again to access the menu.');
            return;
        }

        $context = $this->mvpRepository->qrContext($token);

        if ($context === null) {
            $this->renderCustomerError('Session Expired or Invalid', 'This table session is no longer active. Please contact staff to request a new session.');
            return;
        }

        $restaurantId = (int) $context['restaurant_id'];
        $tableId = (int) $context['table_id'];

        // Store table session details in PHP session
        $_SESSION['customer_restaurant_id'] = $restaurantId;
        $_SESSION['customer_table_id'] = $tableId;
        $_SESSION['customer_token'] = $token;

        $categories = $this->mvpRepository->categories($restaurantId, true);
        $foods = $this->mvpRepository->foods($restaurantId, true);

        // Attach variants and customizations to each food item for display
        foreach ($foods as &$food) {
            $fId = (int) $food['id'];
            $food['variants'] = $this->mvpRepository->variants($fId);
            $food['customizations'] = $this->mvpRepository->customizations($fId);
        }

        // Group foods by category_id for simple view mapping
        $groupedFoods = [];
        foreach ($foods as $food) {
            $catId = (int) $food['category_id'];
            $groupedFoods[$catId][] = $food;
        }

        View::render('menu/index', [
            'title' => $context['restaurant_name'] . ' — Digital Menu',
            'restaurant' => [
                'name' => $context['restaurant_name'],
                'cuisine' => !empty($context['cuisine_type']) ? $context['cuisine_type'] : 'Multi-Cuisine',
                'description' => !empty($context['restaurant_description']) ? $context['restaurant_description'] : 'Delightful food freshly prepared with high quality ingredients.',
                'phone' => $context['restaurant_phone'] ?? '',
                'address' => $context['restaurant_address'] ?? '',
                'city' => $context['restaurant_city'] ?? '',
                'rating' => $context['avg_rating'] ?? 4.9,
                'reviews_count' => $context['total_reviews'] ?? 124,
                'is_open' => true,
            ],
            'restaurantName' => $context['restaurant_name'],
            'tableName' => $context['table_number'] ?? 'Table',
            'categories' => $categories,
            'groupedFoods' => $groupedFoods,
            'foods' => $foods,
            'token' => $token,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
        ], 'menu');
    }

    public function checkout(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your session expired. Please refresh the page and try again.');
            $token = trim((string) ($_POST['token'] ?? ''));
            $this->redirect($token !== '' ? '/menu?token=' . $token : '/');
        }

        $restaurantId = (int) ($_SESSION['customer_restaurant_id'] ?? 0);
        $tableId = (int) ($_SESSION['customer_table_id'] ?? 0);
        $token = trim((string) ($_SESSION['customer_token'] ?? ''));

        if ($restaurantId === 0 || $tableId === 0 || $token === '') {
            Flash::set('error', 'Your dining session is missing. Please scan the QR code.');
            $this->redirect('/');
        }

        $note = trim((string) ($_POST['note'] ?? ''));
        $customerName = trim((string) ($_POST['customer_name'] ?? ''));
        $customerPhone = trim((string) ($_POST['customer_phone'] ?? ''));
        $cart = [];

        // Check if JSON cart payload is submitted (from dynamic cart drawer)
        $cartJson = trim((string) ($_POST['cart_json'] ?? ''));
        if ($cartJson !== '') {
            $decodedCart = json_decode($cartJson, true);
            if (is_array($decodedCart)) {
                foreach ($decodedCart as $item) {
                    $qty = (int) ($item['quantity'] ?? 0);
                    $fId = (int) ($item['food_id'] ?? 0);
                    if ($qty > 0 && $fId > 0) {
                        $cart[] = [
                            'food_id' => $fId,
                            'quantity' => $qty,
                            'variant_id' => !empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                            'customization_ids' => !empty($item['customization_ids']) ? array_map('intval', (array) $item['customization_ids']) : [],
                            'note' => !empty($item['note']) ? trim((string) $item['note']) : null
                        ];
                    }
                }
            }
        }

        // Fallback to classic form post if cart_json wasn't used
        if ($cart === []) {
            $quantities = $_POST['quantities'] ?? [];
            $selectedVariants = $_POST['variants'] ?? [];
            $selectedCustomizations = $_POST['customizations'] ?? [];

            foreach ($quantities as $foodIdStr => $qtyStr) {
                $qty = (int) $qtyStr;
                if ($qty > 0) {
                    $foodId = (int) $foodIdStr;
                    $cart[] = [
                        'food_id' => $foodId,
                        'quantity' => $qty,
                        'variant_id' => !empty($selectedVariants[$foodId]) ? (int) $selectedVariants[$foodId] : null,
                        'customization_ids' => !empty($selectedCustomizations[$foodId]) ? array_map('intval', (array) $selectedCustomizations[$foodId]) : []
                    ];
                }
            }
        }

        if ($cart === []) {
            Flash::set('error', 'Your cart is empty. Please add items to checkout.');
            $this->redirect('/menu?token=' . $token);
        }

        $orderResult = $this->mvpRepository->createOrder(
            $restaurantId,
            $tableId,
            $cart,
            $note,
            $customerName ?: 'Guest',
            $customerPhone
        );

        if ($orderResult === null) {
            Flash::set('error', 'Failed to place your order. One or more items might be sold out.');
            $this->redirect('/menu?token=' . $token);
        }

        $this->redirect('/menu/order?id=' . $orderResult['order_id'] . '&token=' . $token);
    }

    public function status(): void
    {
        $orderId = (int) ($_GET['id'] ?? 0);
        $token = trim((string) ($_GET['token'] ?? ''));

        if ($orderId <= 0 || $token === '') {
            $this->renderCustomerError('Order Not Found', 'Invalid access parameters.');
            return;
        }

        $context = $this->mvpRepository->qrContext($token);
        if ($context === null) {
            $this->renderCustomerError('Session Invalid', 'Table session expired.');
            return;
        }

        $restaurantId = (int) $context['restaurant_id'];
        $tableId = (int) $context['table_id'];

        $order = $this->mvpRepository->orderForCustomer($orderId, $restaurantId, $tableId);

        if ($order === null) {
            $this->renderCustomerError('Order Not Found', 'We could not locate this order under your current table.');
            return;
        }

        // Fetch itemized details including variants and customizations
        $items = $this->mvpRepository->orderItemsWithDetails($orderId);
        $payment = $this->mvpRepository->paymentForOrder($orderId);

        View::render('menu/order_status', [
            'title' => 'Track Order #' . $order['order_number'],
            'restaurant' => [
                'name' => $context['restaurant_name'],
                'cuisine' => !empty($context['cuisine_type']) ? $context['cuisine_type'] : 'Multi-Cuisine',
                'description' => $context['restaurant_description'] ?? '',
            ],
            'restaurantName' => $context['restaurant_name'],
            'tableName' => $context['table_number'] ?? 'Table',
            'order' => $order,
            'items' => $items,
            'token' => $token,
            'payment' => $payment,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
        ], 'menu');
    }

    public function pollStatus(): void
    {
        header('Content-Type: application/json');

        $orderId = (int) ($_GET['id'] ?? 0);
        $token = trim((string) ($_GET['token'] ?? ''));

        if ($orderId <= 0 || $token === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid parameters']);
            return;
        }

        $context = $this->mvpRepository->qrContext($token);
        if ($context === null) {
            http_response_code(403);
            echo json_encode(['error' => 'Session expired']);
            return;
        }

        $order = $this->mvpRepository->orderForCustomer($orderId, (int) $context['restaurant_id'], (int) $context['table_id']);

        if ($order === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
            return;
        }

        echo json_encode(['status' => $order['status']]);
    }

    private function renderCustomerError(string $title, string $message): void
    {
        View::render('errors/403', [
            'title' => $title,
            'message' => $message
        ], 'auth');
    }
}
