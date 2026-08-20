USE healthy_bite;

-- Seed Admin roles
INSERT INTO admin (id, name) VALUES
(1, 'Super Admin'),
(2, 'Restaurant Owner'),
(3, 'Branch Manager'),
(4, 'Staff')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Demo Owner User
INSERT INTO users (id, admin_id, restaurant_id, name, email, password_hash, status) VALUES
(1, 2, 1, 'Demo Owner', 'owner@healthybite.test', '$2y$10$wN8oY1M3GqO...mockhash', 'active')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Demo Restaurant
INSERT INTO restaurants (id, owner_user_id, name, email, phone, address, city, state, cuisine_type, description, approval_status) VALUES
(1, 1, 'Green Earth Bistro', 'info@greenearth.test', '555-0199', '123 Healthy Way', 'San Francisco', 'CA', 'Organic & Healthy', 'Fresh farm-to-table organic meals and juices.', 'approved')
ON DUPLICATE KEY UPDATE name=VALUES(name);

UPDATE users SET restaurant_id = 1 WHERE id = 1;

-- Seed Branch
INSERT INTO branches (id, restaurant_id, name, phone, address) VALUES
(1, 1, 'Downtown Main Branch', '555-0199', '123 Healthy Way')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Categories
INSERT INTO categories (id, restaurant_id, name, sort_order, is_active) VALUES
(1, 1, 'Salads & Greens', 1, 1),
(2, 1, 'Fresh Smoothies', 2, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Food Items
INSERT INTO food_items (id, category_id, name, ingredients, base_price, image, calories, protein, carbs, fat, allergens, preparation_time, spice_level, food_type, is_available, is_featured) VALUES
(1, 1, 'Avocado Kale Salad', 'Organic Kale, Avocado, Cherry Tomatoes, Olive Oil', 14.50, 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd', 320, 8, 18, 22, 'None', 10, 'Mild', 'veg', 1, 1),
(2, 2, 'Berry Protein Blast', 'Blueberries, Banana, Almond Milk, Whey Protein', 8.50, 'https://images.unsplash.com/photo-1553530666-ba11a7da3888', 250, 20, 35, 4, 'Almonds', 5, 'None', 'veg', 1, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Food Variants
INSERT INTO food_variants (id, food_item_id, name, price_adjustment) VALUES
(1, 1, 'Regular', 0.00),
(2, 1, 'Large Bowl', 3.50)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Food Customizations
INSERT INTO food_customizations (id, food_item_id, name, price_adjustment) VALUES
(1, 1, 'Extra Avocado', 2.00),
(2, 1, 'Feta Cheese', 1.50)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Restaurant Table
INSERT INTO restaurant_tables (id, branch_id, table_number, status) VALUES
(1, 1, 'Table 101', 'available'),
(2, 1, 'Table 102', 'occupied')
ON DUPLICATE KEY UPDATE table_number=VALUES(table_number);

-- Seed QR Token
INSERT INTO qr_tokens (id, restaurant_table_id, token, is_active) VALUES
(1, 1, 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 1)
ON DUPLICATE KEY UPDATE token=VALUES(token);

-- Seed Customer
INSERT INTO customers (id, name, phone) VALUES
(1, 'Alice Smith', '555-1234')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Seed Review
INSERT INTO reviews (id, customer_id, restaurant_id, food_item_id, restaurant_table_id, rating, comment) VALUES
(1, 1, 1, 1, 1, 5, 'Amazing avocado kale salad! Super fresh ingredients.')
ON DUPLICATE KEY UPDATE comment=VALUES(comment);
