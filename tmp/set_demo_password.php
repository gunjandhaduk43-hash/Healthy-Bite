<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=healthy_bite", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $password = 'Owner@123';
    $hash = password_hash($password, PASSWORD_BCRYPT);
    
    // Update owner@healthybite.com (Owner)
    $stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email IN ('owner@healthybite.com', 'owner@healthybite.test')");
    $stmt->execute(['hash' => $hash]);
    
    // Update admin@healthybite.com (Super Admin)
    $adminPassword = 'Admin@123';
    $adminHash = password_hash($adminPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email IN ('admin@healthybite.com', 'admin@healthybite.test')");
    $stmt->execute(['hash' => $adminHash]);
    
    echo "Demo user passwords successfully updated!\n";
    echo "Owner Email: owner@healthybite.com | Password: Owner@123\n";
    echo "Super Admin Email: admin@healthybite.com | Password: Admin@123\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
