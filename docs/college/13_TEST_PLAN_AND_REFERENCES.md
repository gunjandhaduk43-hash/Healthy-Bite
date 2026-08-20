# Test Plan, Test Cases, References, and Testing

## Test plan

| Item | Plan |
| --- | --- |
| Objective | Verify correctness, security, usability, and responsive behavior of the MVP |
| Environment | XAMPP Apache/PHP/MySQL, Chrome/Edge, Android/iOS phone browser where available |
| Test data | One approved restaurant, one owner, one staff user, two tables, active/unavailable food items |
| Entry criteria | Stage 4 implementation deployed locally with test database |
| Exit criteria | All critical tests pass; no open blocker for QR ordering, authentication, or order totals |

## Core test cases

| ID | Scenario | Expected result |
| --- | --- | --- |
| TC-01 | Owner registers with valid details | Account/restaurant is stored and password is hashed |
| TC-02 | Login with invalid password | Login denied without revealing which field failed |
| TC-03 | Open owner route while logged out | Redirect to login page |
| TC-04 | Owner attempts another restaurant's record URL | Access denied/no foreign record returned |
| TC-05 | Scan valid active QR | Correct restaurant menu opens and QR session starts |
| TC-06 | Change QR URL token or use expired token | Invalid QR page; ordering unavailable |
| TC-07 | Add unavailable food through crafted request | Server rejects item |
| TC-08 | Submit cart price altered in browser | Server recalculates and ignores altered amount |
| TC-09 | Place valid order | Header, items, and pending history save in one transaction |
| TC-10 | Staff updates own restaurant order | Next allowed status saved and visible to customer |
| TC-11 | Staff updates foreign restaurant order | Access denied |
| TC-12 | Mobile menu at 360px width | Navigation, cards, cart, and forms remain usable |

## Test record format

| Test ID | Date | Tester | Actual result | Pass/Fail | Screenshot/file |
| --- | --- | --- | --- | --- | --- |
| [TC-xx] | [date] | [name] | [result] | [status] | [path] |

## References

- PHP Manual: password hashing, sessions, PDO, `random_bytes()`.
- MySQL 8 Reference Manual: InnoDB, constraints, transactions.
- Bootstrap 5 documentation: responsive layout and accessible components.
- OWASP Cheat Sheet Series: SQL injection prevention, XSS prevention, CSRF prevention, file upload guidance.

Record the access date for every web reference in the final report bibliography.
