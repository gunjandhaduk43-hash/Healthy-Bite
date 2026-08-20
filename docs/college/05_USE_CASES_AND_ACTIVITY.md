# Use Case and Activity Diagrams

## Primary actors

- Super administrator
- Restaurant owner
- Restaurant staff
- Customer

## Use cases

| Actor | Use cases |
| --- | --- |
| Super administrator | Approve/suspend restaurants, view platform records |
| Owner | Register/login, manage profile, menu, nutrition, tables, QR, staff, reports |
| Staff | Login, view assigned restaurant orders, change status |
| Customer | Scan QR, browse menu, view nutrition, manage cart, place order, view status |

## Customer ordering activity

```mermaid
flowchart TD
    A[Scan QR code] --> B{Token valid?}
    B -- No --> C[Show invalid QR page]
    B -- Yes --> D[Create secure QR session]
    D --> E[Browse available menu]
    E --> F[Add/update cart]
    F --> G{Place order?}
    G -- No --> E
    G -- Yes --> H[Validate session and menu items]
    H --> I[Create order transaction]
    I --> J[Show order confirmation]
```

## Owner menu-management activity

```mermaid
flowchart TD
    A[Owner logs in] --> B[Open food management]
    B --> C[Enter food and nutrition details]
    C --> D{Validation successful?}
    D -- No --> C
    D -- Yes --> E[Save item]
    E --> F[Show updated menu list]
```
