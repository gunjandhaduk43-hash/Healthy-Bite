<?php
$pdo = new PDO('mysql:host=localhost;dbname=healthy_bite', 'root', '');
$hash = password_hash('Admin@12345', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email IN ('admin@healthybite.test', 'admin@healthybite.com', 'owner@healthybite.test', 'owner@healthybite.com')");
$stmt->execute([$hash]);

echo "Updated " . $stmt->rowCount() . " rows in healthy_bite.\n";

// Verify
$stmt = $pdo->query("SELECT email, password_hash FROM users WHERE email = 'admin@healthybite.test'");
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify('Admin@12345', $user['password_hash'])) {
    echo "VERIFICATION PASSED: admin@healthybite.test can log in with Admin@12345!\n";
} else {
    echo "VERIFICATION FAILED!\n";
}

// Now do Internship Tracker database
try {
    $pdo2 = new PDO('mysql:host=localhost;dbname=internship_tracker', 'root', '');
    $hash_admin = password_hash('Admin@123', PASSWORD_BCRYPT);
    $hash_student = password_hash('Student@123', PASSWORD_BCRYPT);
    
    $stmt2 = $pdo2->prepare("UPDATE students SET password = ? WHERE email = 'admin@tracker.com'");
    $stmt2->execute([$hash_admin]);
    
    $stmt3 = $pdo2->prepare("UPDATE students SET password = ? WHERE email = 'demo@tracker.com'");
    $stmt3->execute([$hash_student]);
    
    echo "Updated internship_tracker credentials successfully!\n";
} catch (Exception $e) {
    echo "Internship Tracker DB error: " . $e->getMessage() . "\n";
}
