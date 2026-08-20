# Requirement Analysis and Feasibility Study

## Functional requirements

1. Owners can register, log in, and maintain restaurant details.
2. Owners can manage categories, food items, nutrition, ingredients, allergens, tables, and QR codes.
3. Customers can scan a QR, browse available food, filter/select items, change cart quantities, and place an order.
4. Staff can view restaurant orders and update statuses: pending, accepted, preparing, ready, served, completed, cancelled.
5. Owners can view basic daily order and sales reports.

## Non-functional requirements

- Responsive customer UI on mobile devices.
- Role-based access control and tenant isolation.
- Secure passwords, prepared queries, session protection, and CSRF protection.
- Clear error messages and reliable transaction-based ordering.

## Feasibility

| Type | Finding |
| --- | --- |
| Technical | Feasible using PHP 8, MySQL, Apache/XAMPP, Bootstrap, and JavaScript taught or appropriate for BCA work. |
| Operational | Restaurant owners and staff require only a browser; customers use a phone camera and browser. |
| Economic | Development uses free/open-source tools and local XAMPP during academic work. |
| Schedule | MVP is divided across four development stages before 14 September 2026. |
| Legal/ethical | Nutrition values are informational; restaurant owners are responsible for correct menu/allergen data. |

## Hardware/software requirements

- Windows/Linux computer, 4 GB RAM minimum, modern browser, phone for QR testing.
- XAMPP, PHP 8+, MySQL 8+, VS Code, and MySQL Workbench or phpMyAdmin.
