<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Repositories\MvpRepository;

final class LandingController extends Controller
{
    private MvpRepository $mvpRepository;

    public function __construct()
    {
        $this->mvpRepository = new MvpRepository();
    }

    public function index(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user !== null && isset($user['role']) && $user['role'] === 'superadmin') {
                $this->redirect('/admin/dashboard');
            }
            $this->redirect('/dashboard');
        }

        // Get demo QR token for interactive live demo link
        $demoTables = $this->mvpRepository->tables(1);
        $demoToken = null;
        if (!empty($demoTables)) {
            $demoToken = $this->mvpRepository->issueQrToken(1, (int) $demoTables[0]['id']);
        }

        $this->view('landing', [
            'title' => 'Healthy Bite — Digital Restaurant Menu & Food Ordering System',
            'demoToken' => $demoToken,
        ], 'auth');
    }
}
