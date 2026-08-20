<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=healthy_bite", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("
        SELECT u.id AS user_id, u.name AS owner_name, u.email, r.id AS restaurant_id, r.name AS restaurant_name 
        FROM users u 
        LEFT JOIN restaurants r ON u.restaurant_id = r.id 
        WHERE u.email IN ('owner@healthybite.com', 'owner@healthybite.test', 'admin@healthybite.com')
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($results);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
