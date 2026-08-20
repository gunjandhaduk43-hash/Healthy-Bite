<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MvpRepository;

final class OrderController extends Controller
{
    private MvpRepository $mvpRepository;

    public function __construct()
    {
        $this->mvpRepository = new MvpRepository();
    }

    public function index(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $orders = $this->mvpRepository->orders($restaurantId);

        $this->view('dashboard/orders', [
            'title' => 'Live Kitchen Queue',
            'user' => $user,
            'orders' => $orders,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
        ]);
    }

    public function updateStatus(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/orders');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);
        $userId = (int) ($user['id'] ?? 0);

        if ($restaurantId === 0 || $userId === 0) {
            $this->redirect('/login');
        }

        $orderId = (int) ($_POST['order_id'] ?? 0);
        $status = trim((string) ($_POST['status'] ?? ''));

        if ($orderId > 0 && $status !== '') {
            $success = $this->mvpRepository->updateOrderStatus($restaurantId, $orderId, $status, $userId);
            if ($success) {
                Flash::set('success', 'Order status updated to ' . ucfirst($status));
            } else {
                Flash::set('error', 'Failed to update order status. Please verify the order state.');
            }
        } else {
            Flash::set('error', 'Invalid order details provided.');
        }

        $this->redirect('/dashboard/orders');
    }
}
