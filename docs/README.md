# Healthy Bite Documentation

Healthy Bite is a multi-restaurant, QR-based digital menu and table-ordering platform. This documentation separates the assessed BCA Semester V implementation from the longer-term SaaS product vision.

## Documentation map

| Document | Purpose |
| --- | --- |
| [Project vision](PROJECT_VISION.md) | Problem, objective, scope, and success criteria |
| [System architecture](SYSTEM_ARCHITECTURE.md) | Custom PHP MVC design and component responsibilities |
| [Database design](DATABASE_DESIGN.md) | MySQL schema and data dictionary |
| [ER diagram](ER_DIAGRAM.md) | Entity relationships for the assessed MVP |
| [Modules](MODULES.md) | Module scope and delivery stages |
| [Current pages](PAGES.md) | Implemented routes, screens, access rules, and planned pages |
| [Demo accounts](DEMO_ACCOUNTS.md) | Development login credentials and access scope |
| [Coding standards](CODING_STANDARDS.md) | PHP, MySQL, JavaScript, and Git conventions |
| [UI guidelines](UI_GUIDELINES.md) | Responsive Bootstrap UI rules |
| [Security](SECURITY.md) | Mandatory security controls |
| [Roadmap](ROADMAP.md) | Timeline-aligned delivery plan |
| [Changelog](CHANGELOG.md) | Documentation and implementation changes |
| [Decisions](DECISIONS.md) | Key technical decisions and reasons |
| [AI context](AI_CONTEXT.md) | Persistent instructions for future development sessions |

## College submission pack

The [college](college/README.md) folder follows the BCA Sem-V 2026-27 project guideline in deadline order. Use it for guide signatures, presentation preparation, implementation progress, and final report assembly.

## Technology constraints

- Frontend: HTML5, CSS3, Bootstrap 5, vanilla JavaScript, AJAX
- Backend: PHP 8+
- Database: MySQL 8+ with InnoDB and `utf8mb4`
- Local environment: Apache and MySQL through XAMPP
- Excluded: React, Node.js, Laravel, and other application frameworks

## Assessed MVP boundary

The September 2026 submission implements restaurant onboarding, menu and nutrition management, secure table QR sessions, customer ordering, staff order status updates, and basic reports. Payments, inventory, loyalty, multi-branch support, analytics, and AI features remain documented future work.
