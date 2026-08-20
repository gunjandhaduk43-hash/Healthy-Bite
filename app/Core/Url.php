<?php

declare(strict_types=1);

namespace App\Core;

final class Url
{
    public static function to(string $path = '/'): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = '';

        if (str_ends_with($scriptName, '.php')) {
            $scriptDirectory = str_replace('\\', '/', dirname($scriptName));
            $basePath = $scriptDirectory === '/' || $scriptDirectory === '.' ? '' : rtrim($scriptDirectory, '/');
        }

        return $basePath . '/' . ltrim($path, '/');
    }
}
