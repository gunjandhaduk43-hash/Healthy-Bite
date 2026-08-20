<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Url;

final class OwnerOnly
{
    public function handle(): void
    {
        $user = Auth::user();

        if ($user !== null && $user['role'] === 'owner' && !empty($user['restaurant_id'])) {
            return;
        }

        Flash::set('error', 'Restaurant owner access is required.');
        header('Location: ' . Url::to('/login'));
        exit;
    }
}
