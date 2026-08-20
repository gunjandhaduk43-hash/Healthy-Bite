# Demo Data

`001_demo_restaurant_admin.sql` creates or resets the Stage 1 demo restaurant-admin account and its restaurant.

Import it only after `../schema/001_stage_1.sql` has created the `healthy_bite` database.

```text
Email:    admin@healthybite.test
Password: Admin@12345
```

The account uses the current `owner` role because the current interface is the restaurant-owner dashboard. A separate Super Admin dashboard is planned but is not implemented in Stage 1.
