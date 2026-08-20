<?php

declare(strict_types=1);

require 'bootstrap.php';
$db = \App\Core\Database::connection();

echo "Starting database migration for Healthy Bite Data Dictionary (Corrected Version)...\n";

try {
    // 1. Table: admin
    echo "Creating admin table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS admin (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Migrate/Rename roles to admin if roles exists
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('roles', $tables) && !in_array('admin', $tables)) {
        $db->exec("RENAME TABLE roles TO admin");
    }

    $db->exec("INSERT IGNORE INTO admin (id, name) VALUES 
        (1, 'Super Admin'),
        (2, 'Restaurant Owner'),
        (3, 'Branch Manager'),
        (4, 'Staff')");

    // 2. Table: user (users)
    echo "Updating users table...\n";
    $userCols = $db->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('role_id', $userCols) && !in_array('admin_id', $userCols)) {
        try {
            $db->exec("ALTER TABLE users DROP FOREIGN KEY fk_users_role");
        } catch (\Throwable $e) {}
        try {
            $db->exec("ALTER TABLE users DROP FOREIGN KEY users_role_id_foreign");
        } catch (\Throwable $e) {}
        $db->exec("ALTER TABLE users CHANGE COLUMN role_id admin_id BIGINT UNSIGNED NOT NULL");
    } elseif (!in_array('admin_id', $userCols)) {
        $db->exec("ALTER TABLE users ADD COLUMN admin_id BIGINT UNSIGNED NOT NULL DEFAULT 2 AFTER id");
    }
    
    // Add FK constraint if missing
    try {
        $db->exec("ALTER TABLE users ADD CONSTRAINT users_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES admin (id) ON UPDATE CASCADE ON DELETE RESTRICT");
    } catch (\Throwable $e) {}

    // 3. Table: restaurant (restaurants)
    echo "Checking restaurants table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS restaurants (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        owner_user_id BIGINT UNSIGNED NULL,
        name VARCHAR(160) NOT NULL,
        email VARCHAR(190) NULL,
        phone VARCHAR(30) NULL,
        address VARCHAR(500) NULL,
        city VARCHAR(120) NULL,
        state VARCHAR(120) NULL,
        cuisine_type VARCHAR(120) NULL,
        description VARCHAR(1000) NULL,
        approval_status ENUM('pending', 'approved', 'suspended') NOT NULL DEFAULT 'approved',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // 4. Table: branch (branches)
    echo "Updating branches table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS branches (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        restaurant_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(160) NOT NULL,
        phone VARCHAR(30) NULL,
        address VARCHAR(500) NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT branches_restaurant_id_foreign FOREIGN KEY (restaurant_id) REFERENCES restaurants (id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Ensure default branch for each restaurant
    $db->exec("INSERT IGNORE INTO branches (id, restaurant_id, name, phone, address)
               SELECT id, id, CONCAT(name, ' Main Branch'), phone, address FROM restaurants");

    // 5. Table: category (categories)
    echo "Checking categories table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS categories (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        restaurant_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(120) NOT NULL,
        sort_order INT UNSIGNED NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT categories_restaurant_id_foreign FOREIGN KEY (restaurant_id) REFERENCES restaurants (id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // 6. Table: food_item (food_items)
    echo "Updating food_items table...\n";
    $foodCols = $db->query("DESCRIBE food_items")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('price', $foodCols) && !in_array('base_price', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN price base_price DECIMAL(10, 2) NOT NULL");
    }
    if (in_array('calories_kcal', $foodCols) && !in_array('calories', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN calories_kcal calories INT NULL");
    }
    if (in_array('protein_g', $foodCols) && !in_array('protein', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN protein_g protein INT NULL");
    }
    if (in_array('carbohydrates_g', $foodCols) && !in_array('carbs', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN carbohydrates_g carbs INT NULL");
    }
    if (in_array('fat_g', $foodCols) && !in_array('fat', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN fat_g fat INT NULL");
    }
    if (in_array('preparation_minutes', $foodCols) && !in_array('preparation_time', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN preparation_minutes preparation_time INT NULL");
    }
    if (in_array('diet_type', $foodCols) && !in_array('food_type', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN diet_type food_type VARCHAR(50) NOT NULL DEFAULT 'veg'");
    }
    if (in_array('is_recommended', $foodCols) && !in_array('is_featured', $foodCols)) {
        $db->exec("ALTER TABLE food_items CHANGE COLUMN is_recommended is_featured TINYINT(1) NOT NULL DEFAULT 0");
    }

    // 7. Table: restaurant_table (restaurant_tables)
    echo "Updating restaurant_tables table...\n";
    $tableCols = $db->query("DESCRIBE restaurant_tables")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('branch_id', $tableCols)) {
        $db->exec("ALTER TABLE restaurant_tables ADD COLUMN branch_id BIGINT UNSIGNED NULL AFTER id");
        $db->exec("UPDATE restaurant_tables SET branch_id = restaurant_id");
        $db->exec("ALTER TABLE restaurant_tables MODIFY COLUMN branch_id BIGINT UNSIGNED NOT NULL");
    }
    if (in_array('table_name', $tableCols) && !in_array('table_number', $tableCols)) {
        $db->exec("ALTER TABLE restaurant_tables CHANGE COLUMN table_name table_number VARCHAR(80) NOT NULL");
    }

    // 8. Table: qr_token (qr_tokens)
    echo "Updating qr_tokens table...\n";
    $qrCols = $db->query("DESCRIBE qr_tokens")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('token_hash', $qrCols) && !in_array('token', $qrCols)) {
        $db->exec("ALTER TABLE qr_tokens CHANGE COLUMN token_hash token VARCHAR(255) NOT NULL");
    }
    if (!in_array('updated_at', $qrCols)) {
        $db->exec("ALTER TABLE qr_tokens ADD COLUMN updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

    // 9. Table: food_variant (food_variants)
    echo "Updating food_variants table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS food_variants (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        food_item_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(80) NOT NULL,
        price_adjustment DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_food_variants_food_item FOREIGN KEY (food_item_id) REFERENCES food_items (id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $variantCols = $db->query("DESCRIBE food_variants")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('price_differential', $variantCols) && !in_array('price_adjustment', $variantCols)) {
        $db->exec("ALTER TABLE food_variants CHANGE COLUMN price_differential price_adjustment DECIMAL(10, 2) NOT NULL DEFAULT 0.00");
    }

    // 10. Table: food_customization (food_customizations)
    echo "Updating food_customizations table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS food_customizations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        food_item_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(120) NOT NULL,
        price_adjustment DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_food_customizations_food_item FOREIGN KEY (food_item_id) REFERENCES food_items (id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $custCols = $db->query("DESCRIBE food_customizations")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('price', $custCols) && !in_array('price_adjustment', $custCols)) {
        $db->exec("ALTER TABLE food_customizations CHANGE COLUMN price price_adjustment DECIMAL(10, 2) NOT NULL DEFAULT 0.00");
    }

    // 11. Table: customer (customers)
    echo "Updating customers table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS customers (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        phone VARCHAR(30) NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // 12. Table: review (reviews)
    echo "Updating reviews table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS reviews (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        customer_id BIGINT UNSIGNED NOT NULL,
        restaurant_id BIGINT UNSIGNED NOT NULL,
        food_item_id BIGINT UNSIGNED NULL,
        restaurant_table_id BIGINT UNSIGNED NULL,
        rating INT NOT NULL,
        comment TEXT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_reviews_customer FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_reviews_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurants (id) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_reviews_food FOREIGN KEY (food_item_id) REFERENCES food_items (id) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $revCols = $db->query("DESCRIBE reviews")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('restaurant_table_id', $revCols)) {
        $db->exec("ALTER TABLE reviews ADD COLUMN restaurant_table_id BIGINT UNSIGNED NULL AFTER food_item_id");
        try {
            $db->exec("ALTER TABLE reviews ADD CONSTRAINT fk_reviews_table FOREIGN KEY (restaurant_table_id) REFERENCES restaurant_tables (id) ON DELETE SET NULL ON UPDATE CASCADE");
        } catch (\Throwable $e) {}
    }

    // 13. Table: order (orders)
    echo "Updating orders table...\n";
    $orderCols = $db->query("DESCRIBE orders")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('branch_id', $orderCols)) {
        $db->exec("ALTER TABLE orders ADD COLUMN branch_id BIGINT UNSIGNED NULL AFTER id");
        $db->exec("UPDATE orders SET branch_id = restaurant_id");
        $db->exec("ALTER TABLE orders MODIFY COLUMN branch_id BIGINT UNSIGNED NOT NULL");
    }
    if (!in_array('customer_id', $orderCols)) {
        $db->exec("ALTER TABLE orders ADD COLUMN customer_id BIGINT UNSIGNED NULL AFTER branch_id");
    }
    if (!in_array('restaurant_table_id', $orderCols)) {
        if (in_array('table_id', $orderCols)) {
            $db->exec("ALTER TABLE orders CHANGE COLUMN table_id restaurant_table_id BIGINT UNSIGNED NOT NULL");
        } else {
            $db->exec("ALTER TABLE orders ADD COLUMN restaurant_table_id BIGINT UNSIGNED NOT NULL AFTER customer_id");
        }
    }
    if (in_array('coupon_id', $orderCols)) {
        try {
            $db->exec("ALTER TABLE orders DROP FOREIGN KEY orders_coupon_id_foreign");
        } catch (\Throwable $e) {}
        try {
            $db->exec("ALTER TABLE orders DROP FOREIGN KEY fk_orders_coupon");
        } catch (\Throwable $e) {}
        $db->exec("ALTER TABLE orders DROP COLUMN coupon_id");
    }

    // 14. Table: order_item (order_items)
    echo "Updating order_items table...\n";
    $oiCols = $db->query("DESCRIBE order_items")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('food_variant_id', $oiCols)) {
        $db->exec("ALTER TABLE order_items ADD COLUMN food_variant_id BIGINT UNSIGNED NULL AFTER food_item_id");
        try {
            $db->exec("ALTER TABLE order_items ADD CONSTRAINT fk_order_items_variant FOREIGN KEY (food_variant_id) REFERENCES food_variants (id) ON DELETE SET NULL ON UPDATE CASCADE");
        } catch (\Throwable $e) {}
    }

    // 15. Table: order_item_customization (order_item_customizations)
    echo "Creating order_item_customizations table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS order_item_customizations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_item_id BIGINT UNSIGNED NOT NULL,
        food_customization_id BIGINT UNSIGNED NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_oic_order_item FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_oic_customization FOREIGN KEY (food_customization_id) REFERENCES food_customizations (id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // 16. Table: payment (payments)
    echo "Updating payments table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS payments (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        method ENUM('cash', 'upi', 'card') NOT NULL,
        status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Drop coupon table if exists
    echo "Dropping coupons table...\n";
    $db->exec("DROP TABLE IF EXISTS coupons");

    echo "Migration completed successfully!\n";

} catch (\Throwable $e) {
    echo "ERROR during migration: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
