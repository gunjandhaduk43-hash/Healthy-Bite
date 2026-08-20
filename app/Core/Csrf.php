<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    public static function validate(?string $token): bool
    {
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        return is_string($token) && $sessionToken !== '' && hash_equals($sessionToken, $token);
    }
}
