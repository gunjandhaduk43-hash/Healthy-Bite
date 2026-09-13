<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=healthy_bite', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$sql = file_get_contents(__DIR__ . '/../documentation/healthy_bite_sample_data.sql');
$pdo->exec($sql);
echo "healthy_bite_sample_data.sql executed successfully without errors!\n";

$counts = [];
$tables = [
    'admin', 'roles', 'users', 'restaurants', 'branches', 'categories',
    'food_items', 'food_variants', 'food_customizations', 'restaurant_tables',
    'qr_tokens', 'customers', 'orders', 'order_items', 'order_item_customizations',
    'payments', 'reviews'
];

foreach ($tables as $t) {
    $c = $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
    $counts[$t] = $c;
    echo " - Table $t: $c records\n";
}
