<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = __DIR__ . '/public' . $path;

if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'pdf'   => 'application/pdf',
        'ico'   => 'image/x-icon',
        'html'  => 'text/html',
        'json'  => 'application/json',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'otf'   => 'font/otf',
    ];
    
    $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';
    header("Content-Type: " . $contentType);
    readfile($file);
    exit;
}

require_once __DIR__ . '/public/index.php';
