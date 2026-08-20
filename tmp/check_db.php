<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "SUCCESSFULLY CONNECTED TO XAMPP MYSQL ON PORT 3307!\n";
    
    $stmt = $pdo->query("SHOW DATABASES LIKE 'healthy_bite'");
    $dbExists = $stmt->fetch();
    if ($dbExists) {
        echo "Database healthy_bite EXISTS!\n";
        $pdo->exec("USE healthy_bite");
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables in healthy_bite: " . implode(', ', $tables) . "\n";
    } else {
        echo "Database healthy_bite does not exist.\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
