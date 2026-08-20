# Demo Accounts

## Restaurant Admin

Use this account to test the current Healthy Bite dashboard:

| Field | Value |
| --- | --- |
| Name | Healthy Bite Demo Admin |
| Email | `admin@healthybite.test` |
| Password | `Admin@12345` |
| Role | Restaurant owner (restaurant admin) |
| Restaurant | Healthy Bite Cafe |

## Current access

This demo admin can sign in at `/login`, view `/dashboard`, and update the Healthy Bite Cafe profile. The account is intentionally a **restaurant admin/owner**, because the Stage 1 project currently has no separate Super Admin dashboard.

## Resetting demo data

Import `database/seeders/001_demo_restaurant_admin.sql` after importing the Stage 1 schema. It safely creates or resets the demo owner, password, restaurant profile, and owner-to-restaurant link.

Do not use this password for a real deployed restaurant account.
