USE healthy_bite;

-- 1. Seed Roles (Table 2: roles)
INSERT INTO roles (id, name, slug, description) VALUES
(1, 'Super Administrator', 'super_admin', 'Full platform access and restaurant approvals'),
(2, 'Restaurant Owner', 'owner', 'Manages restaurant profile, categories, menu, staff, and analytics'),
(3, 'Branch Manager', 'manager', 'Oversees operational floor and kitchen queues'),
(4, 'Kitchen & Floor Staff', 'staff', 'Live kitchen order fulfillment')
ON DUPLICATE KEY UPDATE name=VALUES(name), slug=VALUES(slug), description=VALUES(description);

-- 2. Seed Admin Platform Master (Table 1: admin)
INSERT INTO admin (id, name) VALUES
(1, 'Platform Super Admin'),
(2, 'Tenant Administrator'),
(3, 'Operations Admin'),
(4, 'Support Staff')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 3. Seed Demo Users (Table 3: users)
-- Super Admin (Password: Admin@12345)
INSERT INTO users (id, admin_id, role_id, restaurant_id, name, email, password_hash, status) VALUES
(1, 1, 1, NULL, 'Platform Super Admin', 'admin@healthybite.test', '$2y$10$p0b3.07zU703k5E/M0B7he4794uD8Jp0J7oU60K8yvGgYnQp3p9xO', 'active')
ON DUPLICATE KEY UPDATE name=VALUES(name), admin_id=VALUES(admin_id), role_id=VALUES(role_id);

-- Demo Owner User (Password: Admin@12345)
INSERT INTO users (id, admin_id, role_id, restaurant_id, name, email, password_hash, status) VALUES
(2, 2, 2, NULL, 'Demo Owner', 'owner@healthybite.test', '$2y$10$p0b3.07zU703k5E/M0B7he4794uD8Jp0J7oU60K8yvGgYnQp3p9xO', 'active')
ON DUPLICATE KEY UPDATE name=VALUES(name), admin_id=VALUES(admin_id), role_id=VALUES(role_id);

-- 4. Seed Demo Restaurant (Table 4: restaurants)
INSERT INTO restaurants (id, owner_user_id, name, email, phone, address, city, state, cuisine_type, description, approval_status) VALUES
(1, 2, 'Green Earth Bistro', 'info@greenearth.test', '555-0199', '123 Healthy Way', 'San Francisco', 'CA', 'Organic & Healthy', 'Fresh farm-to-table organic meals and juices.', 'approved')
ON DUPLICATE KEY UPDATE name=VALUES(name), owner_user_id=VALUES(owner_user_id);

UPDATE users SET restaurant_id = 1 WHERE id = 2;

-- 5. Seed Branch (Table 5: branches)
INSERT INTO branches (id, restaurant_id, name, phone, address) VALUES
(1, 1, 'Downtown Main Branch', '555-0199', '123 Healthy Way')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 6. Seed Categories (Table 6: categories)
INSERT INTO categories (id, restaurant_id, name, sort_order, is_active) VALUES
(1, 1, 'Salads & Greens', 1, 1),
(2, 1, 'Fresh Smoothies', 2, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 7. Seed Food Items (Table 7: food_items)
INSERT INTO food_items (id, category_id, name, description, ingredients, base_price, image, calories, protein, carbs, fat, allergens, preparation_time, spice_level, food_type, is_available, is_featured) VALUES
(1, 1, 'Avocado Kale Salad', 'A nutrient-dense bowl with chopped organic kale, creamy hass avocado, and virgin olive oil.', 'Organic Kale, Avocado, Cherry Tomatoes, Olive Oil', 14.50, 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd', 320, 8.00, 18.00, 22.00, 'None', 10, 'medium', 'veg', 1, 1),
(2, 2, 'Berry Protein Blast', 'High protein antioxidant smoothie blended with organic blueberries and almond milk.', 'Blueberries, Banana, Almond Milk, Whey Protein', 8.50, 'https://images.unsplash.com/photo-1553530666-ba11a7da3888', 250, 20.00, 35.00, 4.00, 'Almonds', 5, 'low', 'veg', 1, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 8. Seed Food Variants (Table 8: food_variants)
INSERT INTO food_variants (id, food_item_id, name, price_adjustment) VALUES
(1, 1, 'Regular', 0.00),
(2, 1, 'Large Bowl', 3.50)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 9. Seed Food Customizations (Table 9: food_customizations)
INSERT INTO food_customizations (id, food_item_id, name, price_adjustment) VALUES
(1, 1, 'Extra Avocado', 2.00),
(2, 1, 'Feta Cheese', 1.50)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 10. Seed Restaurant Table (Table 10: restaurant_tables)
INSERT INTO restaurant_tables (id, branch_id, table_number, status) VALUES
(1, 1, 'Table 101', 'available'),
(2, 1, 'Table 102', 'occupied')
ON DUPLICATE KEY UPDATE table_number=VALUES(table_number);

-- 11. Seed QR Token (Table 11: qr_tokens)
INSERT INTO qr_tokens (id, restaurant_table_id, token, is_active) VALUES
(1, 1, 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 1)
ON DUPLICATE KEY UPDATE token=VALUES(token);

-- 12. Seed Customer (Table 12: customers)
INSERT INTO customers (id, name, phone, email) VALUES
(1, 'Alice Smith', '555-1234', 'alice@example.com')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 13. Seed Review (Table 17: reviews)
INSERT INTO reviews (id, customer_id, restaurant_id, food_item_id, restaurant_table_id, rating, comment) VALUES
(1, 1, 1, 1, 1, 5, 'Amazing avocado kale salad! Super fresh ingredients.')
ON DUPLICATE KEY UPDATE comment=VALUES(comment);
