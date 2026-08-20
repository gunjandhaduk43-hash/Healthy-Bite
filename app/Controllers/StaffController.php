<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\UserRepository;

final class StaffController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $staffList = $this->userRepository->findStaffByRestaurant($restaurantId);

        $this->view('dashboard/staff', [
            'title' => 'Manage Staff Accounts',
            'user' => $user,
            'staffList' => $staffList,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
            'errors' => Flash::get('errors') ?? [],
            'old' => Flash::get('old') ?? [],
        ]);
    }

    public function save(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/staff');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Full name is required.';
        } elseif (mb_strlen($name) > 120) {
            $errors['name'] = 'Full name must be 120 characters or fewer.';
        }

        if ($email === '') {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($this->userRepository->findByEmail($email) !== null) {
            $errors['email'] = 'This email address is already in use.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (mb_strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($errors !== []) {
            Flash::set('errors', $errors);
            Flash::set('old', ['name' => $name, 'email' => $email]);
            $this->redirect('/dashboard/staff');
        }

        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $this->userRepository->createStaff($restaurantId, $name, $email, $passwordHash);
            Flash::set('success', 'Staff account created successfully.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to create staff account: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/staff');
    }

    public function toggle(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/staff');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $staffId = (int) ($_POST['staff_id'] ?? 0);

        if ($staffId > 0) {
            $this->userRepository->toggleStaffStatus($restaurantId, $staffId);
            Flash::set('success', 'Staff account status updated.');
        } else {
            Flash::set('error', 'Invalid staff user selected.');
        }

        $this->redirect('/dashboard/staff');
    }
}
