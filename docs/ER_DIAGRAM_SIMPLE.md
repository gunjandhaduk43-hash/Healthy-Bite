# Core Database ER Diagram (Authoritative 17-Table Architecture)

This diagram details the authoritative **17 core tables** that power the complete Healthy Bite platform (Authentication, Roles, Tenantry, Branches, Menu, Customizations, Variants, Seating, Ordering, Reviews, and Payments).

---

## 1. 17-Table Core ER Diagram

```mermaid
erDiagram
    admin ||--o{ users : "admin_id -> id"
    roles ||--o{ users : "role_id -> id"
    restaurants ||--o{ users : "restaurant_id -> id"
    users ||--o{ restaurants : "owner_user_id -> id"
    restaurants ||--o{ branches : "restaurant_id -> id"
    restaurants ||--o{ categories : "restaurant_id -> id"
    categories ||--o{ food_items : "category_id -> id"
    food_items ||--o{ food_variants : "food_item_id -> id"
    food_items ||--o{ food_customizations : "food_item_id -> id"
    branches ||--o{ restaurant_tables : "branch_id -> id"
    restaurant_tables ||--o{ qr_tokens : "restaurant_table_id -> id"
    customers ||--o{ orders : "customer_id -> id"
    branches ||--o{ orders : "branch_id -> id"
    restaurant_tables ||--o{ orders : "restaurant_table_id -> id"
    orders ||--o{ order_items : "order_id -> id"
    food_items ||--o{ order_items : "food_item_id -> id"
    food_variants ||--o{ order_items : "food_variant_id -> id"
    order_items ||--o{ order_item_customizations : "order_item_id -> id"
    food_customizations ||--o{ order_item_customizations : "food_customization_id -> id"
    orders ||--|| payments : "order_id -> id"
    customers ||--o{ reviews : "customer_id -> id"
    restaurants ||--o{ reviews : "restaurant_id -> id"
    orders ||--o{ reviews : "order_id -> id"
    food_items ||--o{ reviews : "food_item_id -> id"
    restaurant_tables ||--o{ reviews : "restaurant_table_id -> id"

    admin {
        BIGINT id PK
        VARCHAR name
    }
    roles {
        BIGINT id PK
        VARCHAR name
        VARCHAR slug UK
        VARCHAR description
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
    }
    restaurants {
        BIGINT id PK
        BIGINT owner_user_id FK
        VARCHAR name
        VARCHAR email
        VARCHAR phone
        VARCHAR address
        ENUM approval_status
    }
    branches {
        BIGINT id PK
        BIGINT restaurant_id FK
        VARCHAR name
        VARCHAR phone
        VARCHAR address
    }
    categories {
        BIGINT id PK
        BIGINT restaurant_id FK
        VARCHAR name
        INT sort_order
        TINYINT is_active
    }
    food_items {
        BIGINT id PK
        BIGINT category_id FK
        VARCHAR name
        DECIMAL base_price
        INT calories
        DECIMAL protein
        DECIMAL carbs
        DECIMAL fat
        VARCHAR food_type
        TINYINT is_available
    }
    food_variants {
        BIGINT id PK
        BIGINT food_item_id FK
        VARCHAR name
        DECIMAL price_adjustment
    }
    food_customizations {
        BIGINT id PK
        BIGINT food_item_id FK
        VARCHAR name
        DECIMAL price_adjustment
    }
    restaurant_tables {
        BIGINT id PK
        BIGINT branch_id FK
        VARCHAR table_number
        INT capacity
        ENUM status
    }
    qr_tokens {
        BIGINT id PK
        BIGINT restaurant_table_id FK
        VARCHAR token UK
        TINYINT is_active
    }
    customers {
        BIGINT id PK
        VARCHAR name
        VARCHAR phone
        VARCHAR email
    }
    orders {
        BIGINT id PK
        BIGINT branch_id FK
        BIGINT customer_id FK
        BIGINT restaurant_table_id FK
        VARCHAR order_number UK
        ENUM status
        DECIMAL subtotal
        DECIMAL total_amount
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
    }
    order_item_customizations {
        BIGINT id PK
        BIGINT order_item_id FK
        BIGINT food_customization_id FK
    }
    payments {
        BIGINT id PK
        BIGINT order_id FK
        DECIMAL amount
        ENUM method
        ENUM status
    }
    reviews {
        BIGINT id PK
        BIGINT customer_id FK
        BIGINT restaurant_id FK
        BIGINT order_id FK
        BIGINT food_item_id FK
        BIGINT restaurant_table_id FK
        INT rating
        TEXT comment
    }
```

---

## 2. Table Directory (Authoritative 17 Tables)

1. **admin**: Platform Administration master entity.
2. **roles**: Master lookup for user roles (Super Admin, Owner, Manager, Staff).
3. **users**: Operational accounts scoped to roles and restaurant tenants.
4. **restaurants**: Core billing tenant profile.
5. **branches**: Geographically distinct physical locations.
6. **categories**: Logical menu groupings.
7. **food_items**: Dishes with prices, nutrition, allergens, and dietary tags.
8. **food_variants**: Sizing / portion variations.
9. **food_customizations**: Ingredient add-ons & extras.
10. **restaurant_tables**: Physical tables at a branch.
11. **qr_tokens**: Dynamic QR access tokens.
12. **customers**: Guest ordering profiles.
13. **orders**: Transactions and kitchen tickets.
14. **order_items**: Line-item breakdown.
15. **order_item_customizations**: Add-on bridge for order items.
16. **payments**: Point-of-sale settlements and references.
17. **reviews**: Customer feedback and star ratings.
