# System Development Stage 1

**Target date:** 20 August 2026

## Scope

- Project folder structure and configuration.
- PDO database connection and initial schema.
- Base layout and responsive Bootstrap navigation.
- Restaurant owner registration, login, logout, and session middleware.
- Staff account foundation and role checks.
- Restaurant profile create/read/update.

## Implementation status

- [x] Custom PHP MVC folder structure, front controller, routing, views, and PDO configuration.
- [x] Owner registration, login, logout, secure sessions, and protected dashboard route.
- [x] Tenant-scoped restaurant profile create/read/update flow.
- [x] Initial `users` and `restaurants` MySQL schema.
- [ ] Import schema into local XAMPP MySQL and capture required screenshots/test evidence.

## Evidence to attach

- Screenshot of running local application and login screen.
- Screenshot of restaurant registration/profile page.
- phpMyAdmin/MySQL Workbench screenshot showing initial tables.
- Test cases for successful login, failed login, logout, and protected route redirection.

## Acceptance criteria

Owner can register and log in; unauthenticated users cannot open dashboard pages; passwords are hashed; owner data is stored in MySQL through prepared statements.
