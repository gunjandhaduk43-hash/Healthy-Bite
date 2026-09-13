USE healthy_bite;

SET FOREIGN_KEY_CHECKS = 0;

-- Update restaurant profile to reflect universal multi-cuisine concept
UPDATE restaurants 
SET name = 'Healthy Bite Kitchen & Global Bistro',
    cuisine_type = 'Multi-Cuisine (Italian, Mexican, Indian, Japanese, American)',
    description = 'From wood-fired Neapolitan pizzas to vibrant Gujarati thalis, artisan ramen bowls, and guilt-free superfood smoothies. Crafted fresh with premium ingredients.',
    phone = '+91 98765 43210',
    address = '42 Gourmet Avenue, Central Boulevard',
    city = 'Mumbai',
    state = 'Maharashtra'
WHERE id = 1;

-- Seed Categories (Table 6: categories)
INSERT INTO categories (id, restaurant_id, name, sort_order, is_active) VALUES
(10, 1, 'Artisan Pizzas', 1, 1),
(11, 1, 'Mexican Street Food', 2, 1),
(12, 1, 'Authentic Indian & Gujarati', 3, 1),
(13, 1, 'Japanese Ramen & Bowls', 4, 1),
(14, 1, 'Gourmet Burgers', 5, 1),
(15, 1, 'Superfood Salads & Bowls', 6, 1),
(16, 1, 'Craft Beverages & Smoothies', 7, 1),
(17, 1, 'Artisan Desserts', 8, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name), sort_order=VALUES(sort_order), is_active=VALUES(is_active);

