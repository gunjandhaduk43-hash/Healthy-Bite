# Coding Standards

## PHP

- Target PHP 8+ and use `declare(strict_types=1);` in application PHP files where compatible.
- Follow PSR-12 formatting: four spaces, braces on the next line, and descriptive names.
- Keep controllers thin. Put business rules in services and database statements in repositories.
- Use `password_hash()` and `password_verify()` only; never implement custom hashing.
- Escape rendered untrusted text through one helper, for example `e($value)`.

## SQL and database

- Use PDO prepared statements for every query.
- Name tables and columns in lowercase `snake_case`; singular PHP classes and plural tables.
- Use transactions for order creation and any multi-table update.
- Do not concatenate request values into SQL.

## JavaScript and CSS

- Use vanilla JavaScript with `const`/`let`, event delegation where needed, and no inline event handlers.
- Keep page-specific scripts in separate files under `public/assets/js/`.
- Use Bootstrap utilities/components first, then project CSS variables and component classes.
- Maintain keyboard-visible focus states and meaningful labels.

## Git and documentation

- Make small, purpose-focused commits when committing is requested.
- Do not commit `.env`, uploads, logs, generated QR images, or database backups.
- Update `CHANGELOG.md`, relevant technical documentation, and college progress evidence for every completed stage.
