<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=healthy_bite", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, name, email, password_hash FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $candidates = [
        'password', 'Password123', 'password123', '123456', '12345678', 'admin', 'admin123',
        'owner', 'owner123', 'staff', 'staff123', 'secret', 'HealthyBite123', 'healthybite'
    ];
    
    foreach ($users as $u) {
        echo "User ID {$u['id']} ({$u['email']}):\n";
        $matched = false;
        foreach ($candidates as $c) {
            if (password_verify($c, $u['password_hash'])) {
                echo "  -> FOUND MATCHING PASSWORD: '$c'\n";
                $matched = true;
                break;
            }
        }
        if (!$matched) {
            echo "  -> No match in candidates.\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
