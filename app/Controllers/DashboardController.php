<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\RestaurantRepository;
use App\Services\RestaurantService;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        if ($user !== null) {
            if ($user['role'] === 'superadmin') {
                $this->redirect('/admin/dashboard');
                return;
            }
            if ($user['role'] === 'staff' || $user['role'] === 'manager') {
                $this->redirect('/dashboard/orders');
                return;
            }
        }

        $restaurant = $this->restaurantFor($user);

        if ($restaurant === null) {
            http_response_code(403);
            $this->view('errors/403', ['title' => 'Restaurant access unavailable'], 'auth');
            return;
        }

        $mvpRepo = new \App\Repositories\MvpRepository();
        $foodsCount = count($mvpRepo->foods((int) $restaurant['id']));
        $ordersCount = count($mvpRepo->orders((int) $restaurant['id']));

        $this->view('dashboard/index', [
            'title' => 'Restaurant Dashboard',
            'user' => $user,
            'restaurant' => $restaurant,
            'foodsCount' => $foodsCount,
            'ordersCount' => $ordersCount,
            'success' => Flash::get('success'),
        ]);

    }

    public function updateRestaurant(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard');
        }

        $user = Auth::user();
        $restaurant = $this->restaurantFor($user);

        if ($restaurant === null || $user === null) {
            http_response_code(403);
            $this->view('errors/403', ['title' => 'Restaurant access unavailable'], 'auth');
            return;
        }

        $errors = (new RestaurantService())->updateProfile((int) $restaurant['id'], (int) $user['id'], $_POST);

        if ($errors !== []) {
            $this->view('dashboard/index', [
                'title' => 'Restaurant Dashboard',
                'user' => $user,
                'restaurant' => array_merge($restaurant, $this->profileInput($_POST)),
                'errors' => $errors,
            ]);
            return;
        }

        Flash::set('success', 'Restaurant profile updated.');
        $this->redirect('/dashboard');
    }

    /** @param array<string, mixed>|null $user
     *  @return array<string, mixed>|null
     */
    private function restaurantFor(?array $user): ?array
    {
        if ($user === null || $user['role'] !== 'owner' || empty($user['restaurant_id'])) {
            return null;
        }

        return (new RestaurantRepository())->findByIdForOwner((int) $user['restaurant_id'], (int) $user['id']);
    }

    /** @param array<string, mixed> $input
     *  @return array<string, string>
     */
    private function profileInput(array $input): array
    {
        $keys = ['name', 'email', 'phone', 'address', 'city', 'state', 'cuisine_type', 'description'];
        $profile = [];

        foreach ($keys as $key) {
            $profile[$key] = trim((string) ($input[$key] ?? ''));
        }

        return $profile;
    }
}
