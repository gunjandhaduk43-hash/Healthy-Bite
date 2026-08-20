# Core Database ER Diagram (80/20 Ruleset for Documentation)

This diagram applies the **80/20 principle (Pareto Principle)** to the Healthy Bite SaaS platform. It condenses the 50+ database tables down to the **14 core tables** that drive 80%+ of the system's functionality (Authentication, Tenantry, Menu, Seating Sessions, Order Placement, Kitchen Routing, and Payments). 

This version is optimal for project reports, system documentation, and student viva presentations, keeping the codebase easy to explain and draw.

---

## 1. 80/20 Core ER Diagram

```mermaid
erDiagram
    %% Core Tenant & Users
    Role ||--o{ User : assigns
    Restaurant ||--o{ User : employs
    Restaurant ||--o{ Branch : operates
    
    %% Menu Structure
    Restaurant ||--o{ Category : possesses
    Category ||--o{ FoodItem : groups
    FoodItem ||--|| Nutrition : contains
    
    %% Seating & Session
    Branch ||--o{ RestaurantTable : operates
    RestaurantTable ||--o{ QrSession : matches
    
    %% Ordering & Placement
    Customer ||--o{ Order : places
    QrSession ||--o{ Order : authorizes
    RestaurantTable ||--o{ Order : serves
    Order ||--o{ OrderItem : details
    FoodItem ||--o{ OrderItem : captures
    
    %% Fulfillment & Payment
    Order ||--|| Payment : settles
    Order ||--o{ KitchenOrder : routes

    Role {
        BIGINT id PK
        VARCHAR name
        VARCHAR slug UK
    }
    User {
        BIGINT id PK
        BIGINT restaurant_id FK
        BIGINT role_id FK
        VARCHAR name
        VARCHAR email UK
        VARCHAR password_hash
        ENUM status
    }
    Restaurant {
        BIGINT id PK
        VARCHAR name
        VARCHAR email
        VARCHAR phone
        VARCHAR address
    }
    Branch {
        BIGINT id PK
        BIGINT restaurant_id FK
        VARCHAR name
        VARCHAR phone
        VARCHAR address
    }
    Category {
        BIGINT id PK
        BIGINT restaurant_id FK
        VARCHAR name
        INT sort_order
    }
    FoodItem {
        BIGINT id PK
        BIGINT category_id FK
        VARCHAR name
        DECIMAL price
        ENUM status
    }
    Nutrition {
        BIGINT id PK
        BIGINT food_item_id FK "Unique"
        INT calories
        INT proteins
        INT fats
    }
    RestaurantTable {
        BIGINT id PK
        BIGINT branch_id FK
        VARCHAR table_number
        INT capacity
    }
    QrSession {
        BIGINT id PK
        BIGINT table_id FK
        VARCHAR session_token
        ENUM status
    }
    Customer {
        BIGINT id PK
        VARCHAR name
        VARCHAR email UK
        VARCHAR phone
    }
    Order {
        BIGINT id PK
        BIGINT branch_id FK
        BIGINT table_id FK
        BIGINT customer_id FK
        BIGINT qr_session_id FK
        DECIMAL total_amount
        ENUM order_status
    }
    OrderItem {
        BIGINT id PK
        BIGINT order_id FK
        BIGINT food_item_id FK
        INT quantity
        DECIMAL unit_price
    }
    KitchenOrder {
        BIGINT id PK
        BIGINT order_id FK
        ENUM status
        TIMESTAMP prepared_at
    }
    Payment {
        BIGINT id PK
        BIGINT order_id FK
        DECIMAL amount
        VARCHAR payment_method
        ENUM status
    }
```

---

## 2. Table Directory (80/20 Core Engine)

These 14 entities provide the foundation for the entire multi-tenant SaaS application flow:

1. **Role**: Dictates user permissions and administration access levels (Owner, Cashier, Kitchen, Waiter).
2. **User**: Operational accounts scoped to specific restaurants via `restaurant_id`.
3. **Restaurant**: The core billing tenant mapping overall corporate details.
4. **Branch**: Splits single restaurants into geographically isolated, physical locations.
5. **Category**: Logical menus groupings (Appetizers, Mains, Drinks) configured by the Tenant.
6. **FoodItem**: Individual dishes containing price fields, descriptions, and statuses.
7. **Nutrition**: Holds dietary values such as proteins, calories, fats for a particular `food_item_id`.
8. **RestaurantTable**: Physical restaurant dining tables where QR tokens are placed.
9. **QrSession**: Tracks active guest devices and tables during an ordering flow.
10. **Customer**: Guest ordering profiles.
11. **Order**: Relational transaction ticket tracking statuses (Pending, Preparing, Completed, Cancelled).
12. **OrderItem**: Line-item details specifying order counts and prices of chosen foods.
13. **KitchenOrder**: Routes order details into kitchen queue streams.
14. **Payment**: Handles point-of-sale settlements, payment options, and references.
