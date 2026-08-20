<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=healthy_bite", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "--- USERS --- \n";
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);
    
    echo "\n--- RESTAURANTS --- \n";
    $stmt = $pdo->query("SELECT * FROM restaurants");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($restaurants);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
