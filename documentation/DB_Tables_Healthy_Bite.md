# 6. DB/Tables

This section documents the relational database design for **Healthy Bite – Digital Menu & Food Ordering System**. The database schema consists of **exactly 17 normalized tables** implemented in MySQL 8.0 (InnoDB) in Third Normal Form (3NF).

---

### 1. Admin:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique administration level identifier |
| `name` | VARCHAR(120) | - | NO | - | Admin group name (e.g., 'Platform Super Admin') |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | name | created_at | updated_at |
| :--- | :--- | :--- | :--- |
| 1 | Platform Super Admin | 2026-08-01 09:00:00 | 2026-08-01 09:00:00 |
| 2 | Tenant Administrator | 2026-08-01 09:00:00 | 2026-08-01 09:00:00 |

---

### 2. Roles:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique role identifier |
| `name` | VARCHAR(80) | - | NO | - | Display title of the role |
| `slug` | VARCHAR(50) | UNI | NO | - | Unique machine slug (`super_admin`, `owner`, `staff`) |
| `description` | VARCHAR(255) | - | YES | NULL | Explanation of role permissions and access level |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | name | slug | description | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Super Admin | super_admin | Full platform access and system control | 2026-08-01 09:00:00 | 2026-08-01 09:00:00 |
| 2 | Restaurant Owner | owner | Restaurant administrator with full branch control | 2026-08-01 09:00:00 | 2026-08-01 09:00:00 |
| 3 | Staff | staff | Kitchen and order service operations | 2026-08-01 09:00:00 | 2026-08-01 09:00:00 |

---

### 3. Users:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique user account ID |
| `admin_id` | BIGINT UNSIGNED | FK | YES | NULL | References `admin(id)` (ON DELETE SET NULL) |
| `role_id` | BIGINT UNSIGNED | FK | YES | NULL | References `roles(id)` (ON DELETE RESTRICT) |
| `restaurant_id` | BIGINT UNSIGNED | FK | YES | NULL | References `restaurants(id)` (ON DELETE SET NULL) |
| `name` | VARCHAR(120) | - | NO | - | Full operational/account name |
| `email` | VARCHAR(190) | UNI | NO | - | Unique login email address |
| `password_hash` | VARCHAR(255) | - | NO | - | Secure BCrypt password hash |
| `status` | ENUM('active', 'inactive') | - | NO | 'active' | User account state |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Account registration timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last profile update timestamp |

#### Sample Database Records:
| id | admin_id | role_id | restaurant_id | name | email | password_hash | status | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | 1 | NULL | Demo Admin | admin@healthybite.test | [HASHED_PASSWORD] | active | 2026-08-01 10:00:00 | 2026-08-01 10:00:00 |
| 2 | 2 | 2 | 1 | Demo Owner | owner@healthybite.test | [HASHED_PASSWORD] | active | 2026-08-02 11:30:00 | 2026-08-02 11:30:00 |
| 3 | NULL | 3 | 1 | Demo Staff | staff@healthybite.test | [HASHED_PASSWORD] | active | 2026-08-03 14:00:00 | 2026-08-03 14:00:00 |

---

### 4. Restaurants:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique restaurant tenant ID |
| `owner_user_id` | BIGINT UNSIGNED | FK | YES | NULL | References `users(id)` (ON DELETE SET NULL) |
| `name` | VARCHAR(160) | - | NO | - | Registered commercial restaurant name |
| `email` | VARCHAR(190) | - | YES | NULL | Business contact email |
| `phone` | VARCHAR(30) | - | YES | NULL | Business contact phone number |
| `address` | VARCHAR(500) | - | YES | NULL | Registered street address |
| `city` | VARCHAR(120) | - | YES | NULL | City |
| `state` | VARCHAR(120) | - | YES | NULL | State / Region |
| `cuisine_type` | VARCHAR(120) | - | YES | NULL | Cuisine category (e.g., 'Organic & Healthy') |
| `description` | VARCHAR(1000) | - | YES | NULL | Public description and profile overview |
| `approval_status`| ENUM('pending', 'approved', 'suspended') | - | NO | 'approved' | Restaurant platform approval status |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Registration timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | owner_user_id | name | email | phone | address | city | state | cuisine_type | description | approval_status | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 2 | Healthy Bite Demo Restaurant | restaurant@healthybite.test | 9000000000 | 123 Green Avenue | San Francisco | CA | Organic & Healthy | Fresh farm-to-table organic meals and juices. | approved | 2026-08-02 11:30:00 | 2026-08-02 11:30:00 |

