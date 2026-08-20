# UI Guidelines

## Design principles

Use a clean, modern Bootstrap 5 interface that remains usable on a mobile phone. Customer pages prioritize menu discovery and order clarity; owner and staff pages prioritize fast, low-error operations.

## Visual system

| Element | Rule |
| --- | --- |
| Color | Fresh green for primary actions, neutral backgrounds, red only for destructive/error actions |
| Typography | System sans-serif stack; clear heading hierarchy; body text at least 16px on customer pages |
| Cards | Rounded corners, restrained shadow, consistent padding |
| Images | Fixed aspect ratio, descriptive `alt` text, fallback placeholder |
| Status | Text label plus color; never rely on color alone |

## Customer QR menu

- Display restaurant identity and table context without exposing internal IDs.
- Food cards show image, name, price, diet badge, availability, and quick nutrition summary.
- Food detail view shows ingredients, allergens, preparation time, and full nutrition.
- Keep the cart accessible with a visible item count and an unambiguous total.
- Use large touch targets and a one-column layout below 576px.

## Owner and staff dashboard

- Use a persistent navigation area on desktop and accessible collapsed navigation on mobile.
- Use tables for data-heavy management screens with search, filter, pagination, and empty states.
- Confirm destructive actions and clearly show successful save/error feedback.
- Kitchen order cards must show order number, table name, items, notes, elapsed time, and allowed next status.

## Accessibility

Use semantic landmarks, labels for every form control, keyboard navigation, visible focus indicators, readable contrast, error messages attached to controls, and text alternatives for images and QR downloads.
