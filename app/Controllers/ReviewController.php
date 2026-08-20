<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MvpRepository;

final class ReviewController extends Controller
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

        $reviews = $this->mvpRepository->reviews($restaurantId);
        $summary = $this->mvpRepository->reviewSummary($restaurantId);

        // Group stars to draw histogram
        $starsCount = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($reviews as $rev) {
            $r = (int) $rev['rating'];
            if ($r >= 1 && $r <= 5) {
                $starsCount[$r]++;
            }
        }

        $this->view('dashboard/reviews', [
            'title' => 'Customer Feedback',
            'user' => $user,
            'reviews' => $reviews,
            'summary' => $summary,
            'starsCount' => $starsCount,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
        ]);
    }

    public function submit(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your session expired. Please refresh and try again.');
            $token = trim((string) ($_POST['token'] ?? ''));
            $this->redirect($token !== '' ? '/menu?token=' . $token : '/');
        }

        $token = trim((string) ($_POST['token'] ?? ''));
        $orderId = (int) ($_POST['order_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 5);
        $comment = strip_tags(trim((string) ($_POST['comment'] ?? '')));
        $foodItemId = (int) ($_POST['food_item_id'] ?? 0);

        if ($orderId <= 0 || $token === '') {
            Flash::set('error', 'Invalid parameter for submitting review.');
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

        if ($rating < 1 || $rating > 5) {
            Flash::set('error', 'Rating must be between 1 and 5 stars.');
            $this->redirect('/menu/order?id=' . $orderId . '&token=' . $token);
        }

        if ($comment === '') {
            Flash::set('error', 'Please write a review comment.');
            $this->redirect('/menu/order?id=' . $orderId . '&token=' . $token);
        }

        try {
            $this->mvpRepository->createReview([
                'restaurant_id' => $restaurantId,
                'food_item_id' => $foodItemId > 0 ? $foodItemId : null,
                'restaurant_table_id' => $tableId,
                'customer_id' => (int) $order['customer_id'],
                'order_id' => $orderId,
                'comment' => $comment,
                'rating' => $rating
            ]);
            Flash::set('success', 'Thank you for your feedback! Your review helps us improve.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to submit review: ' . $e->getMessage());
        }

        $this->redirect('/menu/order?id=' . $orderId . '&token=' . $token);
    }
}
