<?php

declare(strict_types=1);

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=healthy_bite', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $seeder1 = file_get_contents(__DIR__ . '/../database/seeders/001_demo_restaurant_admin.sql');
    $pdo->exec($seeder1);
    echo "Seeder 1 (001_demo_restaurant_admin.sql) applied successfully.\n";

    $seeder2 = file_get_contents(__DIR__ . '/../database/seeders/002_demo_staff.sql');
    $pdo->exec($seeder2);
    echo "Seeder 2 (002_demo_staff.sql) applied successfully.\n";

    $seeder3 = file_get_contents(__DIR__ . '/../database/seeders/003_multicuisine_sample_data.sql');
    $pdo->exec($seeder3);
    echo "Seeder 3 (003_multicuisine_sample_data.sql) applied successfully.\n";

} catch (Exception $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
}