---

### 5. Branches:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique branch identifier |
| `restaurant_id` | BIGINT UNSIGNED | FK | NO | - | References `restaurants(id)` (ON DELETE CASCADE) |
| `name` | VARCHAR(160) | - | NO | - | Branch outlet title (e.g. 'Downtown Main Branch') |
| `phone` | VARCHAR(30) | - | YES | NULL | Branch direct contact phone |
| `address` | VARCHAR(500) | - | YES | NULL | Physical branch location address |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | restaurant_id | name | phone | address | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | Downtown Main Branch | 9000000001 | 123 Green Avenue, Downtown | 2026-08-02 12:00:00 | 2026-08-02 12:00:00 |
| 2 | 1 | Uptown Express Outlet | 9000000002 | 456 Wellness Boulevard, Uptown | 2026-08-05 09:00:00 | 2026-08-05 09:00:00 |

---

### 6. Categories:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique category identifier |
| `restaurant_id` | BIGINT UNSIGNED | FK | NO | - | References `restaurants(id)` (ON DELETE CASCADE) |
| `name` | VARCHAR(120) | UNI | NO | - | Category display name (unique per restaurant) |
| `sort_order` | INT UNSIGNED | - | NO | 0 | Display sequence order |
| `is_active` | TINYINT(1) | - | NO | 1 | Visibility toggle (1=active, 0=hidden) |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | restaurant_id | name | sort_order | is_active | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | Salads & Bowls | 1 | 1 | 2026-08-02 12:30:00 | 2026-08-02 12:30:00 |
| 2 | 1 | Fresh Smoothies | 2 | 1 | 2026-08-02 12:30:00 | 2026-08-02 12:30:00 |
| 3 | 1 | Warm Grain Bowls | 3 | 1 | 2026-08-02 12:30:00 | 2026-08-02 12:30:00 |

---

### 7. Food Items:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique dish identifier |
| `category_id` | BIGINT UNSIGNED | FK | NO | - | References `categories(id)` (ON DELETE RESTRICT) |
| `name` | VARCHAR(160) | - | NO | - | Dish commercial name |
| `description` | VARCHAR(1000) | - | YES | NULL | Detailed culinary description |
| `image` | VARCHAR(255) | - | YES | NULL | Image asset URL or file path |
| `ingredients` | TEXT | - | YES | NULL | Ingredients list |
| `base_price` | DECIMAL(10,2) | - | NO | - | Base selling price |
| `calories` | INT | - | YES | NULL | Energy value in calories (kcal) |
| `protein` | DECIMAL(8,2) | - | YES | NULL | Protein amount (grams) |
| `carbs` | DECIMAL(8,2) | - | YES | NULL | Carbohydrates amount (grams) |
| `fat` | DECIMAL(8,2) | - | YES | NULL | Total fat amount (grams) |
| `fiber_g` | DECIMAL(8,2) | - | YES | NULL | Dietary fiber amount (grams) |
| `sugar_g` | DECIMAL(8,2) | - | YES | NULL | Sugar content (grams) |
| `allergens` | VARCHAR(255) | - | YES | NULL | Allergen warnings (e.g. 'Dairy, Soy') |
| `preparation_time`| INT | - | YES | NULL | Estimated kitchen prep duration (minutes) |
| `spice_level` | VARCHAR(50) | - | YES | 'medium' | Spice indicator (`low`, `medium`, `high`, `none`) |
| `food_type` | VARCHAR(50) | - | NO | 'veg' | Dietary classification (`veg`, `non_veg`, `vegan`, `jain`) |
| `serving_size` | VARCHAR(80) | - | YES | NULL | Serving measure (e.g. '350g', '400ml') |
| `is_available` | TINYINT(1) | - | NO | 1 | Stock availability (1=available, 0=out of stock) |
| `is_featured` | TINYINT(1) | - | NO | 0 | Highlight toggle (1=featured/bestseller, 0=standard) |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | category_id | name | description | base_price | calories | protein | carbs | fat | fiber_g | sugar_g | allergens | food_type | is_available |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | Greek Quinoa Crunch Salad | Fresh cucumbers, tomatoes, kalamata olives, quinoa, and feta. | 12.50 | 340 | 12.50 | 28.00 | 16.00 | 6.50 | 4.00 | Dairy | veg | 1 |
| 2 | 2 | Green Detox Glow Smoothie | Cold-pressed kale, green apple, ginger, cucumber, and chia. | 7.50 | 180 | 4.00 | 34.00 | 1.50 | 5.00 | 18.00 | None | vegan | 1 |
| 3 | 3 | Roasted Tofu Harvest Bowl | Organic baked tofu with roasted sweet potato and broccoli. | 14.00 | 450 | 22.00 | 52.00 | 14.00 | 8.00 | 5.50 | Soy, Sesame | vegan | 1 |

