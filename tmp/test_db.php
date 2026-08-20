<?php
$passwords = ['', 'root', 'admin', 'password', '123456', '1234', 'mysql', 'root123', 'root1234'];
foreach ($passwords as $pwd) {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', $pwd);
        echo "Success: Connected with password '$pwd'!\n";
        exit(0);
    } catch (Exception $e) {
        echo "Failed with password '$pwd': " . $e->getMessage() . "\n";
    }
}