-- Seed Food Items (Table 7: food_items)
INSERT INTO food_items (id, category_id, name, description, ingredients, base_price, image, calories, protein, carbs, fat, allergens, preparation_time, spice_level, food_type, serving_size, is_available, is_featured) VALUES
-- Italian Pizza
(101, 10, 'Wood-Fired Margherita Basilico', 'San Marzano tomato sauce, fresh buffalo mozzarella, hand-picked sweet basil, and cold-pressed extra virgin olive oil on sourdough crust.', 'Sourdough, San Marzano Tomatoes, Buffalo Mozzarella, Fresh Basil, Extra Virgin Olive Oil', 349.00, 'https://images.unsplash.com/photo-1604382355076-af4b0eb60143?w=600&auto=format&fit=crop&q=80', 580, 22.00, 68.00, 24.00, 'Dairy, Gluten', 14, 'low', 'veg', '10 inch', 1, 1),
(102, 10, 'Truffle Mushroom & Wild Herb Pizza', 'Wild cremini and shiitake mushrooms, creamy fior di latte mozzarella, roasted garlic, and white truffle oil drizzle.', 'Flour, Cremini Mushrooms, Shiitake, Fior di Latte, Truffle Oil, Garlic, Thyme', 449.00, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&auto=format&fit=crop&q=80', 640, 24.00, 72.00, 28.00, 'Dairy, Gluten', 16, 'medium', 'veg', '10 inch', 1, 0),

-- Mexican
(103, 11, 'Fiesta Fajita Burrito Bowl', 'Cilantro-lime brown rice, spiced pinto beans, charred bell peppers, charred corn salsa, hand-mashed guacamole, and chipotle crema.', 'Brown Rice, Pinto Beans, Bell Peppers, Corn, Avocado, Lime, Chipotle, Cilantro', 329.00, 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?w=600&auto=format&fit=crop&q=80', 490, 16.00, 74.00, 15.00, 'None', 12, 'medium', 'vegan', '450g bowl', 1, 1),
(104, 11, 'Crispy Avocado & Black Bean Tacos', 'Three soft corn tortillas with crispy panko-crusted avocado, slow-cooked black beans, pickled red onions, and zesty salsa verde.', 'Corn Tortillas, Avocado, Black Beans, Pickled Onion, Jalapeño, Salsa Verde, Lime', 289.00, 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=600&auto=format&fit=crop&q=80', 420, 12.00, 56.00, 18.00, 'Gluten', 10, 'high', 'vegan', '3 tacos', 1, 0),

-- Indian / Gujarati
(105, 12, 'Royal Kathiyawadi & Gujarati Thali', 'Wholesome royal platter with Sev Tameta Shaak, Ringan No Olo, Gujarati Dal, Steamed Rice, 4 Phulka Rotis, Roasted Papad, and Sweet Shrikhand.', 'Eggplant, Tomato, Sev, Toor Dal, Rice, Wheat Flour, Yogurt, Cardamom, Ghee', 399.00, 'https://images.unsplash.com/photo-1610057099443-fde8c4d50f91?w=600&auto=format&fit=crop&q=80', 720, 26.00, 98.00, 22.00, 'Dairy, Gluten', 15, 'medium', 'veg', 'Full Thali (7 items)', 1, 1),
(106, 12, 'Mysore Masala Butter Dosa', 'Golden crispy fermented rice crepe layered with spicy red chili-garlic paste, spiced potato masala, served with 2 coconut chutneys and hot sambar.', 'Rice, Urad Dal, Potatoes, Red Chili, Garlic, Mustard Seeds, Curry Leaves, Butter', 219.00, 'https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=600&auto=format&fit=crop&q=80', 380, 9.00, 52.00, 14.00, 'Dairy, Mustard', 10, 'high', 'veg', '1 Large Dosa', 1, 0),

-- Japanese Ramen
(107, 13, 'Hokkaido Miso Shoyu Ramen', 'Slow-simmered rich kombu-miso broth, handmade wavy ramen noodles, soft-boiled marinated ajitsuke tamago, bamboo shoots, sweet corn, and toasted nori.', 'Ramen Noodles, Miso Paste, Kombu Broth, Marinated Egg, Sweet Corn, Bamboo Shoots, Scallions, Nori', 379.00, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600&auto=format&fit=crop&q=80', 540, 20.00, 78.00, 16.00, 'Egg, Soy, Gluten', 15, 'medium', 'veg', '650ml ramen bowl', 1, 1),

-- American Burger
(108, 14, 'Truffle & Mushroom Brioche Burger', 'Crispy plant protein patty, melted sharp cheddar cheese, sautéed balsamic mushrooms, baby arugula, and truffle aioli on toasted brioche bun.', 'Brioche Bun, Plant Patty, Cheddar Cheese, Mushrooms, Truffle Aioli, Arugula', 319.00, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop&q=80', 610, 28.00, 54.00, 32.00, 'Dairy, Gluten', 12, 'low', 'veg', 'Single Burger + Fries', 1, 1),

-- Salads
(109, 15, 'Mediterranean Quinoa Greek Crunch', 'Organic quinoa, crisp Persian cucumbers, heirloom cherry tomatoes, pitted kalamata olives, crumbled Greek feta, and lemon-oregano vinaigrette.', 'Quinoa, Cucumber, Heirloom Tomatoes, Kalamata Olives, Feta Cheese, Lemon, Extra Virgin Olive Oil', 299.00, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80', 340, 14.00, 36.00, 16.00, 'Dairy', 8, 'low', 'veg', '380g bowl', 1, 1),

-- Beverages
(110, 16, 'Cold-Pressed Green Detox Elixir', 'Fresh cold-pressed baby spinach, cucumber, green apple, fresh ginger root, celery, and cold-extracted mint.', 'Spinach, Persian Cucumber, Green Apple, Ginger, Celery, Fresh Mint', 169.00, 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=600&auto=format&fit=crop&q=80', 110, 3.00, 24.00, 0.50, 'None', 5, 'low', 'vegan', '350ml bottle', 1, 1),
(111, 16, 'Kesari Mango & Cardamom Lassi', 'Silky organic yogurt whisked with Alphonso mango pulp, green cardamom, and slivered pistachio nuts.', 'Alphonso Mango, Yogurt, Green Cardamom, Raw Cane Sugar, Pistachio', 149.00, 'https://images.unsplash.com/photo-1527661591475-527312dd65f5?w=600&auto=format&fit=crop&q=80', 210, 6.00, 38.00, 4.00, 'Dairy, Tree Nuts', 5, 'low', 'veg', '300ml glass', 1, 0),

-- Desserts
(112, 17, 'Warm Belgian Chocolate Lava Tart', 'Rich molten dark chocolate center, buttery cocoa crust, served with a scoop of Madagascar vanilla bean gelato.', 'Dark Chocolate (70%), Butter, Eggs, Flour, Madagascar Vanilla Gelato', 229.00, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80', 480, 7.00, 52.00, 28.00, 'Dairy, Egg, Gluten', 10, 'low', 'veg', '1 Tart with Gelato', 1, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), base_price=VALUES(base_price), image=VALUES(image), category_id=VALUES(category_id);

-- Seed Food Variants (Table 8: food_variants)
-- Pizza sizes
INSERT INTO food_variants (id, food_item_id, name, price_adjustment) VALUES
(1001, 101, 'Regular (8 inch - 4 Slices)', 0.00),
(1002, 101, 'Medium (10 inch - 6 Slices)', 120.00),
(1003, 101, 'Large (12 inch - 8 Slices)', 220.00),
(1004, 102, 'Medium (10 inch)', 0.00),
(1005, 102, 'Large (12 inch)', 180.00),

-- Burrito Bowl size
(1006, 103, 'Regular Portion', 0.00),
(1007, 103, 'Grande Super Bowl (+30% Protein)', 99.00),

-- Thali options
(1008, 105, 'Standard Thali', 0.00),
(1009, 105, 'Deluxe Thali (Includes Farsan & Basundi)', 110.00),

-- Ramen Bowl
(1010, 107, 'Standard Ramen', 0.00),
(1011, 107, 'Mega Chashu & Noodle Bowl', 85.00),

-- Burger
(1012, 108, 'Single Stack', 0.00),
(1013, 108, 'Double Stack Patty', 90.00)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_adjustment=VALUES(price_adjustment);

-- Seed Food Customizations (Table 9: food_customizations)
-- Pizza Add-ons & Crusts
INSERT INTO food_customizations (id, food_item_id, name, price_adjustment) VALUES
(2001, 101, 'Cheese Burst Stuffed Crust', 69.00),
(2002, 101, 'Extra Buffalo Mozzarella', 50.00),
(2003, 101, 'Sliced Black Olives & Jalapeños', 35.00),
(2004, 101, 'White Truffle Oil Drizzle', 45.00),
(2005, 102, 'Extra Shiitake Mushrooms', 55.00),
(2006, 102, 'Artisan Vegan Cheese', 40.00),

-- Mexican Bowl Add-ons
(2007, 103, 'Extra Fresh Guacamole Scoop', 60.00),
(2008, 103, 'Smoky Chipotle Cashew Queso', 45.00),
(2009, 103, 'Extra Charred Corn Salsa', 25.00),
(2010, 104, 'Spicy Habanero Salsa Dip', 20.00),

-- Indian / Gujarati Extras
(2011, 105, 'Extra Ghee Phulka Roti (2 pcs)', 30.00),
(2012, 105, 'Masala Buttermilk / Chaas Glass', 25.00),
(2013, 105, 'Kesar Shrikhand Cup', 40.00),
(2014, 106, 'Extra Cheese Shredding', 40.00),
(2015, 106, 'Extra Coconut Chutney & Sambar', 20.00),

-- Ramen Customizations
(2016, 107, 'Extra Ajitsuke Marinated Egg', 45.00),
(2017, 107, 'Extra Ramen Noodles Portion', 50.00),
(2018, 107, 'Spicy Togarashi Chili Crunch', 25.00),

-- Burger Add-ons
(2019, 108, 'Extra Sharp Melted Cheddar', 35.00),
(2020, 108, 'Caramelized Onion Jam', 25.00),
(2021, 108, 'Truffle Parmesan Fries Upgrade', 60.00)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_adjustment=VALUES(price_adjustment);

-- Ensure Table 1, Table 2, and Table 3 have clean active QR tokens
INSERT INTO restaurant_tables (id, branch_id, table_number, capacity, status) VALUES
(1, 1, 'Table 101', 4, 'available'),
(2, 1, 'Table 102', 2, 'occupied'),
(3, 1, 'Table 103', 6, 'available')
ON DUPLICATE KEY UPDATE table_number=VALUES(table_number), capacity=VALUES(capacity), status=VALUES(status);

INSERT INTO qr_tokens (id, restaurant_table_id, token, is_active) VALUES
(1, 1, 'hb_tok_9f82a1d04b9e28', 1),
(2, 2, 'hb_tok_6c11e48b70f4a3', 1),
(3, 3, 'hb_tok_3a77d291e0bc12', 1)
ON DUPLICATE KEY UPDATE token=VALUES(token), is_active=VALUES(is_active);

-- Ensure a glowing 5-star review exists for the restaurant
INSERT INTO reviews (id, customer_id, restaurant_id, rating, comment) VALUES
(1, 1, 1, 5, 'Phenomenal food quality! The Margherita sourdough pizza and Mysore dosa were unbelievable. Ultra-fast table ordering.')
ON DUPLICATE KEY UPDATE rating=VALUES(rating), comment=VALUES(comment);

SET FOREIGN_KEY_CHECKS = 1;