---

### 8. Food Variants:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique variant identifier |
| `food_item_id` | BIGINT UNSIGNED | FK | NO | - | References `food_items(id)` (ON DELETE CASCADE) |
| `name` | VARCHAR(80) | - | NO | - | Variant portion title (e.g. 'Large Power Bowl') |
| `price_adjustment`| DECIMAL(10,2)| - | NO | 0.00 | Incremental price difference from base item |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | food_item_id | name | price_adjustment | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | Regular Bowl | 0.00 | 2026-08-02 14:00:00 | 2026-08-02 14:00:00 |
| 2 | 1 | Large Power Bowl | 3.50 | 2026-08-02 14:00:00 | 2026-08-02 14:00:00 |
| 3 | 2 | Mega Booster (650ml) | 2.50 | 2026-08-02 14:05:00 | 2026-08-02 14:05:00 |

---

### 9. Food Customizations:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique customization identifier |
| `food_item_id` | BIGINT UNSIGNED | FK | NO | - | References `food_items(id)` (ON DELETE CASCADE) |
| `name` | VARCHAR(120) | - | NO | - | Add-on title (e.g. 'Extra Avocado Slice') |
| `price_adjustment`| DECIMAL(10,2)| - | NO | 0.00 | Price added per unit |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | food_item_id | name | price_adjustment | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | Extra Avocado Slice | 2.00 | 2026-08-02 14:10:00 | 2026-08-02 14:10:00 |
| 2 | 1 | Organic Crumbled Feta | 1.50 | 2026-08-02 14:10:00 | 2026-08-02 14:10:00 |
| 3 | 3 | Extra Tahini Drizzle | 1.00 | 2026-08-02 14:20:00 | 2026-08-02 14:20:00 |

---

### 10. Restaurant Tables:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique table identifier |
| `branch_id` | BIGINT UNSIGNED | FK | NO | - | References `branches(id)` (ON DELETE CASCADE) |
| `table_number` | VARCHAR(80) | UNI | NO | - | Table designation (unique per branch) |
| `capacity` | SMALLINT UNSIGNED | - | NO | 2 | Seating capacity |
| `status` | ENUM('available', 'occupied', 'cleaning', 'out_of_service') | - | NO | 'available' | Real-time table status |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | branch_id | table_number | capacity | status | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | T-01 | 4 | available | 2026-08-02 15:00:00 | 2026-08-02 15:00:00 |
| 2 | 1 | T-02 | 2 | occupied | 2026-08-02 15:00:00 | 2026-08-02 15:00:00 |

---

### 11. QR Tokens:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique token record ID |
| `restaurant_table_id` | BIGINT UNSIGNED | FK | NO | - | References `restaurant_tables(id)` (ON DELETE CASCADE) |
| `token` | VARCHAR(255) | UNI | NO | - | Cryptographic URL-safe token string |
| `expires_at` | DATETIME | - | YES | NULL | Optional token expiration date/time |
| `is_active` | TINYINT(1) | - | NO | 1 | Token status (1=active, 0=revoked) |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Generation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | restaurant_table_id | token | expires_at | is_active | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | hb_tok_9f82a1d04b9e28 | NULL | 1 | 2026-08-02 15:30:00 | 2026-08-02 15:30:00 |
| 2 | 2 | hb_tok_6c11e48b70f4a3 | NULL | 1 | 2026-08-02 15:30:00 | 2026-08-02 15:30:00 |

---

### 12. Customers:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique customer identifier |
| `name` | VARCHAR(120) | - | NO | - | Customer full name |
| `phone` | VARCHAR(30) | - | YES | NULL | Contact phone number |
| `email` | VARCHAR(190) | - | YES | NULL | Contact email address |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | name | phone | email | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Sophia Chen | 9876543210 | sophia.c@example.test | 2026-08-10 12:15:00 | 2026-08-10 12:15:00 |
| 2 | Liam Thorne | 9876543211 | liam.thorne@example.test | 2026-08-10 13:05:00 | 2026-08-10 13:05:00 |

---

