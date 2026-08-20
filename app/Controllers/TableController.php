<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MvpRepository;

final class TableController extends Controller
{
    private MvpRepository $mvpRepository;

    public function __construct()
    {
        $this->mvpRepository = new MvpRepository();
    }

    public function index(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $tables = $this->mvpRepository->tables($restaurantId);

        $this->view('dashboard/tables', [
            'title' => 'Table QR Seating',
            'user' => $user,
            'tables' => $tables,
            'success' => Flash::get('success'),
            'error' => Flash::get('error'),
            'errors' => Flash::get('errors') ?? [],
            'old' => Flash::get('old') ?? [],
            'new_qr_token' => Flash::get('new_qr_token'),
            'new_qr_table' => Flash::get('new_qr_table'),
        ]);
    }

    public function create(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/tables');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $tableName = trim((string) ($_POST['table_number'] ?? $_POST['table_name'] ?? ''));
        $capacityStr = trim((string) ($_POST['capacity'] ?? '2'));
        $errors = [];

        if ($tableName === '') {
            $errors['table_name'] = 'Table label/number is required.';
        } elseif (mb_strlen($tableName) > 80) {
            $errors['table_name'] = 'Table label/number must be 80 characters or fewer.';
        }

        if (!filter_var($capacityStr, FILTER_VALIDATE_INT) || (int) $capacityStr <= 0) {
            $errors['capacity'] = 'Capacity must be a positive integer.';
        }

        if ($errors !== []) {
            Flash::set('errors', $errors);
            Flash::set('old', ['table_name' => $tableName, 'capacity' => $capacityStr]);
            $this->redirect('/dashboard/tables');
        }

        try {
            $capacity = (int) $capacityStr;
            $this->mvpRepository->createTable($restaurantId, $tableName, $capacity);
            Flash::set('success', "Table '$tableName' created successfully.");
        } catch (\Throwable $e) {
            Flash::set('error', 'Failed to create table: ' . $e->getMessage());
        }

        $this->redirect('/dashboard/tables');
    }

    public function issueQr(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/tables');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $tableId = (int) ($_POST['table_id'] ?? 0);
        $tableName = trim((string) ($_POST['table_name'] ?? 'Table'));

        if ($tableId > 0) {
            $token = $this->mvpRepository->issueQrToken($restaurantId, $tableId);
            if ($token !== null) {
                Flash::set('success', 'Secure QR token generated for ' . $tableName);
                Flash::set('new_qr_token', $token);
                Flash::set('new_qr_table', $tableName);
            } else {
                Flash::set('error', 'Failed to generate QR token.');
            }
        } else {
            Flash::set('error', 'Invalid table selected.');
        }

        $this->redirect('/dashboard/tables');
    }

    public function printQr(): void
    {
        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $token = trim((string) ($_GET['token'] ?? ''));
        $tableName = trim((string) ($_GET['table'] ?? 'Table'));

        if ($token === '') {
            Flash::set('error', 'No token provided for printing.');
            $this->redirect('/dashboard/tables');
        }

        // Render printable page directly with a minimal layout
        $this->view('dashboard/qr_print', [
            'title' => 'Print Table QR - ' . $tableName,
            'token' => $token,
            'tableName' => $tableName,
            'restaurantName' => $user['restaurant_name'] ?? 'Healthy Bite Restaurant',
        ], 'auth'); // Use 'auth' layout since it is minimal and lacks sidebar/nav
    }

    public function updateStatus(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/dashboard/tables');
        }

        $user = Auth::user();
        $restaurantId = (int) ($user['restaurant_id'] ?? 0);

        if ($restaurantId === 0) {
            $this->redirect('/login');
        }

        $tableId = (int) ($_POST['table_id'] ?? 0);
        $status = trim((string) ($_POST['status'] ?? 'available'));

        if ($tableId > 0) {
            $this->mvpRepository->updateTableStatus($restaurantId, $tableId, $status);
            Flash::set('success', 'Table status updated to ' . ucfirst(str_replace('_', ' ', $status)));
        } else {
            Flash::set('error', 'Invalid table selected.');
        }

        $this->redirect('/dashboard/tables');
    }
}
