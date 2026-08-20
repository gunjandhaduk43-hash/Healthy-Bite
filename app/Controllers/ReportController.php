<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use PDO;

final class ReportController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        // Resolve Date range filters
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';

        // Validate date format, fallback to default last 30 days if empty
        if ($startDate === '' || !$this->isValidDate($startDate)) {
            $startDate = date('Y-m-d', strtotime('-30 days'));
        }
        if ($endDate === '' || !$this->isValidDate($endDate)) {
            $endDate = date('Y-m-d');
        }

        $connection = Database::connection();

        // 1. Fetch Metrics (Orders count, Revenue, Average Order)
        $metricsStmt = $connection->prepare(
            'SELECT 
                COUNT(*) AS orders_count, 
                COALESCE(SUM(total_amount), 0) AS revenue, 
                COALESCE(AVG(total_amount), 0) AS average_order 
             FROM orders 
             WHERE restaurant_id = :restaurant_id 
               AND status <> "cancelled"
               AND DATE(created_at) >= :start_date
               AND DATE(created_at) <= :end_date'
        );
        $metricsStmt->execute([
            'restaurant_id' => $restaurantId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        $metrics = $metricsStmt->fetch() ?: [];

        // 2. Fetch Popular Menu Items
        $popularStmt = $connection->prepare(
            'SELECT 
                order_items.item_name,
                COUNT(order_items.id) AS order_occurrences,
                SUM(order_items.quantity) AS total_quantity,
                SUM(order_items.line_total) AS total_sales
             FROM order_items
             INNER JOIN orders ON orders.id = order_items.order_id
             WHERE orders.restaurant_id = :restaurant_id
               AND orders.status <> "cancelled"
               AND DATE(orders.created_at) >= :start_date
               AND DATE(orders.created_at) <= :end_date
             GROUP BY order_items.food_item_id, order_items.item_name
             ORDER BY total_quantity DESC, total_sales DESC
             LIMIT 8'
        );
        $popularStmt->execute([
            'restaurant_id' => $restaurantId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        $popularItems = $popularStmt->fetchAll() ?: [];

        // 3. Fetch Daily Sales (for micro charts / reports list)
        $dailySalesStmt = $connection->prepare(
            'SELECT 
                DATE(created_at) AS order_date,
                COUNT(*) AS day_order_count,
                SUM(total_amount) AS day_revenue
             FROM orders
             WHERE restaurant_id = :restaurant_id
               AND status <> "cancelled"
               AND DATE(created_at) >= :start_date
               AND DATE(created_at) <= :end_date
             GROUP BY DATE(created_at)
             ORDER BY order_date DESC'
        );
        $dailySalesStmt->execute([
            'restaurant_id' => $restaurantId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        $dailySales = $dailySalesStmt->fetchAll() ?: [];

        $this->view('dashboard/reports', [
            'title' => 'Analytics Reports',
            'user' => $user,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => $metrics,
            'popularItems' => $popularItems,
            'dailySales' => $dailySales,
        ]);
    }

    private function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