### 13. Orders:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique order ticket ID |
| `branch_id` | BIGINT UNSIGNED | FK | NO | - | References `branches(id)` (ON DELETE RESTRICT) |
| `customer_id` | BIGINT UNSIGNED | FK | NO | - | References `customers(id)` (ON DELETE RESTRICT) |
| `restaurant_table_id` | BIGINT UNSIGNED | FK | NO | - | References `restaurant_tables(id)` (ON DELETE RESTRICT) |
| `order_number` | VARCHAR(32) | UNI | NO | - | Public human-readable order number |
| `status` | ENUM('pending', 'accepted', 'preparing', 'ready', 'served', 'completed', 'cancelled') | - | NO | 'pending' | Current order processing state |
| `customer_note` | VARCHAR(500) | - | YES | NULL | Special preparation instructions |
| `subtotal` | DECIMAL(10,2) | - | NO | - | Order subtotal before taxes |
| `tax_amount` | DECIMAL(10,2) | - | NO | 0.00 | Calculated tax total |
| `total_amount` | DECIMAL(10,2) | - | NO | - | Final billed total (`subtotal + tax_amount`) |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Placement timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Status modification timestamp |

#### Sample Database Records:
| id | branch_id | customer_id | restaurant_table_id | order_number | status | customer_note | subtotal | tax_amount | total_amount | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | 1 | 2 | HB-20260810-001 | completed | Dressing on the side please. | 20.00 | 1.00 | 21.00 | 2026-08-10 12:20:00 | 2026-08-10 12:45:00 |
| 2 | 1 | 2 | 1 | HB-20260810-002 | preparing | No ice in smoothie. | 21.50 | 1.08 | 22.58 | 2026-08-10 13:10:00 | 2026-08-10 13:12:00 |

---

### 14. Order Items:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique line item ID |
| `order_id` | BIGINT UNSIGNED | FK | NO | - | References `orders(id)` (ON DELETE CASCADE) |
| `food_item_id` | BIGINT UNSIGNED | FK | NO | - | References `food_items(id)` (ON DELETE RESTRICT) |
| `food_variant_id`| BIGINT UNSIGNED | FK | YES | NULL | References `food_variants(id)` (ON DELETE SET NULL) |
| `item_name` | VARCHAR(160) | - | NO | - | Snapshot name of ordered dish |
| `unit_price` | DECIMAL(10,2) | - | NO | - | Snapshot price at time of purchase |
| `quantity` | INT UNSIGNED | - | NO | - | Quantity ordered |
| `line_total` | DECIMAL(10,2) | - | NO | - | Line item total price |
| `customer_note` | VARCHAR(300) | - | YES | NULL | Item-specific preparation note |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Placement timestamp |

#### Sample Database Records:
| id | order_id | food_item_id | food_variant_id | item_name | unit_price | quantity | line_total | customer_note | created_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | 1 | 2 | Greek Quinoa Crunch Salad (Large Power Bowl) | 16.00 | 1 | 18.00 | Extra dressing container | 2026-08-10 12:20:00 |
| 2 | 1 | 2 | NULL | Green Detox Glow Smoothie | 7.50 | 1 | 7.50 | NULL | 2026-08-10 12:20:00 |
| 3 | 2 | 3 | NULL | Roasted Tofu Harvest Bowl | 14.00 | 1 | 15.00 | Warm bowl please | 2026-08-10 13:10:00 |

---

### 15. Order Item Customizations:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique customization link identifier |
| `order_item_id` | BIGINT UNSIGNED | FK | NO | - | References `order_items(id)` (ON DELETE CASCADE) |
| `food_customization_id` | BIGINT UNSIGNED | FK | NO | - | References `food_customizations(id)` (ON DELETE CASCADE) |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Link timestamp |

#### Sample Database Records:
| id | order_item_id | food_customization_id | created_at |
| :--- | :--- | :--- | :--- |
| 1 | 1 | 1 | 2026-08-10 12:20:00 |
| 2 | 3 | 3 | 2026-08-10 13:10:00 |

---

### 16. Payments:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique payment record ID |
| `order_id` | BIGINT UNSIGNED | FK | NO | - | References `orders(id)` (ON DELETE CASCADE) |
| `amount` | DECIMAL(10,2) | - | NO | - | Settled transaction amount |
| `method` | ENUM('cash', 'upi', 'card') | - | NO | - | Payment channel |
| `status` | ENUM('pending', 'completed', 'failed') | - | NO | 'pending' | Settlement status |
| `transaction_reference` | VARCHAR(100) | - | YES | NULL | External gateway/POS reference string |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Transaction timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | order_id | amount | method | status | transaction_reference | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | 21.00 | upi | completed | UPI-TXN-20260810-9842 | 2026-08-10 12:45:00 | 2026-08-10 12:45:00 |
| 2 | 2 | 22.58 | card | pending | CARD-AUTH-90214 | 2026-08-10 13:10:00 | 2026-08-10 13:10:00 |

