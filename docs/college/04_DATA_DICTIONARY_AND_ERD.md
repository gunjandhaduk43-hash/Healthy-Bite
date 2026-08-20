# Data Dictionary and ER Diagram

The complete MVP database design is maintained in [Database Design](../DATABASE_DESIGN.md) and [ER Diagram](../ER_DIAGRAM.md).

## College data dictionary summary

| Table | Primary key | Major foreign keys | Purpose |
| --- | --- | --- | --- |
| `users` | `id` | `restaurant_id` | Owner/staff/super-admin login identity |
| `restaurants` | `id` | `owner_user_id` | Restaurant profile and approval state |
| `categories` | `id` | `restaurant_id` | Food grouping |
| `food_items` | `id` | `restaurant_id`, `category_id` | Menu item details and price |
| `food_nutrition` | `food_item_id` | `food_item_id` | Nutrition values per item |
| `ingredients` | `id` | - | Reusable ingredient master |
| `food_ingredients` | composite | `food_item_id`, `ingredient_id` | Food ingredient mapping |
| `allergens` | `id` | - | Reusable allergen master |
| `food_allergens` | composite | `food_item_id`, `allergen_id` | Food allergen mapping |
| `restaurant_tables` | `id` | `restaurant_id` | Restaurant table details |
| `qr_tokens` | `id` | `restaurant_table_id` | Hashed secure QR token |
| `orders` | `id` | `restaurant_id`, `table_id` | Order header and total |
| `order_items` | `id` | `order_id`, `food_item_id` | Ordered food lines |
| `order_status_history` | `id` | `order_id`, `changed_by_user_id` | Audit of status updates |

## ERD submission note

Render the Mermaid ER diagram from `docs/ER_DIAGRAM.md` into an image or redraw it using draw.io before adding it to the final bound report. Keep all table and relationship names identical.
