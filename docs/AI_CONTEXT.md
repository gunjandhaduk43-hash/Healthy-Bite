# AI Context

## Current project state

Healthy Bite is at documentation and planning stage. The codebase has not yet established an implementation structure. Use the BCA Semester V MVP boundary in `PROJECT_VISION.md` and `ROADMAP.md` as the source of truth for work before 30 September 2026.

## Non-negotiable constraints

- Use only PHP, MySQL, HTML, CSS, Bootstrap 5, vanilla JavaScript, AJAX, and Apache/XAMPP.
- Do not introduce React, Node.js, Laravel, Composer-dependent frameworks, or an ORM.
- Preserve a custom modular MVC separation: controller -> service -> repository -> PDO.
- Use MySQL prepared statements, CSRF checks, escaping, sessions, RBAC, and opaque QR tokens.
- Treat all restaurant-owned data as tenant-scoped.

## Implementation sequence

1. Create the source layout, routing, configuration, PDO connection, helpers, and authentication foundation.
2. Build restaurant profile, category, food item, nutrition, ingredient, allergen, table, and QR management.
3. Build the customer QR menu and transactional order flow.
4. Build staff status updates, basic reports, test evidence, and final screenshots.

## Definition of done

For every module: update database schema, routes, validation, role checks, views, manual tests, `CHANGELOG.md`, and the matching college-stage document. Do not claim a feature is complete without a working path and test evidence.
