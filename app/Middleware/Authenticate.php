<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Url;

final class Authenticate
{
    public function handle(): void
    {
        if (Auth::check()) {
            return;
        }

        Flash::set('error', 'Please sign in to continue.');
        header('Location: ' . Url::to('/login'));
        exit;
    }
}
