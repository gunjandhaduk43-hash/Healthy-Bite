# Data Dictionary and ER Diagram

The complete authoritative database design is maintained in [Database Design](../DATABASE_DESIGN.md) and [ER Diagram](../ER_DIAGRAM.md).

## Authoritative 17-Table Data Dictionary Summary

| # | Table | Primary Key | Major Foreign Keys | Purpose |
|---|---|---|---|---|
| 1 | `admin` | `id` | None | Platform administration master entity |
| 2 | `roles` | `id` | None | Master authorization role definitions |
| 3 | `users` | `id` | `admin_id`, `role_id`, `restaurant_id` | Super admin, owner, manager, and staff credentials |
| 4 | `restaurants` | `id` | `owner_user_id` | Restaurant tenant profile and approval state |
| 5 | `branches` | `id` | `restaurant_id` | Physical location branches |
| 6 | `categories` | `id` | `restaurant_id` | Food and menu grouping |
| 7 | `food_items` | `id` | `category_id` | Menu item details, prices, and nutrition |
| 8 | `food_variants` | `id` | `food_item_id` | Item portion and size options |
| 9 | `food_customizations` | `id` | `food_item_id` | Add-on ingredients and extras |
| 10 | `restaurant_tables` | `id` | `branch_id` | Physical table seating details |
| 11 | `qr_tokens` | `id` | `restaurant_table_id` | Secure dynamic QR tokens |
| 12 | `customers` | `id` | None | Guest diner account profiles |
| 13 | `orders` | `id` | `branch_id`, `customer_id`, `restaurant_table_id` | Customer order tickets and totals |
| 14 | `order_items` | `id` | `order_id`, `food_item_id`, `food_variant_id` | Ordered line-item dishes |
| 15 | `order_item_customizations` | `id` | `order_item_id`, `food_customization_id` | Chosen line-item add-ons |
| 16 | `payments` | `id` | `order_id` | POS transaction settlements |
| 17 | `reviews` | `id` | `customer_id`, `restaurant_id`, `order_id`, `food_item_id`, `restaurant_table_id` | Verified customer reviews and ratings |

## ERD Submission Note

Render the Mermaid ER diagram from `docs/ER_DIAGRAM.md` or `docs/ER_DIAGRAM_SIMPLE.md` into an image or redraw it using draw.io before adding it to the final bound report. Keep all 17 table and relationship names identical.
