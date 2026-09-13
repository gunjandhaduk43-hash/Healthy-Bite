# Entity Relationship Diagram (ERD) & Technical Database Design (Authoritative 17-Table Schema)

This document details the relational database design for **Healthy Bite – QR-Based Digital Menu & Food Ordering System**. 

The schema is in Third Normal Form (3NF), utilizes `BIGINT` for all primary keys, adheres to MySQL 8.0 standards, and consists of **exactly 17 tables**.

---

## 1. Professional ER Diagram (Mermaid Relational Notation)

Below is the visual relationship schema represented in standard Mermaid.js notation.

```mermaid
erDiagram
    %% ==========================================
    %% 1. AUTHENTICATION & ACCESS CONTROL
    %% ==========================================
    admin ||--o{ users : "assigns"
    roles ||--o{ users : "assigns"
    restaurants ||--o{ users : "employs"

    %% ==========================================
    %% 2. RESTAURANT & BRANCH MODULE
    %% ==========================================
    restaurants ||--o{ branches : "spans"
    restaurants ||--o{ categories : "possesses"
    restaurants ||--o{ reviews : "scores"

    %% ==========================================
    %% 3. MENU MODULE
    %% ==========================================
    categories ||--o{ food_items : "groups"
    food_items ||--o{ food_variants : "sizes"
    food_items ||--o{ food_customizations : "adapts"
    food_items ||--o{ order_items : "details"
    food_items ||--o{ reviews : "evaluates"

    %% ==========================================
    %% 4. QR SEATING MODULE
    %% ==========================================
    branches ||--o{ restaurant_tables : "operates"
    restaurant_tables ||--o{ qr_tokens : "generates"
    restaurant_tables ||--o{ orders : "serves"
    restaurant_tables ||--o{ reviews : "locations"

    %% ==========================================
    %% 5. CUSTOMER & ORDER MODULES
    %% ==========================================
    customers ||--o{ orders : "places"
    customers ||--o{ reviews : "writes"
    branches ||--o{ orders : "processes"
    orders ||--o{ order_items : "comprises"
    orders ||--o{ reviews : "verifies"
    orders ||--|| payments : "settles"
    order_items ||--o{ order_item_customizations : "customizes"
    food_customizations ||--o{ order_item_customizations : "applied_in"

    %% ==========================================
    %% ENTITY ATTRIBUTES
    %% ==========================================
    admin {
        BIGINT id PK
        VARCHAR name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    roles {
        BIGINT id PK
        VARCHAR name
        VARCHAR slug UK
        VARCHAR description
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    users {
        BIGINT id PK
        BIGINT admin_id FK
        BIGINT role_id FK
        BIGINT restaurant_id FK
        VARCHAR name
        VARCHAR email UK
        VARCHAR password_hash
        ENUM status
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    restaurants {
        BIGINT id PK
        BIGINT owner_user_id FK
        VARCHAR name
        VARCHAR email
        VARCHAR phone
        VARCHAR address
        VARCHAR city
        VARCHAR state
        VARCHAR cuisine_type
        VARCHAR description
        ENUM approval_status
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    branches {
        BIGINT id PK
        BIGINT restaurant_id FK
        VARCHAR name
        VARCHAR phone
        VARCHAR address
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    categories {
        BIGINT id PK
        BIGINT restaurant_id FK
        VARCHAR name
        INT sort_order
        TINYINT is_active
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    food_items {
        BIGINT id PK
        BIGINT category_id FK
        VARCHAR name
        VARCHAR description
        VARCHAR image
        TEXT ingredients
        DECIMAL base_price
        INT calories
        DECIMAL protein
        DECIMAL carbs
        DECIMAL fat
        DECIMAL fiber_g
        DECIMAL sugar_g
        VARCHAR allergens
        INT preparation_time
        VARCHAR spice_level
        VARCHAR food_type
        VARCHAR serving_size
        TINYINT is_available
        TINYINT is_featured
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    food_variants {
        BIGINT id PK
        BIGINT food_item_id FK
        VARCHAR name
        DECIMAL price_adjustment
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    food_customizations {
        BIGINT id PK
        BIGINT food_item_id FK
        VARCHAR name
        DECIMAL price_adjustment
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    restaurant_tables {
        BIGINT id PK
        BIGINT branch_id FK
        VARCHAR table_number
        SMALLINT capacity
        ENUM status
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    qr_tokens {
        BIGINT id PK
        BIGINT restaurant_table_id FK
        VARCHAR token UK
        DATETIME expires_at
        TINYINT is_active
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    customers {
        BIGINT id PK
        VARCHAR name
        VARCHAR phone
        VARCHAR email
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    orders {
        BIGINT id PK
        BIGINT branch_id FK
        BIGINT customer_id FK
        BIGINT restaurant_table_id FK
        VARCHAR order_number UK
        ENUM status
        VARCHAR customer_note
        DECIMAL subtotal
        DECIMAL tax_amount
        DECIMAL total_amount
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    order_items {
        BIGINT id PK
        BIGINT order_id FK
        BIGINT food_item_id FK
        BIGINT food_variant_id FK
        VARCHAR item_name
        DECIMAL unit_price
        INT quantity
        DECIMAL line_total
        VARCHAR customer_note
        TIMESTAMP created_at
    }
    order_item_customizations {
        BIGINT id PK
        BIGINT order_item_id FK
        BIGINT food_customization_id FK
        TIMESTAMP created_at
    }
    payments {
        BIGINT id PK
        BIGINT order_id FK
        DECIMAL amount
        ENUM method
        ENUM status
        VARCHAR transaction_reference
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    reviews {
        BIGINT id PK
        BIGINT customer_id FK
        BIGINT restaurant_id FK
        BIGINT order_id FK
        BIGINT food_item_id FK
        BIGINT restaurant_table_id FK
        TINYINT rating
        TEXT comment
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
```

