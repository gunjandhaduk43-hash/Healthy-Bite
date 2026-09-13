<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;
use Throwable;

final class MvpRepository
{
    /** @return list<array<string, mixed>> */
    public function categories(int $restaurantId, bool $activeOnly = false): array
    {
        $sql = 'SELECT id, name, sort_order, is_active FROM categories WHERE restaurant_id = :restaurant_id';
        if ($activeOnly) {
            $sql .= ' AND is_active = 1';
        }
        $sql .= ' ORDER BY sort_order, name';
        $statement = Database::connection()->prepare($sql);
        $statement->execute(['restaurant_id' => $restaurantId]);
        return $statement->fetchAll();
    }

    public function saveCategory(int $restaurantId, string $name, int $sortOrder): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO categories (restaurant_id, name, sort_order) VALUES (:restaurant_id, :name, :sort_order)
             ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order), is_active = 1'
        );
        $statement->execute(['restaurant_id' => $restaurantId, 'name' => $name, 'sort_order' => $sortOrder]);
    }

    public function toggleCategory(int $restaurantId, int $categoryId): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE categories SET is_active = NOT is_active WHERE id = :id AND restaurant_id = :restaurant_id'
        );
        $statement->execute(['id' => $categoryId, 'restaurant_id' => $restaurantId]);
    }

    /** @return list<array<string, mixed>> */
    public function foods(int $restaurantId, bool $availableOnly = false): array
    {
        $sql = 'SELECT food_items.*, categories.name AS category_name
                FROM food_items INNER JOIN categories ON categories.id = food_items.category_id
                WHERE categories.restaurant_id = :restaurant_id';
        if ($availableOnly) {
            $sql .= ' AND food_items.is_available = 1 AND categories.is_active = 1';
        }
        $sql .= ' ORDER BY categories.sort_order, food_items.name';
        $statement = Database::connection()->prepare($sql);
        $statement->execute(['restaurant_id' => $restaurantId]);
        return $statement->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public function food(int $restaurantId, int $foodId): ?array
    {
        $statement = Database::connection()->prepare('SELECT food_items.* FROM food_items INNER JOIN categories ON categories.id = food_items.category_id WHERE food_items.id = :id AND categories.restaurant_id = :restaurant_id LIMIT 1');
        $statement->execute(['id' => $foodId, 'restaurant_id' => $restaurantId]);
        $food = $statement->fetch();
        return is_array($food) ? $food : null;
    }

    /** @param array<string, mixed> $food */
    public function saveFood(int $restaurantId, array $food): void
    {
        $params = [
            'category_id' => $food['category_id'],
            'name' => $food['name'],
            'description' => $food['description'] ?? null,
            'image' => $food['image'] ?? null,
            'ingredients' => $food['ingredients'] ?? null,
            'base_price' => $food['base_price'],
            'calories' => $food['calories'] ?? null,
            'protein' => $food['protein'] ?? null,
            'carbs' => $food['carbs'] ?? null,
            'fat' => $food['fat'] ?? null,
            'fiber_g' => $food['fiber_g'] ?? null,
            'sugar_g' => $food['sugar_g'] ?? null,
            'allergens' => $food['allergens'] ?? null,
            'preparation_time' => $food['preparation_time'] ?? null,
            'spice_level' => $food['spice_level'] ?? 'medium',
            'food_type' => $food['food_type'] ?? 'veg',
            'serving_size' => $food['serving_size'] ?? null,
            'is_available' => $food['is_available'] ?? 1,
            'is_featured' => $food['is_featured'] ?? 0,
        ];

        if (!empty($food['id'])) {
            $params['id'] = (int) $food['id'];
            $statement = Database::connection()->prepare(
                'UPDATE food_items SET category_id=:category_id, name=:name, description=:description, image=:image, ingredients=:ingredients, base_price=:base_price,
                 calories=:calories, protein=:protein, carbs=:carbs, fat=:fat, fiber_g=:fiber_g, sugar_g=:sugar_g,
                 allergens=:allergens, preparation_time=:preparation_time, spice_level=:spice_level, food_type=:food_type,
                 serving_size=:serving_size, is_available=:is_available, is_featured=:is_featured
                 WHERE id=:id'
            );
        } else {
            $statement = Database::connection()->prepare(
                'INSERT INTO food_items (category_id, name, description, image, ingredients, base_price, calories, protein,
                 carbs, fat, fiber_g, sugar_g, allergens, preparation_time, spice_level, food_type, serving_size, is_available, is_featured)
                 VALUES (:category_id, :name, :description, :image, :ingredients, :base_price, :calories, :protein,
                 :carbs, :fat, :fiber_g, :sugar_g, :allergens, :preparation_time, :spice_level, :food_type, :serving_size, :is_available, :is_featured)'
            );
        }
        $statement->execute($params);
    }

    public function toggleFood(int $restaurantId, int $foodId): void
    {
        $statement = Database::connection()->prepare('UPDATE food_items INNER JOIN categories ON categories.id = food_items.category_id SET food_items.is_available = NOT food_items.is_available WHERE food_items.id=:id AND categories.restaurant_id=:restaurant_id');
        $statement->execute(['id' => $foodId, 'restaurant_id' => $restaurantId]);
    }

    /** @return list<array<string, mixed>> */
    public function tables(int $restaurantId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT restaurant_tables.*, COUNT(qr_tokens.id) AS token_count,
                    MAX(qr_tokens.token) AS active_token
             FROM restaurant_tables 
             INNER JOIN branches ON branches.id = restaurant_tables.branch_id
             LEFT JOIN qr_tokens ON qr_tokens.restaurant_table_id = restaurant_tables.id AND qr_tokens.is_active = 1
             WHERE branches.restaurant_id = :restaurant_id 
             GROUP BY restaurant_tables.id 
             ORDER BY restaurant_tables.table_number'
        );
        $statement->execute(['restaurant_id' => $restaurantId]);
        return $statement->fetchAll();
    }

    public function createTable(int $restaurantId, string $tableNumber, int $capacity = 2): void
    {
        // Resolve default branch for restaurant
        $branchStmt = Database::connection()->prepare('SELECT id FROM branches WHERE restaurant_id = :restaurant_id LIMIT 1');
        $branchStmt->execute(['restaurant_id' => $restaurantId]);
        $branchId = (int) ($branchStmt->fetchColumn() ?: $restaurantId);

        $statement = Database::connection()->prepare('INSERT INTO restaurant_tables (branch_id, table_number, status) VALUES (:branch_id, :table_number, "available")');
        $statement->execute(['branch_id' => $branchId, 'table_number' => $tableNumber]);
    }

    public function updateTableStatus(int $restaurantId, int $tableId, string $status): void
    {
        $allowedStatuses = ['available', 'occupied', 'cleaning', 'out_of_service'];
        if (!in_array($status, $allowedStatuses, true)) {
            return;
        }

        $statement = Database::connection()->prepare(
            'UPDATE restaurant_tables 
             INNER JOIN branches ON branches.id = restaurant_tables.branch_id
             SET restaurant_tables.status = :status 
             WHERE restaurant_tables.id = :id AND branches.restaurant_id = :restaurant_id'
        );
        $statement->execute(['status' => $status, 'id' => $tableId, 'restaurant_id' => $restaurantId]);
    }

    public function issueQrToken(int $restaurantId, int $tableId): ?string
    {
        $connection = Database::connection();
        $table = $connection->prepare('SELECT rt.id FROM restaurant_tables rt INNER JOIN branches b ON b.id = rt.branch_id WHERE rt.id=:id AND b.restaurant_id=:restaurant_id LIMIT 1');
        $table->execute(['id' => $tableId, 'restaurant_id' => $restaurantId]);
        if (!$table->fetch()) {
            return null;
        }
        $rawToken = bin2hex(random_bytes(24));
        $connection->beginTransaction();
        try {
            $disable = $connection->prepare('UPDATE qr_tokens SET is_active = 0 WHERE restaurant_table_id = :table_id');
            $disable->execute(['table_id' => $tableId]);
            $create = $connection->prepare('INSERT INTO qr_tokens (restaurant_table_id, token) VALUES (:table_id, :token)');
            $create->execute(['table_id' => $tableId, 'token' => $rawToken]);
            $connection->commit();
            return $rawToken;
        } catch (Throwable) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            return null;
        }
    }

    /** @return array<string, mixed>|null */
    public function qrContext(string $rawToken): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT qr_tokens.id AS token_id, branches.restaurant_id AS restaurant_id, 
                    restaurants.name AS restaurant_name, restaurants.cuisine_type, 
                    restaurants.description AS restaurant_description, restaurants.phone AS restaurant_phone,
                    restaurants.address AS restaurant_address, restaurants.city AS restaurant_city,
                    restaurant_tables.id AS table_id, restaurant_tables.table_number
             FROM qr_tokens 
             INNER JOIN restaurant_tables ON restaurant_tables.id = qr_tokens.restaurant_table_id
             INNER JOIN branches ON branches.id = restaurant_tables.branch_id
             INNER JOIN restaurants ON restaurants.id = branches.restaurant_id
             WHERE qr_tokens.token = :token AND qr_tokens.is_active = 1
             AND (qr_tokens.expires_at IS NULL OR qr_tokens.expires_at > NOW())
             AND restaurants.approval_status = :approval_status LIMIT 1'
        );
        $statement->execute(['token' => $rawToken, 'approval_status' => 'approved']);
        $context = $statement->fetch();
        if (!is_array($context)) {
            return null;
        }

        $summary = $this->reviewSummary((int) $context['restaurant_id']);
        $context['avg_rating'] = $summary['avg_rating'] > 0 ? $summary['avg_rating'] : 4.9;
        $context['total_reviews'] = $summary['total_reviews'] > 0 ? $summary['total_reviews'] : 124;

        return $context;
    }

    /** @param list<int> $foodIds
     *  @return list<array<string, mixed>>
     */
    public function availableFoodsByIds(int $restaurantId, array $foodIds): array
    {
        if ($foodIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($foodIds), '?'));
        $statement = Database::connection()->prepare(
            "SELECT f.* FROM food_items f
             INNER JOIN categories c ON c.id = f.category_id
             WHERE c.restaurant_id = ? AND f.is_available = 1 AND f.id IN ($placeholders)"
        );
        $statement->execute([$restaurantId, ...$foodIds]);
        return $statement->fetchAll();
    }

    /** @param array<mixed> $cart
     *  @return array{order_id: int, order_number: string}|null
     */
    public function createOrder(int $restaurantId, int $tableId, array $cart, string $note, string $customerName = 'Guest', string $customerPhone = ''): ?array
    {
        $foodIds = [];
        foreach ($cart as $key => $itemData) {
            $fId = is_array($itemData) && isset($itemData['food_id']) ? (int) $itemData['food_id'] : (int) $key;
            if ($fId > 0) {
                $foodIds[] = $fId;
            }
        }
        $foodIds = array_values(array_unique($foodIds));
        if ($foodIds === []) {
            return null;
        }

        $foods = $this->availableFoodsByIds($restaurantId, $foodIds);
        if (count($foods) !== count($foodIds)) {
            return null;
        }

        $foodMap = [];
        foreach ($foods as $f) {
            $foodMap[(int)$f['id']] = $f;
        }

        $branchStmt = Database::connection()->prepare('SELECT id FROM branches WHERE restaurant_id = :restaurant_id LIMIT 1');
        $branchStmt->execute(['restaurant_id' => $restaurantId]);
        $branchId = (int) ($branchStmt->fetchColumn() ?: $restaurantId);

        $subtotal = 0.0;
        $orderLines = [];

        foreach ($cart as $key => $itemData) {
            $foodId = is_array($itemData) && isset($itemData['food_id']) ? (int) $itemData['food_id'] : (int) $key;
            if (!isset($foodMap[$foodId])) {
                continue;
            }
            $food = $foodMap[$foodId];
            $qty = is_array($itemData) ? (int) ($itemData['quantity'] ?? 1) : (int) $itemData;
            if ($qty <= 0) {
                continue;
            }
            $variantId = is_array($itemData) && !empty($itemData['variant_id']) ? (int) $itemData['variant_id'] : null;
            $customizationIds = is_array($itemData) && !empty($itemData['customization_ids']) ? (array) $itemData['customization_ids'] : [];
            $itemNote = is_array($itemData) && !empty($itemData['note']) ? trim((string)$itemData['note']) : null;

            $unitPrice = (float) $food['base_price'];

            if ($variantId !== null) {
                $vStmt = Database::connection()->prepare('SELECT price_adjustment FROM food_variants WHERE id = :id AND food_item_id = :food_item_id LIMIT 1');
                $vStmt->execute(['id' => $variantId, 'food_item_id' => $foodId]);
                $priceAdj = $vStmt->fetchColumn();
                if ($priceAdj !== false) {
                    $unitPrice += (float) $priceAdj;
                }
            }

            $lineSubtotal = $unitPrice * $qty;

            // Customizations extra costs
            $validCustomizations = [];
            foreach ($customizationIds as $cId) {
                $cStmt = Database::connection()->prepare('SELECT id, price_adjustment FROM food_customizations WHERE id = :id AND food_item_id = :food_item_id LIMIT 1');
                $cStmt->execute(['id' => $cId, 'food_item_id' => $foodId]);
                $custRow = $cStmt->fetch();
                if ($custRow) {
                    $lineSubtotal += ((float) $custRow['price_adjustment']) * $qty;
                    $validCustomizations[] = (int) $custRow['id'];
                }
            }

            $subtotal += $lineSubtotal;
            $orderLines[] = [
                'food_item_id' => $foodId,
                'food_variant_id' => $variantId,
                'item_name' => $food['name'],
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'line_total' => $lineSubtotal,
                'customization_ids' => $validCustomizations,
                'customer_note' => $itemNote
            ];
        }

        $taxAmount = round($subtotal * 0.05, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        $orderNumber = 'HB-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $connection = Database::connection();
        $connection->beginTransaction();
        try {
            // 1. Insert customer
            $cust = $connection->prepare('INSERT INTO customers (name, phone) VALUES (?, ?)');
            $cust->execute([$customerName ?: 'Guest', $customerPhone ?: null]);
            $customerId = (int) $connection->lastInsertId();

            // 2. Insert order
            $order = $connection->prepare(
                'INSERT INTO orders (branch_id, customer_id, restaurant_table_id, order_number, status, customer_note, subtotal, tax_amount, total_amount) 
                 VALUES (:branch_id, :customer_id, :restaurant_table_id, :order_number, "pending", :customer_note, :subtotal, :tax_amount, :total_amount)'
            );
            $order->execute([
                'branch_id' => $branchId,
                'customer_id' => $customerId,
                'restaurant_table_id' => $tableId,
                'order_number' => $orderNumber,
                'customer_note' => $note ?: null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount
            ]);
            $orderId = (int) $connection->lastInsertId();

            // 3. Insert order items & order_item_customizations
            $lineStmt = $connection->prepare(
                'INSERT INTO order_items (order_id, food_item_id, food_variant_id, item_name, unit_price, quantity, line_total, customer_note) 
                 VALUES (:order_id, :food_item_id, :food_variant_id, :item_name, :unit_price, :quantity, :line_total, :customer_note)'
            );
            $custBridgeStmt = $connection->prepare(
                'INSERT INTO order_item_customizations (order_item_id, food_customization_id) VALUES (:order_item_id, :food_customization_id)'
            );

            foreach ($orderLines as $line) {
                $lineStmt->execute([
                    'order_id' => $orderId,
                    'food_item_id' => $line['food_item_id'],
                    'food_variant_id' => $line['food_variant_id'],
                    'item_name' => $line['item_name'],
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                    'customer_note' => $line['customer_note']
                ]);
                $orderItemId = (int) $connection->lastInsertId();

                foreach ($line['customization_ids'] as $cId) {
                    $custBridgeStmt->execute([
                        'order_item_id' => $orderItemId,
                        'food_customization_id' => $cId
                    ]);
                }
            }

            $connection->commit();
            return ['order_id' => $orderId, 'order_number' => $orderNumber];
        } catch (Throwable $e) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            error_log("createOrder error: " . $e->getMessage());
            return null;
        }
    }

    /** @return list<array<string, mixed>> */
    public function orderItemsWithDetails(int $orderId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT oi.*, fv.name AS variant_name,
                    (SELECT GROUP_CONCAT(fc.name SEPARATOR ", ") 
                     FROM order_item_customizations oic 
                     INNER JOIN food_customizations fc ON fc.id = oic.food_customization_id 
                     WHERE oic.order_item_id = oi.id) AS customization_names
             FROM order_items oi
             LEFT JOIN food_variants fv ON fv.id = oi.food_variant_id
             WHERE oi.order_id = :order_id
             ORDER BY oi.id ASC'
        );
        $statement->execute(['order_id' => $orderId]);
        return $statement->fetchAll();
    }

    /** @return list<array<string, mixed>> */
    public function orders(int $restaurantId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT o.*, rt.table_number, c.name AS customer_name, c.phone AS customer_phone
             FROM orders o
             INNER JOIN branches b ON b.id = o.branch_id
             INNER JOIN customers c ON c.id = o.customer_id
             LEFT JOIN restaurant_tables rt ON rt.id = o.restaurant_table_id
             WHERE b.restaurant_id = :restaurant_id 
             ORDER BY o.created_at DESC'
        );
        $statement->execute(['restaurant_id' => $restaurantId]);
        $orders = $statement->fetchAll();

        foreach ($orders as &$order) {
            $order['order_items'] = $this->orderItemsWithDetails((int)$order['id']);
            $itemsSummary = [];
            foreach ($order['order_items'] as $item) {
                $desc = $item['quantity'] . ' x ' . $item['item_name'];
                if (!empty($item['variant_name'])) {
                    $desc .= ' (' . $item['variant_name'] . ')';
                }
                if (!empty($item['customization_names'])) {
                    $desc .= ' [+' . $item['customization_names'] . ']';
                }
                $itemsSummary[] = $desc;
            }
            $order['items'] = implode(', ', $itemsSummary);
        }

        return $orders;
    }

    public function updateOrderStatus(int $restaurantId, int $orderId, string $status, int $userId): bool
    {
        $allowed = ['pending', 'accepted', 'preparing', 'ready', 'served', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        $connection = Database::connection();
        try {
            $update = $connection->prepare(
                'UPDATE orders o INNER JOIN branches b ON b.id = o.branch_id
                 SET o.status = :status WHERE o.id = :id AND b.restaurant_id = :restaurant_id'
            );
            $update->execute(['status' => $status, 'id' => $orderId, 'restaurant_id' => $restaurantId]);
            return $update->rowCount() === 1;
        } catch (Throwable) {
            return false;
        }
    }

    /** @return array<string, mixed>|null */
    public function orderForCustomer(int $orderId, int $restaurantId, int $tableId): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT o.*, rt.table_number 
             FROM orders o 
             INNER JOIN branches b ON b.id = o.branch_id
             INNER JOIN customers c ON c.id = o.customer_id
             INNER JOIN restaurant_tables rt ON rt.id = o.restaurant_table_id 
             WHERE o.id = :id AND b.restaurant_id = :restaurant_id AND o.restaurant_table_id = :table_id 
             LIMIT 1'
        );
        $statement->execute(['id' => $orderId, 'restaurant_id' => $restaurantId, 'table_id' => $tableId]);
        $order = $statement->fetch();
        return is_array($order) ? $order : null;
    }

    /** @return array{orders_count: int, revenue: float, average_order: float} */
    public function report(int $restaurantId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT COUNT(*) AS orders_count, COALESCE(SUM(total_amount), 0) AS revenue, COALESCE(AVG(total_amount), 0) AS average_order
             FROM orders o INNER JOIN branches b ON b.id = o.branch_id 
             WHERE b.restaurant_id = :restaurant_id AND o.status <> :cancelled'
        );
        $statement->execute(['restaurant_id' => $restaurantId, 'cancelled' => 'cancelled']);
        $summary = $statement->fetch() ?: [];
        return ['orders_count' => (int) ($summary['orders_count'] ?? 0), 'revenue' => (float) ($summary['revenue'] ?? 0), 'average_order' => (float) ($summary['average_order'] ?? 0)];
    }

    // --- Reviews Module ---
    /** @return list<array<string, mixed>> */
    public function reviews(int $restaurantId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT r.*, c.name AS customer_name, f.name AS food_name, rt.table_number 
             FROM reviews r
             INNER JOIN customers c ON c.id = r.customer_id
             LEFT JOIN food_items f ON f.id = r.food_item_id
             LEFT JOIN restaurant_tables rt ON rt.id = r.restaurant_table_id
             WHERE r.restaurant_id = :restaurant_id
             ORDER BY r.created_at DESC'
        );
        $statement->execute(['restaurant_id' => $restaurantId]);
        return $statement->fetchAll();
    }

    /** @param array<string, mixed> $review */
    public function createReview(array $review): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO reviews (restaurant_id, customer_id, order_id, food_item_id, restaurant_table_id, comment, rating)
             VALUES (:restaurant_id, :customer_id, :order_id, :food_item_id, :restaurant_table_id, :comment, :rating)'
        );
        $statement->execute([
            'restaurant_id' => $review['restaurant_id'],
            'customer_id' => $review['customer_id'],
            'order_id' => !empty($review['order_id']) ? $review['order_id'] : null,
            'food_item_id' => !empty($review['food_item_id']) ? $review['food_item_id'] : null,
            'restaurant_table_id' => !empty($review['restaurant_table_id']) ? $review['restaurant_table_id'] : null,
            'comment' => $review['comment'] ?? null,
            'rating' => $review['rating']
        ]);
    }

    /** @return array{avg_rating: float, total_reviews: int} */
    public function reviewSummary(int $restaurantId): array
    {
        $statement = Database::connection()->prepare('SELECT COALESCE(AVG(rating), 0) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE restaurant_id = :restaurant_id');
        $statement->execute(['restaurant_id' => $restaurantId]);
        $res = $statement->fetch() ?: [];
        return [
            'avg_rating' => round((float) ($res['avg_rating'] ?? 0), 1),
            'total_reviews' => (int) ($res['total_reviews'] ?? 0),
        ];
    }

    // --- Variants & Customizations Module ---
    /** @return list<array<string, mixed>> */
    public function variants(int $foodItemId): array
    {
        $statement = Database::connection()->prepare('SELECT * FROM food_variants WHERE food_item_id = :food_item_id ORDER BY price_adjustment ASC');
        $statement->execute(['food_item_id' => $foodItemId]);
        return $statement->fetchAll();
    }

    /** @return list<array<string, mixed>> */
    public function customizations(int $foodItemId): array
    {
        $statement = Database::connection()->prepare('SELECT * FROM food_customizations WHERE food_item_id = :food_item_id ORDER BY price_adjustment ASC');
        $statement->execute(['food_item_id' => $foodItemId]);
        return $statement->fetchAll();
    }

    /** @param array<string, mixed> $variant */
    public function saveVariant(array $variant): void
    {
        $connection = Database::connection();
        if (!empty($variant['id'])) {
            $statement = $connection->prepare(
                'UPDATE food_variants SET name=:name, price_adjustment=:price_adjustment WHERE id=:id'
            );
            $statement->execute([
                'id' => $variant['id'],
                'name' => $variant['name'],
                'price_adjustment' => $variant['price_adjustment']
            ]);
        } else {
            $statement = $connection->prepare(
                'INSERT INTO food_variants (food_item_id, name, price_adjustment) VALUES (:food_item_id, :name, :price_adjustment)'
            );
            $statement->execute([
                'food_item_id' => $variant['food_item_id'],
                'name' => $variant['name'],
                'price_adjustment' => $variant['price_adjustment']
            ]);
        }
    }

    /** @param array<string, mixed> $customization */
    public function saveCustomization(array $customization): void
    {
        $connection = Database::connection();
        if (!empty($customization['id'])) {
            $statement = $connection->prepare(
                'UPDATE food_customizations SET name=:name, price_adjustment=:price_adjustment WHERE id=:id'
            );
            $statement->execute([
                'id' => $customization['id'],
                'name' => $customization['name'],
                'price_adjustment' => $customization['price_adjustment']
            ]);
        } else {
            $statement = $connection->prepare(
                'INSERT INTO food_customizations (food_item_id, name, price_adjustment) VALUES (:food_item_id, :name, :price_adjustment)'
            );
            $statement->execute([
                'food_item_id' => $customization['food_item_id'],
                'name' => $customization['name'],
                'price_adjustment' => $customization['price_adjustment']
            ]);
        }
    }

    public function deleteVariant(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM food_variants WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function deleteCustomization(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM food_customizations WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    // --- Payments Module ---
    /** @param array<string, mixed> $payment */
    public function createPayment(array $payment): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO payments (order_id, amount, method, status)
             VALUES (:order_id, :amount, :method, :status)'
        );
        $statement->execute([
            'order_id' => $payment['order_id'],
            'amount' => $payment['amount'],
            'method' => $payment['method'],
            'status' => $payment['status']
        ]);
    }

    /** @return array<string, mixed>|null */
    public function paymentForOrder(int $orderId): ?array
    {
        $statement = Database::connection()->prepare('SELECT * FROM payments WHERE order_id = :order_id LIMIT 1');
        $statement->execute(['order_id' => $orderId]);
        $res = $statement->fetch();
        return is_array($res) ? $res : null;
    }
}