---

### 17. Reviews:

#### Table Structure:
| Field | Data Type | Key | Null | Default | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK | NO | AUTO_INCREMENT | Unique review identifier |
| `customer_id` | BIGINT UNSIGNED | FK | NO | - | References `customers(id)` (ON DELETE CASCADE) |
| `restaurant_id` | BIGINT UNSIGNED | FK | NO | - | References `restaurants(id)` (ON DELETE CASCADE) |
| `order_id` | BIGINT UNSIGNED | FK | YES | NULL | References `orders(id)` (ON DELETE SET NULL) |
| `food_item_id` | BIGINT UNSIGNED | FK | YES | NULL | References `food_items(id)` (ON DELETE SET NULL) |
| `restaurant_table_id` | BIGINT UNSIGNED | FK | YES | NULL | References `restaurant_tables(id)` (ON DELETE SET NULL) |
| `rating` | TINYINT UNSIGNED | - | NO | - | Customer score (1 to 5 stars) |
| `comment` | TEXT | - | YES | NULL | Qualitative review text |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Submission timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Last modification timestamp |

#### Sample Database Records:
| id | customer_id | restaurant_id | order_id | food_item_id | restaurant_table_id | rating | comment | created_at | updated_at |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | 1 | 1 | 1 | 1 | 2 | 5 | The Greek Quinoa Salad with extra avocado was exceptionally fresh and filling! | 2026-08-10 13:00:00 | 2026-08-10 13:00:00 |

---

## 6.1 Database Relationship Summary

The Healthy Bite database architecture establishes the following logical relationships among the 17 tables:

1. **Authentication & Identity:**
   - `admin` (1 : N) and `roles` (1 : N) classify user capabilities in `users`.
   - Each `restaurant` is owned by an owner user (`users.id`) and employs operational staff members (`users.restaurant_id`).

2. **Multi-Tenant Branch & Seating Hierarchy:**
   - Each `restaurant` operates multiple physical `branches` (1 : N).
   - Each `branch` manages physical `restaurant_tables` (1 : N).
   - Each dining table generates dynamic, cryptographically secure `qr_tokens` (1 : N) for contactless menu ordering.

3. **Menu, Variants & Customizations:**
   - `restaurants` partition menus into `categories` (1 : N).
   - Each `category` contains multiple `food_items` (1 : N) storing nutritional macros and allergens.
   - Each `food_item` offers portion sizes in `food_variants` (1 : N) and optional add-ons in `food_customizations` (1 : N).

4. **Orders, Fulfillment & Payments:**
   - A `customer` seated at a `restaurant_table` in a `branch` places an `order` (1 : N).
   - An `order` contains multiple `order_items` (1 : N) capturing immutable price snapshots.
   - Selected extras are linked to ordered dishes via the associative bridge table `order_item_customizations` (M : N).
   - Each `order` is settled via a `payments` transaction (1 : 1).

5. **Customer Feedback & Reviews:**
   - Customers submit verified `reviews` associated with their `order`, specific `food_items`, dining `restaurant_table`, and parent `restaurant` (N : 1).

---

## 6.2 Database Implementation Summary

The Healthy Bite system is implemented using **MySQL 8.0+** with the **InnoDB** storage engine to guarantee full ACID compliance, foreign key enforcement, and crash recovery.

Key technical implementation details:
- **Primary Keys:** Every table uses a 64-bit integer (`BIGINT UNSIGNED AUTO_INCREMENT`) primary key (`id`) for high-performance indexing and horizontal scalability.
- **Referential Integrity:** Enforces 25 active foreign keys with explicit cascading rules (`ON UPDATE CASCADE`, `ON DELETE CASCADE / RESTRICT / SET NULL`) to maintain referential integrity without orphan records.
- **Precision Financials:** Money attributes (`base_price`, `unit_price`, `subtotal`, `tax_amount`, `total_amount`, `amount`) use `DECIMAL(10,2)` to prevent floating-point calculation errors.
- **Universal Character Encoding:** Universal `utf8mb4` encoding with `utf8mb4_unicode_ci` collation ensures multilingual support and emoji compatibility.
