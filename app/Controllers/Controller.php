<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Url;
use App\Core\View;

abstract class Controller
{
    /** @param array<string, mixed> $data */
    protected function view(string $template, array $data = [], string $layout = 'app'): void
    {
        View::render($template, $data, $layout);
    }

    protected function redirect(string $path): never
    {
        if (defined('TEST_MODE')) {
            throw new \RuntimeException("Redirect to: " . $path);
        }
        header('Location: ' . Url::to($path));
        exit;
    }
}
