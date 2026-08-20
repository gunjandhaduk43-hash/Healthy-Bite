USE healthy_bite;

START TRANSACTION;

SET @demo_restaurant_id = (SELECT id FROM restaurants WHERE owner_user_id = (SELECT id FROM users WHERE email = 'admin@healthybite.test' LIMIT 1) LIMIT 1);

INSERT INTO users (restaurant_id, role_id, name, email, password_hash, status)
VALUES (
    @demo_restaurant_id,
    3, -- Staff role_id
    'Healthy Bite Staff Cook',
    'staff@healthybite.test',
    '$2y$10$WzOve17rEd97bNnGwjh1U.w2WAHSS/GzOtMtqByzNbdlynhYDU2t2', -- Staff@12345
    'active'
)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    password_hash = VALUES(password_hash),
    status = 'active';

COMMIT;
