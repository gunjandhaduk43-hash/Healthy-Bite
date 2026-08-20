# System Development Stage 2

**Target date:** 29 August 2026

## Scope

- Category CRUD.
- Food item CRUD with availability and image validation.
- Nutrition, ingredients, allergens, and diet type details.
- Restaurant table CRUD.
- Random QR-token generation, stored token hash, QR image generation/print view.

## Evidence to attach

- Category and food-form screenshots.
- Customer-facing food detail screenshot with nutrition/allergen information.
- Table list and generated QR code screenshot.
- Database screenshot for food, nutrition, table, and token tables.
- Tests: invalid food validation, unavailable-food hiding, QR token validity, token tampering rejection.

## Acceptance criteria

An owner can manage their own menu only, generate a unique QR for a table, and a valid QR opens the correct restaurant menu context without exposing internal table IDs.
