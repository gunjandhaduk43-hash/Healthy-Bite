<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RestaurantRepository;

final class RestaurantService
{
    public function __construct(private readonly RestaurantRepository $restaurants = new RestaurantRepository())
    {
    }

    /** @param array<string, mixed> $input
     *  @return array<string, string>
     */
    public function updateProfile(int $restaurantId, int $ownerUserId, array $input): array
    {
        $data = [
            'name' => trim((string) ($input['name'] ?? '')),
            'email' => strtolower(trim((string) ($input['email'] ?? ''))),
            'phone' => trim((string) ($input['phone'] ?? '')),
            'address' => trim((string) ($input['address'] ?? '')),
            'city' => trim((string) ($input['city'] ?? '')),
            'state' => trim((string) ($input['state'] ?? '')),
            'cuisine_type' => trim((string) ($input['cuisine_type'] ?? '')),
            'description' => trim((string) ($input['description'] ?? '')),
        ];
        $errors = [];

        if ($data['name'] === '' || mb_strlen($data['name']) > 160) {
            $errors['name'] = 'Enter a restaurant name using 160 characters or fewer.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid restaurant email address.';
        }
        if ($data['phone'] === '' || mb_strlen($data['phone']) > 30) {
            $errors['phone'] = 'Enter a contact number using 30 characters or fewer.';
        }
        if ($data['address'] === '' || mb_strlen($data['address']) > 500) {
            $errors['address'] = 'Enter an address using 500 characters or fewer.';
        }
        if (mb_strlen($data['city']) > 120 || mb_strlen($data['state']) > 120 || mb_strlen($data['cuisine_type']) > 120 || mb_strlen($data['description']) > 1000) {
            $errors['general'] = 'One or more optional fields are too long.';
        }
        if ($errors !== []) {
            return $errors;
        }

        $this->restaurants->updateProfile($restaurantId, $ownerUserId, $data);

        return [];
    }
}
