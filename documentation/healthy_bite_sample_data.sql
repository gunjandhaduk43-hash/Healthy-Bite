-- ==============================================================================
-- HEALTHY BITE — RESEARCH PAPER SAMPLE DATA SCRIPT
-- Application: Healthy Bite – Digital Menu & Food Ordering System
-- Database: MySQL 8.0+ (InnoDB, utf8mb4)
-- File: documentation/healthy_bite_sample_data.sql
-- Note: Contains safe, fictional sample records for academic demonstration only.
-- ==============================================================================

USE healthy_bite;

-- Disable foreign key checks for clean sequential seeding
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Table: admin (Platform Administration Groups)
INSERT INTO admin (id, name, created_at, updated_at) VALUES
(1, 'Platform Super Admin', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(2, 'Tenant Administrator', '2026-08-01 09:00:00', '2026-08-01 09:00:00')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 2. Table: roles (RBAC Roles)
INSERT INTO roles (id, name, slug, description, created_at, updated_at) VALUES
(1, 'Super Admin', 'super_admin', 'Full platform access and system control', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(2, 'Restaurant Owner', 'owner', 'Restaurant administrator with full branch control', '2026-08-01 09:00:00', '2026-08-01 09:00:00'),
(3, 'Staff', 'staff', 'Kitchen and order service operations', '2026-08-01 09:00:00', '2026-08-01 09:00:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

-- 3. Table: users (User Accounts & Staff)
INSERT INTO users (id, admin_id, role_id, restaurant_id, name, email, password_hash, status, created_at, updated_at) VALUES
(1, 1, 1, NULL, 'Demo Admin', 'admin@healthybite.test', '$2y$10$e8w4kK4pT5Q4J2F0K7n5G.h8G5V7y9N2K1M4P3Q8L0R9T2Y1W5X6Z', 'active', '2026-08-01 10:00:00', '2026-08-01 10:00:00'),
(2, 2, 2, NULL, 'Demo Owner', 'owner@healthybite.test', '$2y$10$p0b3.07zU703k5E/M0B7he4794uD8Jp0J7oU60K8yvGgYnQp3p9xO', 'active', '2026-08-02 11:30:00', '2026-08-02 11:30:00'),
(3, NULL, 3, NULL, 'Demo Staff', 'staff@healthybite.test', '$2y$10$p0b3.07zU703k5E/M0B7he4794uD8Jp0J7oU60K8yvGgYnQp3p9xO', 'active', '2026-08-03 14:00:00', '2026-08-03 14:00:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), status=VALUES(status);

-- 4. Table: restaurants (Tenant Profiles)
INSERT INTO restaurants (id, owner_user_id, name, email, phone, address, city, state, cuisine_type, description, approval_status, created_at, updated_at) VALUES
(1, 2, 'Healthy Bite Demo Restaurant', 'restaurant@healthybite.test', '9000000000', '123 Green Avenue', 'San Francisco', 'CA', 'Organic & Healthy', 'Fresh farm-to-table organic meals and juices.', 'approved', '2026-08-02 11:30:00', '2026-08-02 11:30:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), owner_user_id=VALUES(owner_user_id);

-- Link staff and owner users to restaurant
UPDATE users SET restaurant_id = 1 WHERE id IN (2, 3);

-- 5. Table: branches (Physical Outlets)
INSERT INTO branches (id, restaurant_id, name, phone, address, created_at, updated_at) VALUES
(1, 1, 'Downtown Main Branch', '9000000001', '123 Green Avenue, Downtown', '2026-08-02 12:00:00', '2026-08-02 12:00:00'),
(2, 1, 'Uptown Express Outlet', '9000000002', '456 Wellness Boulevard, Uptown', '2026-08-05 09:00:00', '2026-08-05 09:00:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), address=VALUES(address);

-- 6. Table: categories (Menu Categories)
INSERT INTO categories (id, restaurant_id, name, sort_order, is_active, created_at, updated_at) VALUES
(1, 1, 'Salads & Bowls', 1, 1, '2026-08-02 12:30:00', '2026-08-02 12:30:00'),
(2, 1, 'Fresh Smoothies', 2, 1, '2026-08-02 12:30:00', '2026-08-02 12:30:00'),
(3, 1, 'Warm Grain Bowls', 3, 1, '2026-08-02 12:30:00', '2026-08-02 12:30:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), sort_order=VALUES(sort_order);

-- 7. Table: food_items (Dishes & Nutritional Values)
INSERT INTO food_items (id, category_id, name, description, image, ingredients, base_price, calories, protein, carbs, fat, fiber_g, sugar_g, allergens, preparation_time, spice_level, food_type, serving_size, is_available, is_featured, created_at, updated_at) VALUES
(1, 1, 'Greek Quinoa Crunch Salad', 'Fresh cucumbers, tomatoes, kalamata olives, quinoa, and feta.', '/images/greek_quinoa.jpg', 'Quinoa, Cucumber, Tomato, Olives, Feta, Olive Oil', 12.50, 340, 12.50, 28.00, 16.00, 6.50, 4.00, 'Dairy', 10, 'low', 'veg', '350g', 1, 1, '2026-08-02 13:00:00', '2026-08-02 13:00:00'),
(2, 2, 'Green Detox Glow Smoothie', 'Cold-pressed kale, green apple, ginger, cucumber, and chia.', '/images/green_detox.jpg', 'Kale, Green Apple, Cucumber, Ginger, Chia Seeds', 7.50, 180, 4.00, 34.00, 1.50, 5.00, 18.00, 'None', 5, 'medium', 'vegan', '400ml', 1, 1, '2026-08-02 13:15:00', '2026-08-02 13:15:00'),
(3, 3, 'Roasted Tofu Harvest Bowl', 'Organic baked tofu with roasted sweet potato and broccoli.', '/images/tofu_harvest.jpg', 'Tofu, Brown Rice, Sweet Potato, Broccoli, Tahini', 14.00, 450, 22.00, 52.00, 14.00, 8.00, 5.50, 'Soy, Sesame', 15, 'medium', 'vegan', '420g', 1, 0, '2026-08-02 13:30:00', '2026-08-02 13:30:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), base_price=VALUES(base_price);

-- 8. Table: food_variants (Portion & Sizing)
INSERT INTO food_variants (id, food_item_id, name, price_adjustment, created_at, updated_at) VALUES
(1, 1, 'Regular Bowl', 0.00, '2026-08-02 14:00:00', '2026-08-02 14:00:00'),
(2, 1, 'Large Power Bowl', 3.50, '2026-08-02 14:00:00', '2026-08-02 14:00:00'),
(3, 2, 'Mega Booster (650ml)', 2.50, '2026-08-02 14:05:00', '2026-08-02 14:05:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), price_adjustment=VALUES(price_adjustment);

-- 9. Table: food_customizations (Add-ons & Extras)
INSERT INTO food_customizations (id, food_item_id, name, price_adjustment, created_at, updated_at) VALUES
(1, 1, 'Extra Avocado Slice', 2.00, '2026-08-02 14:10:00', '2026-08-02 14:10:00'),
(2, 1, 'Organic Crumbled Feta', 1.50, '2026-08-02 14:10:00', '2026-08-02 14:10:00'),
(3, 3, 'Extra Tahini Drizzle', 1.00, '2026-08-02 14:20:00', '2026-08-02 14:20:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), price_adjustment=VALUES(price_adjustment);

-- 10. Table: restaurant_tables (Dining Tables)
INSERT INTO restaurant_tables (id, branch_id, table_number, capacity, status, created_at, updated_at) VALUES
(1, 1, 'T-01', 4, 'available', '2026-08-02 15:00:00', '2026-08-02 15:00:00'),
(2, 1, 'T-02', 2, 'occupied', '2026-08-02 15:00:00', '2026-08-02 15:00:00')
ON DUPLICATE KEY UPDATE table_number=VALUES(table_number), status=VALUES(status);

-- 11. Table: qr_tokens (Dynamic QR Tokens)
INSERT INTO qr_tokens (id, restaurant_table_id, token, expires_at, is_active, created_at, updated_at) VALUES
(1, 1, 'hb_tok_9f82a1d04b9e28', NULL, 1, '2026-08-02 15:30:00', '2026-08-02 15:30:00'),
(2, 2, 'hb_tok_6c11e48b70f4a3', NULL, 1, '2026-08-02 15:30:00', '2026-08-02 15:30:00')
ON DUPLICATE KEY UPDATE token=VALUES(token), is_active=VALUES(is_active);

-- 12. Table: customers (Guest Diners)
INSERT INTO customers (id, name, phone, email, created_at, updated_at) VALUES
(1, 'Sophia Chen', '9876543210', 'sophia.c@example.test', '2026-08-10 12:15:00', '2026-08-10 12:15:00'),
(2, 'Liam Thorne', '9876543211', 'liam.thorne@example.test', '2026-08-10 13:05:00', '2026-08-10 13:05:00')
ON DUPLICATE KEY UPDATE name=VALUES(name), phone=VALUES(phone);

-- 13. Table: orders (Order Tickets)
INSERT INTO orders (id, branch_id, customer_id, restaurant_table_id, order_number, status, customer_note, subtotal, tax_amount, total_amount, created_at, updated_at) VALUES
(1, 1, 1, 2, 'HB-20260810-001', 'completed', 'Dressing on the side please.', 20.00, 1.00, 21.00, '2026-08-10 12:20:00', '2026-08-10 12:45:00'),
(2, 1, 2, 1, 'HB-20260810-002', 'preparing', 'No ice in smoothie.', 21.50, 1.08, 22.58, '2026-08-10 13:10:00', '2026-08-10 13:12:00')
ON DUPLICATE KEY UPDATE status=VALUES(status), total_amount=VALUES(total_amount);

-- 14. Table: order_items (Ordered Dishes)
INSERT INTO order_items (id, order_id, food_item_id, food_variant_id, item_name, unit_price, quantity, line_total, customer_note, created_at) VALUES
(1, 1, 1, 2, 'Greek Quinoa Crunch Salad (Large Power Bowl)', 16.00, 1, 18.00, 'Extra dressing container', '2026-08-10 12:20:00'),
(2, 1, 2, NULL, 'Green Detox Glow Smoothie', 7.50, 1, 7.50, NULL, '2026-08-10 12:20:00'),
(3, 2, 3, NULL, 'Roasted Tofu Harvest Bowl', 14.00, 1, 15.00, 'Warm bowl please', '2026-08-10 13:10:00')
ON DUPLICATE KEY UPDATE line_total=VALUES(line_total);

-- 15. Table: order_item_customizations (Selected Add-ons Bridge)
INSERT INTO order_item_customizations (id, order_item_id, food_customization_id, created_at) VALUES
(1, 1, 1, '2026-08-10 12:20:00'),
(2, 3, 3, '2026-08-10 13:10:00')
ON DUPLICATE KEY UPDATE food_customization_id=VALUES(food_customization_id);

-- 16. Table: payments (Settlement Records)
INSERT INTO payments (id, order_id, amount, method, status, transaction_reference, created_at, updated_at) VALUES
(1, 1, 21.00, 'upi', 'completed', 'UPI-TXN-20260810-9842', '2026-08-10 12:45:00', '2026-08-10 12:45:00'),
(2, 2, 22.58, 'card', 'pending', 'CARD-AUTH-90214', '2026-08-10 13:10:00', '2026-08-10 13:10:00')
ON DUPLICATE KEY UPDATE status=VALUES(status), amount=VALUES(amount);

-- 17. Table: reviews (Customer Feedback & Ratings)
INSERT INTO reviews (id, customer_id, restaurant_id, order_id, food_item_id, restaurant_table_id, rating, comment, created_at, updated_at) VALUES
(1, 1, 1, 1, 1, 2, 5, 'The Greek Quinoa Salad with extra avocado was exceptionally fresh and filling!', '2026-08-10 13:00:00', '2026-08-10 13:00:00')
ON DUPLICATE KEY UPDATE rating=VALUES(rating), comment=VALUES(comment);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
