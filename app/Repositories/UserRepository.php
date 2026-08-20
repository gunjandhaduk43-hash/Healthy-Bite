<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class UserRepository
{
    /** @return array<string, mixed>|null */
    public function findByEmail(string $email): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT u.id, u.restaurant_id, 
                    CASE WHEN u.admin_id = 1 THEN "superadmin" WHEN u.admin_id = 2 THEN "owner" WHEN u.admin_id = 3 THEN "manager" ELSE "staff" END AS role, 
                    u.name, u.email, u.password_hash, u.status
             FROM users u
             JOIN admin a ON u.admin_id = a.id
             WHERE u.email = :email
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    /** @return array<string, mixed>|null */
    public function findActiveById(int $userId): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT u.id, u.restaurant_id, 
                    CASE WHEN u.admin_id = 1 THEN "superadmin" WHEN u.admin_id = 2 THEN "owner" WHEN u.admin_id = 3 THEN "manager" ELSE "staff" END AS role, 
                    u.name, u.email, u.status
             FROM users u
             JOIN admin a ON u.admin_id = a.id
             WHERE u.id = :id AND u.status = :status
             LIMIT 1'
        );
        $statement->execute(['id' => $userId, 'status' => 'active']);
        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    public function createOwner(string $name, string $email, string $passwordHash): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO users (name, email, password_hash, admin_id, status)
             VALUES (:name, :email, :password_hash, 2, :status)'
        );
        $statement->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'status' => 'active',
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function assignRestaurant(int $userId, int $restaurantId): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE users SET restaurant_id = :restaurant_id WHERE id = :id'
        );
        $statement->execute(['restaurant_id' => $restaurantId, 'id' => $userId]);
    }

    /** @return list<array<string, mixed>> */
    public function findStaffByRestaurant(int $restaurantId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, name, email, status, created_at
             FROM users
             WHERE restaurant_id = :restaurant_id AND (admin_id = 3 OR admin_id = 4)
             ORDER BY name'
        );
        $statement->execute(['restaurant_id' => $restaurantId]);
        return $statement->fetchAll();
    }

    public function createStaff(int $restaurantId, string $name, string $email, string $passwordHash): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO users (restaurant_id, name, email, password_hash, admin_id, status)
             VALUES (:restaurant_id, :name, :email, :password_hash, 4, "active")'
        );
        $statement->execute([
            'restaurant_id' => $restaurantId,
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public function toggleStaffStatus(int $restaurantId, int $staffId): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE users SET status = IF(status = "active", "inactive", "active")
             WHERE id = :id AND restaurant_id = :restaurant_id AND (admin_id = 3 OR admin_id = 4)'
        );
        $statement->execute(['id' => $staffId, 'restaurant_id' => $restaurantId]);
    }
}
