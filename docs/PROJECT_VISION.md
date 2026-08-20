# Project Vision

## Project title

**Healthy Bite - Smart QR-Based Digital Menu and Food Ordering System**

## Problem statement

Paper menus become outdated quickly, do not provide nutrition or allergen details, and require staff to collect orders manually. A restaurant also needs a simple way to manage menu availability and receive table orders without exposing table identifiers in URLs.

## Proposed solution

Healthy Bite is a PHP and MySQL web application for multiple restaurants. A restaurant owner manages categories, food items, nutrition data, tables, and staff. Each table receives a random QR token. When a customer scans it, the system validates the token, stores the restaurant and table in a server-side session, displays the menu, and accepts an order.

## Objectives

1. Replace paper menus with a responsive digital menu.
2. Show price, ingredients, allergens, diet type, calories, protein, carbohydrates, fat, and preparation time.
3. Let customers place table orders without an account.
4. Give restaurant staff a controlled order-status workflow.
5. Protect data through role checks, prepared statements, CSRF tokens, output escaping, and secure QR tokens.

## Stakeholders

| Stakeholder | Need |
| --- | --- |
| Super administrator | Monitor and approve restaurant accounts |
| Restaurant owner | Manage the restaurant, menu, tables, and staff |
| Restaurant staff | View assigned orders and update their status |
| Customer | Browse a truthful menu and place a convenient table order |

## Scope

### BCA Semester V MVP

- Restaurant registration and owner login
- Restaurant profile and basic staff accounts
- Category, food item, nutrition, ingredient, and allergen management
- Table management and random QR token generation
- QR menu, cart, order placement, and status tracking
- Staff order queue and basic sales/order reports

### Future scope

Online payments, inventory, loyalty, coupons, delivery, reservations, multi-branch operations, advanced analytics, notifications, and AI recommendations are planned only after the MVP is stable.

## Success criteria

- A valid table QR opens only its restaurant menu.
- An order always belongs to the server-validated QR session and table.
- Owners cannot access another restaurant's records.
- Staff can move orders only through approved statuses.
- The customer menu works on a mobile phone at typical restaurant-table screen widths.
