-- ==============================================================================
-- HEALTHY BITE — PRODUCTION DATABASE SCHEMA SCRIPT
-- Application: Healthy Bite – Digital Menu & Food Ordering System
-- Database Engine: MySQL 8.0+ / MariaDB 10.5+
-- Storage Engine: InnoDB
-- Default Charset / Collation: utf8mb4 / utf8mb4_unicode_ci
-- File: database/healthy_bite.sql
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. DATABASE INITIALIZATION
-- ------------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS healthy_bite
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE healthy_bite;

-- Disable foreign key checks during batch execution for clean initialization
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 2. TABLE DEFINITIONS (IN DEPENDENCY-SAFE ORDER)
-- ------------------------------------------------------------------------------

-- Table 1: admin (Platform Administration Master Entity)
-- Stores system-level administrative tiers and platform management groups.
DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: roles (Role-Based Access Control Master)
-- Defines operational roles across the platform (Super Admin, Owner, Manager, Staff).
DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(50) NOT NULL,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY roles_slug_unique (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3: users (User Authentication & Accounts)
-- Core user entity representing platform admins, restaurant owners, managers, and staff.
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id BIGINT UNSIGNED NULL,
    role_id BIGINT UNSIGNED NULL,
    restaurant_id BIGINT UNSIGNED NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY users_email_unique (email),
    KEY users_admin_id_index (admin_id),
    KEY users_role_id_index (role_id),
    KEY users_restaurant_id_index (restaurant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 4: restaurants (Multi-Tenant Restaurant Profiles)
-- Stores corporate business identity, branding, contact info, and platform approval status.
DROP TABLE IF EXISTS restaurants;
CREATE TABLE restaurants (
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
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY restaurants_owner_user_id_index (owner_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5: branches (Physical Outlet Locations)
-- Geographic branch outlets operated by a parent restaurant tenant.
DROP TABLE IF EXISTS branches;
CREATE TABLE branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    restaurant_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(160) NOT NULL,
    phone VARCHAR(30) NULL,
    address VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY branches_restaurant_id_index (restaurant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 6: categories (Menu Category Groupings)
-- Groups food dishes under categories (e.g. Salads, Smoothies, Mains).
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    restaurant_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY categories_restaurant_name_unique (restaurant_id, name),
    KEY categories_restaurant_id_index (restaurant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 7: food_items (Dishes & Menu Catalog)
-- Sellable menu items with base pricing, nutritional metrics, dietary flags, and availability.
DROP TABLE IF EXISTS food_items;
CREATE TABLE food_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(160) NOT NULL,
    description VARCHAR(1000) NULL,
    image VARCHAR(255) NULL,
    ingredients TEXT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    calories INT NULL,
    protein DECIMAL(8,2) NULL,
    carbs DECIMAL(8,2) NULL,
    fat DECIMAL(8,2) NULL,
    fiber_g DECIMAL(8,2) NULL,
    sugar_g DECIMAL(8,2) NULL,
    allergens VARCHAR(255) NULL,
    preparation_time INT NULL,
    spice_level VARCHAR(50) NULL DEFAULT 'medium',
    food_type VARCHAR(50) NOT NULL DEFAULT 'veg',
    serving_size VARCHAR(80) NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY food_items_category_id_index (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 8: food_variants (Portion & Size Variations)
-- Size/portion modifiers (e.g. Regular, Large Bowl) with additive price adjustments.
DROP TABLE IF EXISTS food_variants;
CREATE TABLE food_variants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    food_item_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(80) NOT NULL,
    price_adjustment DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY food_variants_food_item_id_index (food_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 9: food_customizations (Ingredient Add-ons & Extras)
-- Optional add-ons (e.g. Extra Avocado, Feta Cheese) with specific price adjustments.
DROP TABLE IF EXISTS food_customizations;
CREATE TABLE food_customizations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    food_item_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    price_adjustment DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY food_customizations_food_item_id_index (food_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 10: restaurant_tables (Physical Dining Seating)
-- Physical tables situated at a branch outlet for dining guests.
DROP TABLE IF EXISTS restaurant_tables;
CREATE TABLE restaurant_tables (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id BIGINT UNSIGNED NOT NULL,
    table_number VARCHAR(80) NOT NULL,
    capacity SMALLINT UNSIGNED NOT NULL DEFAULT 2,
    status ENUM('available', 'occupied', 'cleaning', 'out_of_service') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY restaurant_tables_branch_number_unique (branch_id, table_number),
    KEY restaurant_tables_branch_id_index (branch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 11: qr_tokens (Dynamic Table QR Tokens)
-- Cryptographically random QR tokens linked to tables for contactless menu access.
DROP TABLE IF EXISTS qr_tokens;
CREATE TABLE qr_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    restaurant_table_id BIGINT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY qr_tokens_token_unique (token),
    KEY qr_tokens_table_id_index (restaurant_table_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 12: customers (Guest Diner Profiles)
-- Diner identity records captured during the order placement flow.
DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NULL,
    email VARCHAR(190) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 13: orders (Order Tickets & Lifecycle)
-- Master order tickets tracking status, notes, subtotal, tax, and total billing amounts.
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id BIGINT UNSIGNED NOT NULL,
    customer_id BIGINT UNSIGNED NOT NULL,
    restaurant_table_id BIGINT UNSIGNED NOT NULL,
    order_number VARCHAR(32) NOT NULL,
    status ENUM('pending', 'accepted', 'preparing', 'ready', 'served', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    customer_note VARCHAR(500) NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY orders_order_number_unique (order_number),
    KEY orders_branch_status_index (branch_id, status),
    KEY orders_customer_id_index (customer_id),
    KEY orders_restaurant_table_id_index (restaurant_table_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 14: order_items (Order Line Items)
-- Immutable purchase snapshot of individual dishes ordered in a ticket.
DROP TABLE IF EXISTS order_items;
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    food_item_id BIGINT UNSIGNED NOT NULL,
    food_variant_id BIGINT UNSIGNED NULL,
    item_name VARCHAR(160) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    line_total DECIMAL(10,2) NOT NULL,
    customer_note VARCHAR(300) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY order_items_order_id_index (order_id),
    KEY order_items_food_item_id_index (food_item_id),
    KEY order_items_food_variant_id_index (food_variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 15: order_item_customizations (Selected Add-ons Bridge)
-- Associative M:N bridge mapping chosen extras to specific ordered item lines.
DROP TABLE IF EXISTS order_item_customizations;
CREATE TABLE order_item_customizations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_item_id BIGINT UNSIGNED NOT NULL,
    food_customization_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY oic_order_item_id_index (order_item_id),
    KEY oic_food_customization_id_index (food_customization_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 16: payments (Point-of-Sale & Settlement Records)
-- Financial settlement transactions for completed orders.
DROP TABLE IF EXISTS payments;
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method ENUM('cash', 'upi', 'card') NOT NULL,
    status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    transaction_reference VARCHAR(100) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY payments_order_id_index (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 17: reviews (Customer Ratings & Feedback)
-- Verified star ratings and comments linked to orders, dishes, tables, and restaurants.
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    restaurant_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NULL,
    food_item_id BIGINT UNSIGNED NULL,
    restaurant_table_id BIGINT UNSIGNED NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY reviews_customer_id_index (customer_id),
    KEY reviews_restaurant_id_index (restaurant_id),
    KEY reviews_order_id_index (order_id),
    KEY reviews_food_item_id_index (food_item_id),
    KEY reviews_restaurant_table_id_index (restaurant_table_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. FOREIGN KEY CONSTRAINTS (REFERENTIAL INTEGRITY)
-- ------------------------------------------------------------------------------

-- Users constraints
ALTER TABLE users
    ADD CONSTRAINT users_admin_id_foreign
        FOREIGN KEY (admin_id) REFERENCES admin (id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT users_role_id_foreign
        FOREIGN KEY (role_id) REFERENCES roles (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    ADD CONSTRAINT users_restaurant_id_foreign
        FOREIGN KEY (restaurant_id) REFERENCES restaurants (id)
        ON UPDATE CASCADE ON DELETE SET NULL;

-- Restaurants constraints
ALTER TABLE restaurants
    ADD CONSTRAINT restaurants_owner_user_id_foreign
        FOREIGN KEY (owner_user_id) REFERENCES users (id)
        ON UPDATE CASCADE ON DELETE SET NULL;

-- Branches constraints
ALTER TABLE branches
    ADD CONSTRAINT branches_restaurant_id_foreign
        FOREIGN KEY (restaurant_id) REFERENCES restaurants (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Categories constraints
ALTER TABLE categories
    ADD CONSTRAINT categories_restaurant_id_foreign
        FOREIGN KEY (restaurant_id) REFERENCES restaurants (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Food Items constraints
ALTER TABLE food_items
    ADD CONSTRAINT food_items_category_id_foreign
        FOREIGN KEY (category_id) REFERENCES categories (id)
        ON UPDATE CASCADE ON DELETE RESTRICT;

-- Food Variants constraints
ALTER TABLE food_variants
    ADD CONSTRAINT food_variants_food_item_id_foreign
        FOREIGN KEY (food_item_id) REFERENCES food_items (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Food Customizations constraints
ALTER TABLE food_customizations
    ADD CONSTRAINT food_customizations_food_item_id_foreign
        FOREIGN KEY (food_item_id) REFERENCES food_items (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Restaurant Tables constraints
ALTER TABLE restaurant_tables
    ADD CONSTRAINT restaurant_tables_branch_id_foreign
        FOREIGN KEY (branch_id) REFERENCES branches (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- QR Tokens constraints
ALTER TABLE qr_tokens
    ADD CONSTRAINT qr_tokens_table_id_foreign
        FOREIGN KEY (restaurant_table_id) REFERENCES restaurant_tables (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Orders constraints
ALTER TABLE orders
    ADD CONSTRAINT orders_branch_id_foreign
        FOREIGN KEY (branch_id) REFERENCES branches (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    ADD CONSTRAINT orders_customer_id_foreign
        FOREIGN KEY (customer_id) REFERENCES customers (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    ADD CONSTRAINT orders_table_id_foreign
        FOREIGN KEY (restaurant_table_id) REFERENCES restaurant_tables (id)
        ON UPDATE CASCADE ON DELETE RESTRICT;

-- Order Items constraints
ALTER TABLE order_items
    ADD CONSTRAINT order_items_order_id_foreign
        FOREIGN KEY (order_id) REFERENCES orders (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT order_items_food_item_id_foreign
        FOREIGN KEY (food_item_id) REFERENCES food_items (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    ADD CONSTRAINT order_items_food_variant_id_foreign
        FOREIGN KEY (food_variant_id) REFERENCES food_variants (id)
        ON UPDATE CASCADE ON DELETE SET NULL;

-- Order Item Customizations constraints
ALTER TABLE order_item_customizations
    ADD CONSTRAINT oic_order_item_id_foreign
        FOREIGN KEY (order_item_id) REFERENCES order_items (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT oic_food_customization_id_foreign
        FOREIGN KEY (food_customization_id) REFERENCES food_customizations (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Payments constraints
ALTER TABLE payments
    ADD CONSTRAINT payments_order_id_foreign
        FOREIGN KEY (order_id) REFERENCES orders (id)
        ON UPDATE CASCADE ON DELETE CASCADE;

-- Reviews constraints
ALTER TABLE reviews
    ADD CONSTRAINT reviews_customer_id_foreign
        FOREIGN KEY (customer_id) REFERENCES customers (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT reviews_restaurant_id_foreign
        FOREIGN KEY (restaurant_id) REFERENCES restaurants (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT reviews_order_id_foreign
        FOREIGN KEY (order_id) REFERENCES orders (id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT reviews_food_item_id_foreign
        FOREIGN KEY (food_item_id) REFERENCES food_items (id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT reviews_restaurant_table_id_foreign
        FOREIGN KEY (restaurant_table_id) REFERENCES restaurant_tables (id)
        ON UPDATE CASCADE ON DELETE SET NULL;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
