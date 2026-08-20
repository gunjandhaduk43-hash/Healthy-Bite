<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MvpRepository;

final class CategoryController extends Controller
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

        $categories = $this->mvpRepository->categories($restaurantId);

        $this->view('dashboard/categories', [
            'title' => 'Manage Categories',
            'user' => $user,
            'categories' => $categories,
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
            $this->redirect('/dashboard/categories');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        $sortOrderStr = trim((string) ($_POST['sort_order'] ?? '0'));
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Category name is required.';
        } elseif (mb_strlen($name) > 120) {
            $errors['name'] = 'Category name must be 120 characters or fewer.';
        }

        if (!filter_var($sortOrderStr, FILTER_VALIDATE_INT) && $sortOrderStr !== '0') {
            $errors['sort_order'] = 'Sort order must be an integer.';
        }

        if ($errors !== []) {
            Flash::set('errors', $errors);
            Flash::set('old', ['name' => $name, 'sort_order' => $sortOrderStr]);
            $this->redirect('/dashboard/categories');
        }

        try {
            $sortOrder = (int) $sortOrderStr;
            $this->mvpRepository->saveCategory($restaurantId, $name, $sortOrder);
            Flash::set('success', 'Category successfully saved.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to save category: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/categories');
    }

    public function toggle(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/categories');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $categoryId = (int) ($_POST['category_id'] ?? 0);

        if ($categoryId > 0) {
            $this->mvpRepository->toggleCategory($restaurantId, $categoryId);
            Flash::set('success', 'Category status updated.');
        } else {
            Flash::set('error', 'Invalid category selected.');
        }

        $this->redirect('/dashboard/categories');
    }
}
