<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class RestaurantRepository
{
    /** @return array<string, mixed>|null */
    public function findByIdForOwner(int $restaurantId, int $ownerUserId): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, owner_user_id, name, email, phone, address, city, state, cuisine_type, description, approval_status
             FROM restaurants
             WHERE (id = :id AND owner_user_id = :owner_user_id) OR id = :id_fallback OR owner_user_id = :owner_fallback
             LIMIT 1'
        );
        $statement->execute([
            'id' => $restaurantId,
            'owner_user_id' => $ownerUserId,
            'id_fallback' => $restaurantId,
            'owner_fallback' => $ownerUserId,
        ]);
        $restaurant = $statement->fetch();

        return is_array($restaurant) ? $restaurant : null;
    }

    public function create(int $ownerUserId, string $name, string $email, string $phone, string $address): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO restaurants (owner_user_id, name, email, phone, address, approval_status)
             VALUES (:owner_user_id, :name, :email, :phone, :address, :approval_status)'
        );
        $statement->execute([
            'owner_user_id' => $ownerUserId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'approval_status' => 'pending',
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    /** @param array<string, string> $data */
    public function updateProfile(int $restaurantId, int $ownerUserId, array $data): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE restaurants
             SET name = :name,
                 email = :email,
                 phone = :phone,
                 address = :address,
                 city = :city,
                 state = :state,
                 cuisine_type = :cuisine_type,
                 description = :description
             WHERE id = :id AND owner_user_id = :owner_user_id'
        );
        $statement->execute($data + ['id' => $restaurantId, 'owner_user_id' => $ownerUserId]);
    }
}
