# Architecture Decisions

## ADR-001: Use PHP and MySQL

**Decision:** Build with PHP 8+, MySQL, Apache/XAMPP, HTML, CSS, Bootstrap 5, and vanilla JavaScript.

**Reason:** These technologies are compatible with the BCA Sem-V syllabus and can demonstrate server-side programming, database connectivity, CRUD, sessions, security, and web design without an advanced framework.

## ADR-002: Deliver an MVP first

**Decision:** Implement QR ordering, nutrition menu management, and staff order handling before payments, inventory, or AI.

**Reason:** The college timeline ends on 30 September 2026. A smaller, tested system is stronger than a large untested feature list.

## ADR-003: Use opaque QR tokens

**Decision:** QR codes contain a random token instead of restaurant and table IDs.

**Reason:** Numeric IDs are easily altered and can misdirect orders. The server validates the token and creates a session-bound table context.

## ADR-004: Use custom modular MVC

**Decision:** Use controllers, services, repositories, middleware, and views without Laravel or another framework.

**Reason:** It follows the technology restriction while keeping code separated, testable, and easy to explain in viva.

## ADR-005: Preserve order item snapshots

**Decision:** Save item name and unit price in `order_items` at order time.

**Reason:** Menu edits must not change historical order records or reports.
