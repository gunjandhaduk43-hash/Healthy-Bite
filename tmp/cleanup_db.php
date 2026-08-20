<?php
require 'bootstrap.php';
$db = \App\Core\Database::connection();

try {
    // Drop restaurant_tables index/fk/column
    $cols = $db->query("DESCRIBE restaurant_tables")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('restaurant_id', $cols)) {
        try {
            $db->exec("ALTER TABLE restaurant_tables DROP FOREIGN KEY restaurant_tables_restaurant_id_foreign");
        } catch (\Throwable $e) {
            echo "Skipping FK drop: " . $e->getMessage() . "\n";
        }
        try {
            $db->exec("ALTER TABLE restaurant_tables DROP INDEX restaurant_tables_restaurant_name_unique");
        } catch (\Throwable $e) {
            echo "Skipping Index drop: " . $e->getMessage() . "\n";
        }
        $db->exec("ALTER TABLE restaurant_tables DROP COLUMN restaurant_id");
        echo "Dropped restaurant_id from restaurant_tables\n";
    } else {
        echo "restaurant_id already absent from restaurant_tables\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
