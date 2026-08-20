<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Repositories\RestaurantRepository;
use App\Repositories\UserRepository;
use Throwable;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly RestaurantRepository $restaurants = new RestaurantRepository()
    ) {
    }

    /** @param array<string, mixed> $input
     *  @return array{errors: array<string, string>, user: array<string, mixed>|null}
     */
    public function registerOwner(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');
        $restaurantName = trim((string) ($input['restaurant_name'] ?? ''));
        $restaurantEmail = strtolower(trim((string) ($input['restaurant_email'] ?? '')));
        $phone = trim((string) ($input['phone'] ?? ''));
        $address = trim((string) ($input['address'] ?? ''));
        $errors = [];

        if ($name === '' || mb_strlen($name) > 120) {
            $errors['name'] = 'Enter your name using 120 characters or fewer.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid owner email address.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must contain at least 8 characters.';
        }
        if ($restaurantName === '' || mb_strlen($restaurantName) > 160) {
            $errors['restaurant_name'] = 'Enter a restaurant name using 160 characters or fewer.';
        }
        if (!filter_var($restaurantEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['restaurant_email'] = 'Enter a valid restaurant email address.';
        }
        if ($phone === '' || mb_strlen($phone) > 30) {
            $errors['phone'] = 'Enter a contact number using 30 characters or fewer.';
        }
        if ($address === '' || mb_strlen($address) > 500) {
            $errors['address'] = 'Enter the restaurant address using 500 characters or fewer.';
        }
        if ($errors !== []) {
            return ['errors' => $errors, 'user' => null];
        }
        if ($this->users->findByEmail($email) !== null) {
            return ['errors' => ['email' => 'An account already uses this email address.'], 'user' => null];
        }

        $database = Database::connection();
        $database->beginTransaction();

        try {
            $ownerUserId = $this->users->createOwner($name, $email, password_hash($password, PASSWORD_DEFAULT));
            $restaurantId = $this->restaurants->create($ownerUserId, $restaurantName, $restaurantEmail, $phone, $address);
            $this->users->assignRestaurant($ownerUserId, $restaurantId);
            $database->commit();

            return [
                'errors' => [],
                'user' => [
                    'id' => $ownerUserId,
                    'restaurant_id' => $restaurantId,
                    'role' => 'owner',
                    'name' => $name,
                    'email' => $email,
                    'status' => 'active',
                ],
            ];
        } catch (Throwable) {
            if ($database->inTransaction()) {
                $database->rollBack();
            }

            return ['errors' => ['general' => 'Unable to create your account. Please try again.'], 'user' => null];
        }
    }

    /** @return array<string, mixed>|null */
    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->users->findByEmail(strtolower(trim($email)));

        if ($user === null || $user['status'] !== 'active' || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        unset($user['password_hash']);

        return $user;
    }
}
