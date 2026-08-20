# System Development Stage 3

**Target date:** 05 September 2026

## Scope

- Mobile QR menu with category browsing and food details.
- Session-based cart with quantity update/removal.
- Server-side order validation and transactional order creation.
- Customer confirmation/status page.
- Staff order queue and status progression.

## Evidence to attach

- Phone-width QR menu screenshots.
- Cart and order confirmation screenshots.
- Staff queue screenshots for pending, preparing, ready, and served statuses.
- Order/order-item/status-history database screenshots.
- Tests: invalid QR, cart price tampering, unavailable item, order creation, invalid status change.

## Acceptance criteria

The system creates an order against only the session-validated table, keeps item/price snapshots, and staff can update only orders belonging to their restaurant.
