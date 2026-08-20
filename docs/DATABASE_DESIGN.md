# Database Design

## Design rules

- MySQL 8+, InnoDB, `utf8mb4`, UTC timestamps.
- Use `BIGINT UNSIGNED` primary keys, foreign keys, and indexes on all foreign-key columns.
- Store money as `DECIMAL(10,2)`; never use floating point for price or totals.
- Use `created_at` and `updated_at` on mutable business tables.
- Store password hashes only; never store raw passwords or payment card data.

## Assessed MVP tables

| Table | Purpose | Key columns |
| --- | --- | --- |
| `users` | Super admin, owner, and staff identities | `id`, `restaurant_id`, `role`, `name`, `email`, `password_hash`, `status` |
| `restaurants` | Tenant profile | `id`, `owner_user_id`, `name`, `email`, `phone`, `address`, `approval_status` |
| `categories` | Restaurant menu groups | `id`, `restaurant_id`, `name`, `sort_order`, `is_active` |
| `food_items` | Sellable menu items | `id`, `restaurant_id`, `category_id`, `name`, `description`, `price`, `is_available`, `diet_type` |
| `food_nutrition` | One nutrition record per food item | `food_item_id`, `calories_kcal`, `protein_g`, `carbohydrates_g`, `fat_g`, `fiber_g`, `sugar_g`, `serving_size` |
| `ingredients` | Reusable ingredient names | `id`, `name` |
| `food_ingredients` | Food-to-ingredient mapping | `food_item_id`, `ingredient_id` |
| `allergens` | Reusable allergen names | `id`, `name` |
| `food_allergens` | Food-to-allergen mapping | `food_item_id`, `allergen_id` |
| `restaurant_tables` | Restaurant seating tables | `id`, `restaurant_id`, `table_name`, `capacity`, `status` |
| `qr_tokens` | Opaque, unique QR token per table | `id`, `restaurant_table_id`, `token_hash`, `expires_at`, `is_active` |
| `orders` | Customer order header | `id`, `restaurant_id`, `table_id`, `order_number`, `status`, `subtotal`, `tax_amount`, `total_amount` |
| `order_items` | Immutable ordered item snapshot | `id`, `order_id`, `food_item_id`, `item_name`, `unit_price`, `quantity`, `line_total`, `customer_note` |
| `order_status_history` | Traceable order changes | `id`, `order_id`, `status`, `changed_by_user_id`, `changed_at` |
| `qr_sessions` | Server-side QR menu context audit | `id`, `qr_token_id`, `session_identifier`, `started_at`, `last_seen_at` |

## Data dictionary

| Entity | Essential validation |
| --- | --- |
| User | Unique lowercase email, `password_hash`, role in allowed role set, active status required at login |
| Restaurant | Owner is a user, unique business email, approved before customer QR access |
| Food item | Belongs to same restaurant as category, non-negative price, active category, availability boolean |
| Nutrition | One row per food item; grams and calories cannot be negative |
| Table | Unique `table_name` within a restaurant; table status controlled by application |
| QR token | Cryptographically random token, only hash stored, unique hash, active and unexpired for scan |
| Order | Restaurant and table must match QR session; amounts calculated on server inside a transaction |
| Order item | Snapshot name and price are retained so historic invoices remain correct after menu edits |

## Status values

- Restaurant: `pending`, `approved`, `suspended`
- Table: `available`, `occupied`, `cleaning`, `out_of_service`
- Order: `pending`, `accepted`, `preparing`, `ready`, `served`, `completed`, `cancelled`

## Important constraints

1. `restaurants.owner_user_id` must reference an owner account.
2. `food_items.restaurant_id` must equal the category's restaurant in application validation.
3. `orders.restaurant_id` must equal the selected table's restaurant.
4. QR tokens contain a hash of the externally visible random value; the raw token is never stored.
5. Deleting referenced records is avoided. Use `status` or `is_active` for soft deactivation.
