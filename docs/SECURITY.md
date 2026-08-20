# Security Requirements

## Mandatory controls

| Risk | Required control |
| --- | --- |
| Password theft | `password_hash()`, `password_verify()`, HTTPS in deployment, no raw password logs |
| SQL injection | PDO prepared statements; no interpolated request values in SQL |
| XSS | Validate input and HTML-escape all untrusted output |
| CSRF | Token on every authenticated or state-changing form/AJAX request |
| Session hijacking | Regenerate ID after login and QR validation; secure, HttpOnly, SameSite cookies |
| Privilege escalation | Authentication and role middleware on every protected route |
| Tenant data leak | Apply authenticated restaurant scope to every owner/staff query |
| QR table tampering | Random opaque token, stored hash, expiry/active checks, session-bound context |
| File upload abuse | MIME and extension allowlist, size limits, generated filename, web-safe storage |
| Brute force | Rate limit failed login and token validation attempts; generic failure messages |

## QR ordering workflow

1. Create at least 32 random bytes with `random_bytes()` for a table QR token.
2. Store only a secure hash of the token with the table, expiry, and active flag.
3. On scan, locate the hash, verify restaurant and table availability, regenerate the session ID, and store only validated context in the session.
4. On order submission, read restaurant and table only from the QR session; ignore browser-supplied IDs.
5. Reject invalid, expired, disabled, or mismatched tokens with an "Invalid QR code" page.

## Order integrity

Re-fetch selected food items on the server, ensure that they are available for the session restaurant, calculate prices and taxes on the server, and create `orders`, `order_items`, and status history in one database transaction.

## Deployment baseline

Use HTTPS, environment variables outside version control, production error logging without stack traces to users, restricted database credentials, routine backups, and tested restore steps.
