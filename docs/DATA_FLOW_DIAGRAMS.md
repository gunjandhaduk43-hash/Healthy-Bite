# Data Flow Diagrams (DFD) — Healthy Bite System

This document provides a concise architectural summary of the essential Data Flow Diagrams (DFDs) for **Healthy Bite** (a multi-tenant PHP & MySQL QR-based Digital Menu and Table Ordering System for Restaurants).

Refer to [docs/college/07_DATA_FLOW_DIAGRAMS.md](file:///d:/Healty%20Bite/docs/college/07_DATA_FLOW_DIAGRAMS.md) for the college submission version with all visual Mermaid diagrams.

---

## DFD Structure Summary

1. **Level 0 (Context Diagram)**: Central `0.0 Healthy Bite System` process connected to 4 External Entities (`Customer`, `Restaurant Owner`, `Kitchen Staff`, `Super Admin`).
2. **Level 1 (Main Systems Overview)**: 8 core processes:
   - `1.0 Validate and Authenticate User`
   - `2.0 Manage Restaurant and Menu Catalog`
   - `3.0 Manage Tables and Issue QR Tokens`
   - `4.0 Scan QR Token and Navigate Menu`
   - `5.0 Validate Cart and Place Order`
   - `6.0 Process Kitchen Orders and Update Status`
   - `7.0 Settle Payment and Generate Receipt`
   - `8.0 Submit Reviews and Generate Reports`
3. **Level 2 (Core Sub-process Breakdown)**:
   - `2nd Level for 1.0`: Login Validation & Owner Registration
   - `2nd Level for 2.0`: Menu Catalog & Table QR Generation
   - `2nd Level for 3.0`: QR Token Validation & Digital Menu Fetch
   - `2nd Level for 4.0`: Server Price Verification & Order Creation
   - `2nd Level for 5.0`: Kitchen Queue Display & Payment Settlement
4. **Level 3 (Critical Process Decomposition)**:
   - `3rd Level for 4.2`: Server-Side Cart Calculation & Price Verification

---

## Primary System Data Stores

| Store ID | Table Name | Description |
| :--- | :--- | :--- |
| **D1** | `users`, `restaurants` | Account credentials, roles (`superadmin`, `owner`, `kitchen_staff`, `waiter_staff`), restaurant profiles, approval status |
| **D2** | `categories`, `food_items` | Food categories, base prices, diet types (`veg`/`non-veg`), nutrition, allergens, availability |
| **D3** | `food_variants`, `food_customizations` | Portion sizes and add-on toppings with price adjustments |
| **D4** | `restaurant_tables`, `qr_tokens` | Table numbers, capacity, seating status, cryptographic QR token hash |
| **D5** | `customers`, `qr_sessions` | Customer guest info (name, phone), QR session identifier, token ID, start time, last seen time |
| **D6** | `orders`, `order_items`, `order_item_customizations`, `order_status_history` | Order header, immutable line item snapshots, selected customization bridge records, status audit log |
| **D7** | `payments` | Payment method (`cash`, `card`, `upi`), transaction status |
| **D8** | `reviews` | Customer star ratings and reviews |
