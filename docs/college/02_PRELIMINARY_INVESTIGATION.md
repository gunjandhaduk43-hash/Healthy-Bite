# Preliminary Investigation

## Existing system

Many restaurants use paper menus and manual order-taking. Paper menus are expensive to reprint, can be out of date, do not communicate nutrition/allergens well, and create delays during busy periods. Basic QR menus often only display a static PDF and do not securely identify the table for orders.

## Proposed system

Healthy Bite provides a dynamic QR menu connected to MySQL. The owner updates menu data once; customers see current availability and can order from a validated table session. Staff receive a structured queue and update status.

## Problem definition

The system must provide a convenient mobile ordering experience while preventing a customer from changing a visible URL to submit an order for another table.

## Investigation conclusion

The solution is technically feasible with PHP, MySQL, XAMPP, Bootstrap, and a QR generation library. It is suitable for a BCA project because it uses core web programming, database, session, security, and software-engineering concepts.
