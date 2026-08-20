<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Url;

final class GuestOnly
{
    public function handle(): void
    {
        if (!Auth::check()) {
            return;
        }

        header('Location: ' . Url::to('/dashboard'));
        exit;
    }
}