---

## 2. Table Directory (Authoritative 17 Tables)

1. **`admin`**: Platform administration master records.
2. **`roles`**: Master definitions for system roles (`super_admin`, `owner`, `manager`, `staff`).
3. **`users`**: User identities associated with roles and restaurant tenants.
4. **`restaurants`**: Tenant profiles and billing owners.
5. **`branches`**: Physical locations operated by restaurants.
6. **`categories`**: Menu groups (Appetizers, Mains, Smoothies, Salads).
7. **`food_items`**: Sellable dishes with pricing, allergen notes, and dietary classifications.
8. **`food_variants`**: Size and portion modifiers (e.g. Regular, Large Bowl).
9. **`food_customizations`**: Add-ons (e.g. Extra Avocado, Feta Cheese).
10. **`restaurant_tables`**: Physical dining seating at a branch.
11. **`qr_tokens`**: Table QR tokens for instant ordering.
12. **`customers`**: Guest diner profiles.
13. **`orders`**: Dining order tickets and lifecycle states.
14. **`order_items`**: Snapshot of foods ordered in a ticket.
15. **`order_item_customizations`**: Bridge mapping selected add-ons to order items.
16. **`payments`**: Payment transaction settlements.
17. **`reviews`**: Ratings and customer feedback.

---

## 3. Relational Foreign Key Matrix

| Child Table | Foreign Key Column | Parent Table (`PK`) | Delete Rule | Update Rule |
| :--- | :--- | :--- | :--- | :--- |
| `users` | `admin_id` | `admin(id)` | `SET NULL` | `CASCADE` |
| `users` | `role_id` | `roles(id)` | `RESTRICT` | `CASCADE` |
| `users` | `restaurant_id` | `restaurants(id)` | `SET NULL` | `CASCADE` |
| `restaurants` | `owner_user_id` | `users(id)` | `SET NULL` | `CASCADE` |
| `branches` | `restaurant_id` | `restaurants(id)` | `CASCADE` | `CASCADE` |
| `categories` | `restaurant_id` | `restaurants(id)` | `CASCADE` | `CASCADE` |
| `food_items` | `category_id` | `categories(id)` | `RESTRICT` | `CASCADE` |
| `food_variants` | `food_item_id` | `food_items(id)` | `CASCADE` | `CASCADE` |
| `food_customizations` | `food_item_id` | `food_items(id)` | `CASCADE` | `CASCADE` |
| `restaurant_tables` | `branch_id` | `branches(id)` | `CASCADE` | `CASCADE` |
| `qr_tokens` | `restaurant_table_id` | `restaurant_tables(id)` | `CASCADE` | `CASCADE` |
| `orders` | `branch_id` | `branches(id)` | `RESTRICT` | `CASCADE` |
| `orders` | `customer_id` | `customers(id)` | `RESTRICT` | `CASCADE` |
| `orders` | `restaurant_table_id` | `restaurant_tables(id)` | `RESTRICT` | `CASCADE` |
| `order_items` | `order_id` | `orders(id)` | `CASCADE` | `CASCADE` |
| `order_items` | `food_item_id` | `food_items(id)` | `RESTRICT` | `CASCADE` |
| `order_items` | `food_variant_id` | `food_variants(id)` | `SET NULL` | `CASCADE` |
| `order_item_customizations` | `order_item_id` | `order_items(id)` | `CASCADE` | `CASCADE` |
| `order_item_customizations` | `food_customization_id` | `food_customizations(id)` | `CASCADE` | `CASCADE` |
| `payments` | `order_id` | `orders(id)` | `CASCADE` | `CASCADE` |
| `reviews` | `customer_id` | `customers(id)` | `CASCADE` | `CASCADE` |
| `reviews` | `restaurant_id` | `restaurants(id)` | `CASCADE` | `CASCADE` |
| `reviews` | `order_id` | `orders(id)` | `SET NULL` | `CASCADE` |
| `reviews` | `food_item_id` | `food_items(id)` | `SET NULL` | `CASCADE` |
| `reviews` | `restaurant_table_id` | `restaurant_tables(id)` | `SET NULL` | `CASCADE` |
