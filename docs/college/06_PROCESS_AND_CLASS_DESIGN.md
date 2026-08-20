# Process Flow and Class Design

## Process flow: secure QR order

```mermaid
flowchart LR
    QR[QR Token] --> Validate[QR Token Service]
    Validate --> Session[QR Session Middleware]
    Session --> Menu[Menu Controller]
    Menu --> Cart[Customer Cart]
    Cart --> Order[Order Service]
    Order --> DB[(MySQL Transaction)]
    DB --> Queue[Staff Order Queue]
```

## Conceptual class diagram

```mermaid
classDiagram
    class User { +id +restaurantId +role +email +passwordHash }
    class Restaurant { +id +name +approvalStatus }
    class Category { +id +restaurantId +name }
    class FoodItem { +id +categoryId +name +price +available }
    class FoodNutrition { +foodItemId +calories +protein }
    class RestaurantTable { +id +restaurantId +tableName }
    class QrToken { +id +tableId +tokenHash +isActive }
    class Order { +id +tableId +status +totalAmount }
    class OrderItem { +id +orderId +itemName +quantity +unitPrice }
    Restaurant "1" --> "many" User
    Restaurant "1" --> "many" Category
    Category "1" --> "many" FoodItem
    FoodItem "1" --> "1" FoodNutrition
    Restaurant "1" --> "many" RestaurantTable
    RestaurantTable "1" --> "many" QrToken
    RestaurantTable "1" --> "many" Order
    Order "1" --> "many" OrderItem
```

## PHP responsibilities

Controllers receive requests; services validate workflow rules and transactions; repositories issue prepared queries; middleware enforces login, role, CSRF, and QR-session requirements; views render escaped information.
