<?php
require 'bootstrap.php';
$db = \App\Core\Database::connection();
$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "=== TABLES IN DATABASE ===\n";
foreach ($tables as $t) {
    echo "- $t\n";
    $columns = $db->query("DESCRIBE `$t`")->fetchAll();
    foreach ($columns as $c) {
        echo "  - {$c['Field']} ({$c['Type']})\n";
    }
}
