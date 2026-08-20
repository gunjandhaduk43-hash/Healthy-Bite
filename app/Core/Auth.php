<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\UserRepository;

final class Auth
{
    /** @var array<string, mixed>|null */
    private static ?array $user = null;

    public static function check(): bool
    {
        return isset($_SESSION['auth_user_id']) && self::user() !== null;
    }

    /** @return array<string, mixed>|null */
    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }

        $userId = $_SESSION['auth_user_id'] ?? null;

        if (!is_int($userId) && !ctype_digit((string) $userId)) {
            return null;
        }

        self::$user = (new UserRepository())->findActiveById((int) $userId);

        return self::$user;
    }

    /** @param array<string, mixed> $user */
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['auth_user_id'] = (int) $user['id'];
        self::$user = $user;
    }

    public static function logout(): void
    {
        unset($_SESSION['auth_user_id']);
        self::$user = null;
        session_regenerate_id(true);
    }
}
