<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MvpRepository;

final class PaymentController extends Controller
{
    private MvpRepository $mvpRepository;

    public function __construct()
    {
        $this->mvpRepository = new MvpRepository();
    }

    public function simulate(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your session expired. Please refresh and try again.');
            $token = trim((string) ($_POST['token'] ?? ''));
            $this->redirect($token !== '' ? '/menu?token=' . $token : '/');
        }

        $token = trim((string) ($_POST['token'] ?? ''));
        $orderId = (int) ($_POST['order_id'] ?? 0);
        $method = trim((string) ($_POST['method'] ?? 'cash'));

        if ($orderId <= 0 || $token === '') {
            Flash::set('error', 'Invalid parameter for payment simulation.');
            $this->redirect('/');
        }

        $context = $this->mvpRepository->qrContext($token);
        if ($context === null) {
            Flash::set('error', 'Dining session expired.');
            $this->redirect('/');
        }

        $restaurantId = (int) $context['restaurant_id'];
        $tableId = (int) $context['table_id'];

        $order = $this->mvpRepository->orderForCustomer($orderId, $restaurantId, $tableId);
        if ($order === null) {
            Flash::set('error', 'Order not found.');
            $this->redirect('/menu?token=' . $token);
        }

        if (!in_array($method, ['cash', 'upi', 'card'], true)) {
            Flash::set('error', 'Invalid payment method selected.');
            $this->redirect('/menu/order?id=' . $orderId . '&token=' . $token);
        }

        // Check if already paid
        $existing = $this->mvpRepository->paymentForOrder($orderId);
        if ($existing !== null) {
            Flash::set('success', 'Order has already been paid.');
            $this->redirect('/menu/order?id=' . $orderId . '&token=' . $token);
        }

        try {
            $this->mvpRepository->createPayment([
                'order_id' => $orderId,
                'amount' => (float) $order['total_amount'],
                'method' => $method,
                'status' => 'completed'
            ]);
            Flash::set('success', 'Simulated payment of ' . number_format((float)$order['total_amount'], 2) . ' successfully via ' . strtoupper($method) . '!');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to simulate payment: ' . $e->getMessage());
        }

        $this->redirect('/menu/order?id=' . $orderId . '&token=' . $token);
    }
}
