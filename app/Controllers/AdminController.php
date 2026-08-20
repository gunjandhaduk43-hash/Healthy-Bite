<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Flash;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $user = Auth::user();
        if ($user === null || ($user['role'] ?? '') !== 'superadmin') {
            Flash::set('error', 'Super Admin access required.');
            $this->redirect('/login');
        }

        $db = Database::connection();
        
        // System metrics
        $restaurantsCount = (int) $db->query('SELECT COUNT(*) FROM restaurants')->fetchColumn();
        $approvedCount = (int) $db->query("SELECT COUNT(*) FROM restaurants WHERE approval_status = 'approved'")->fetchColumn();
        $pendingCount = (int) $db->query("SELECT COUNT(*) FROM restaurants WHERE approval_status = 'pending'")->fetchColumn();
        $ordersCount = (int) $db->query('SELECT COUNT(*) FROM orders')->fetchColumn();
        $totalSales = (float) $db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

        $restaurants = $db->query(
            'SELECT r.*, u.name AS owner_name, u.email AS owner_email,
                    COUNT(DISTINCT o.id) AS total_orders
             FROM restaurants r
             LEFT JOIN users u ON u.id = r.owner_user_id
             LEFT JOIN branches b ON b.restaurant_id = r.id
             LEFT JOIN orders o ON o.branch_id = b.id
             GROUP BY r.id
             ORDER BY r.id DESC'
        )->fetchAll();

        $this->view('admin/dashboard', [
            'title' => 'Super Admin Portal — Healthy Bite SaaS Platform',
            'user' => $user,
            'restaurantsCount' => $restaurantsCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'ordersCount' => $ordersCount,
            'totalSales' => $totalSales,
            'restaurants' => $restaurants,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
        ]);
    }

    public function updateStatus(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/admin/dashboard');
        }

        $user = Auth::user();
        if ($user === null || ($user['role'] ?? '') !== 'superadmin') {
            Flash::set('error', 'Super Admin access required.');
            $this->redirect('/login');
        }

        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);
        $status = trim((string) ($_POST['approval_status'] ?? 'approved'));

        $allowedStatuses = ['pending', 'approved', 'suspended'];
        if ($restaurantId > 0 && in_array($status, $allowedStatuses, true)) {
            $stmt = Database::connection()->prepare('UPDATE restaurants SET approval_status = :status WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $restaurantId]);
            Flash::set('success', 'Restaurant approval status updated to ' . ucfirst($status));
        } else {
            Flash::set('error', 'Invalid restaurant or status parameter.');
        }

        $this->redirect('/admin/dashboard');
    }
}
