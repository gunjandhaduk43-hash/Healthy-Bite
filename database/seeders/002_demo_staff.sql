USE healthy_bite;

START TRANSACTION;

SET @demo_restaurant_id = (SELECT id FROM restaurants WHERE owner_user_id = (SELECT id FROM users WHERE email = 'owner@healthybite.test' LIMIT 1) LIMIT 1);

INSERT INTO users (restaurant_id, admin_id, role_id, name, email, password_hash, status)
VALUES (
    COALESCE(@demo_restaurant_id, 1),
    4, -- Staff admin_id
    4, -- Staff role_id
    'Healthy Bite Staff Cook',
    'staff@healthybite.test',
    '$2y$10$p0b3.07zU703k5E/M0B7he4794uD8Jp0J7oU60K8yvGgYnQp3p9xO', -- Admin@12345
    'active'
)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    admin_id = VALUES(admin_id),
    role_id = VALUES(role_id),
    password_hash = VALUES(password_hash),
    status = 'active';

COMMIT;
