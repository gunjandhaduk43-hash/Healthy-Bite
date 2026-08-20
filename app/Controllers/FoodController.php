<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MvpRepository;

final class FoodController extends Controller
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

        $foods = $this->mvpRepository->foods($restaurantId);

        $this->view('dashboard/menu', [
            'title' => 'Menu Management',
            'user' => $user,
            'foods' => $foods,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
        ]);
    }

    public function create(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $categories = $this->mvpRepository->categories($restaurantId, true);

        $this->view('dashboard/menu_form', [
            'title' => 'Create Menu Item',
            'user' => $user,
            'categories' => $categories,
            'food' => null,
            'errors' => Flash::get('errors') ?? [],
            'old' => Flash::get('old') ?? [],
        ]);
    }

    public function edit(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_GET['id'] ?? 0);
        $food = $this->mvpRepository->food($restaurantId, $foodId);

        if ($food === null) {
            Flash::set('error', 'The requested menu item could not be found.');
            $this->redirect('/dashboard/menu');
        }

        $categories = $this->mvpRepository->categories($restaurantId, true);

        $this->view('dashboard/menu_form', [
            'title' => 'Edit Menu Item - ' . ($food['name'] ?? ''),
            'user' => $user,
            'categories' => $categories,
            'food' => $food,
            'errors' => Flash::get('errors') ?? [],
            'old' => Flash::get('old') ?? [],
        ]);
    }

    public function save(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/menu');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_POST['id'] ?? 0);
        
        // Input parsing
        $name = trim((string) ($_POST['name'] ?? ''));
        $image = trim((string) ($_POST['image'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $description = trim((string) ($_POST['description'] ?? ''));
        $priceStr = trim((string) ($_POST['base_price'] ?? ''));
        
        $calories = trim((string) ($_POST['calories'] ?? ''));
        $protein = trim((string) ($_POST['protein'] ?? ''));
        $carbohydrates = trim((string) ($_POST['carbs'] ?? ''));
        $fat = trim((string) ($_POST['fat'] ?? ''));
        $fiber = trim((string) ($_POST['fiber_g'] ?? ''));
        $sugar = trim((string) ($_POST['sugar_g'] ?? ''));
        
        $ingredients = trim((string) ($_POST['ingredients'] ?? ''));
        $allergens = trim((string) ($_POST['allergens'] ?? ''));
        $prepTime = trim((string) ($_POST['preparation_time'] ?? ''));
        $spiceLevel = trim((string) ($_POST['spice_level'] ?? 'medium'));
        $servingSize = trim((string) ($_POST['serving_size'] ?? ''));
        $foodType = trim((string) ($_POST['food_type'] ?? 'veg'));
        
        $isAvailable = isset($_POST['is_available']) ? 1 : 0;
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        } elseif (mb_strlen($name) > 160) {
            $errors['name'] = 'Name must be 160 characters or fewer.';
        }

        if ($categoryId <= 0) {
            $errors['category_id'] = 'Please select a valid category.';
        }

        if ($priceStr === '' || !is_numeric($priceStr) || (float) $priceStr < 0) {
            $errors['base_price'] = 'Please enter a valid positive price amount.';
        }

        if ($description !== '' && mb_strlen($description) > 1000) {
            $errors['description'] = 'Description must be 1000 characters or fewer.';
        }

        // Validate numeric fields if not empty
        $numericFields = [
            'calories' => $calories,
            'protein' => $protein,
            'carbs' => $carbohydrates,
            'fat' => $fat,
            'fiber_g' => $fiber,
            'sugar_g' => $sugar
        ];
        foreach ($numericFields as $field => $val) {
            if ($val !== '' && (!is_numeric($val) || (float) $val < 0)) {
                $errors[$field] = 'Please enter a positive numeric value.';
            }
        }

        if ($prepTime !== '' && (!filter_var($prepTime, FILTER_VALIDATE_INT) || (int) $prepTime < 0)) {
            $errors['preparation_time'] = 'Please enter a valid preparation time in minutes.';
        }

        if ($servingSize !== '' && mb_strlen($servingSize) > 80) {
            $errors['serving_size'] = 'Serving size must be 80 characters or fewer.';
        }

        $allowedDiets = ['veg', 'non_veg', 'vegan', 'jain'];
        if (!in_array($foodType, $allowedDiets, true)) {
            $errors['food_type'] = 'Invalid dietary type selected.';
        }

        $allowedSpices = ['low', 'medium', 'high', 'extra_spicy'];
        if (!in_array($spiceLevel, $allowedSpices, true)) {
            $errors['spice_level'] = 'Invalid spice level selected.';
        }

        if ($errors !== []) {
            Flash::set('errors', $errors);
            Flash::set('old', $_POST);
            $redirectUrl = $foodId > 0 ? '/dashboard/menu/edit?id=' . $foodId : '/dashboard/menu/create';
            $this->redirect($redirectUrl);
        }

        $data = [
            'category_id' => $categoryId,
            'name' => $name,
            'image' => $image !== '' ? $image : null,
            'description' => $description !== '' ? $description : null,
            'base_price' => (float) $priceStr,
            'calories' => $calories !== '' ? (int) $calories : null,
            'protein' => $protein !== '' ? (float) $protein : null,
            'carbs' => $carbohydrates !== '' ? (float) $carbohydrates : null,
            'fat' => $fat !== '' ? (float) $fat : null,
            'fiber_g' => $fiber !== '' ? (float) $fiber : null,
            'sugar_g' => $sugar !== '' ? (float) $sugar : null,
            'ingredients' => $ingredients !== '' ? $ingredients : null,
            'allergens' => $allergens !== '' ? $allergens : null,
            'preparation_time' => $prepTime !== '' ? (int) $prepTime : null,
            'spice_level' => $spiceLevel,
            'serving_size' => $servingSize !== '' ? $servingSize : null,
            'food_type' => $foodType,
            'is_available' => $isAvailable,
            'is_featured' => $isFeatured,
        ];

        if ($foodId > 0) {
            $data['id'] = $foodId;
        }

        try {
            $this->mvpRepository->saveFood($restaurantId, $data);
            Flash::set('success', 'Menu item saved successfully.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to save menu item: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/menu');
    }

    public function toggle(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/menu');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_POST['food_id'] ?? 0);

        if ($foodId > 0) {
            $this->mvpRepository->toggleFood($restaurantId, $foodId);
            Flash::set('success', 'Food availability status updated.');
        } else {
            Flash::set('error', 'Invalid food item selected.');
        }

        $this->redirect('/dashboard/menu');
    }

    public function options(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_GET['id'] ?? 0);
        $food = $this->mvpRepository->food($restaurantId, $foodId);

        if ($food === null) {
            Flash::set('error', 'Food item not found.');
            $this->redirect('/dashboard/menu');
        }

        $variants = $this->mvpRepository->variants($foodId);
        $customizations = $this->mvpRepository->customizations($foodId);

        $this->view('dashboard/menu_options', [
            'title' => 'Manage Options - ' . $food['name'],
            'user' => $user,
            'food' => $food,
            'variants' => $variants,
            'customizations' => $customizations,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
            'errors' => Flash::get('errors') ?? [],
            'old' => Flash::get('old') ?? [],
        ]);
    }

    public function saveVariant(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/menu');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_POST['food_item_id'] ?? 0);
        $food = $this->mvpRepository->food($restaurantId, $foodId);

        if ($food === null) {
            Flash::set('error', 'Food item not found.');
            $this->redirect('/dashboard/menu');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $priceDiffStr = trim((string) ($_POST['price_adjustment'] ?? $_POST['price_differential'] ?? '0.00'));

        $errors = [];
        if ($name === '') {
            $errors['variant_name'] = 'Variant name is required.';
        }

        if (!is_numeric($priceDiffStr)) {
            $errors['price_adjustment'] = 'Price adjustment must be a numeric value.';
        }

        if ($errors !== []) {
            Flash::set('errors', $errors);
            Flash::set('old', $_POST);
            $this->redirect('/dashboard/menu/options?id=' . $foodId);
        }

        $data = [
            'food_item_id' => $foodId,
            'name' => $name,
            'price_adjustment' => (float) $priceDiffStr,
        ];

        if ($id > 0) {
            $data['id'] = $id;
        }

        try {
            $this->mvpRepository->saveVariant($data);
            Flash::set('success', 'Variant options saved successfully.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to save variant: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/menu/options?id=' . $foodId);
    }

    public function saveCustomization(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/menu');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_POST['food_item_id'] ?? 0);
        $food = $this->mvpRepository->food($restaurantId, $foodId);

        if ($food === null) {
            Flash::set('error', 'Food item not found.');
            $this->redirect('/dashboard/menu');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $priceStr = trim((string) ($_POST['price_adjustment'] ?? $_POST['price'] ?? '0.00'));

        $errors = [];
        if ($name === '') {
            $errors['customization_name'] = 'Customization name is required.';
        }

        if (!is_numeric($priceStr) || (float) $priceStr < 0) {
            $errors['price_adjustment'] = 'Price adjustment must be a valid positive number.';
        }

        if ($errors !== []) {
            Flash::set('errors', $errors);
            Flash::set('old', $_POST);
            $this->redirect('/dashboard/menu/options?id=' . $foodId);
        }

        $data = [
            'food_item_id' => $foodId,
            'name' => $name,
            'price_adjustment' => (float) $priceStr,
        ];

        if ($id > 0) {
            $data['id'] = $id;
        }

        try {
            $this->mvpRepository->saveCustomization($data);
            Flash::set('success', 'Customization options saved successfully.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to save customization: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/menu/options?id=' . $foodId);
    }

    public function deleteVariant(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/menu');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_POST['food_item_id'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->mvpRepository->deleteVariant($id);
            Flash::set('success', 'Variant removed.');
        }

        $this->redirect('/dashboard/menu/options?id=' . $foodId);
    }

    public function deleteCustomization(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/menu');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $foodId = (int) ($_POST['food_item_id'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->mvpRepository->deleteCustomization($id);
            Flash::set('success', 'Customization removed.');
        }

        $this->redirect('/dashboard/menu/options?id=' . $foodId);
    }
}
