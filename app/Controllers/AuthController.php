<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Services\AuthService;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'title' => 'Owner Sign In',
            'error' => Flash::get('error'),
        ], 'auth');
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/login');
        }

        $user = (new AuthService())->authenticate((string) ($_POST['email'] ?? ''), (string) ($_POST['password'] ?? ''));

        if ($user === null) {
            Flash::set('error', 'Invalid email or password.');
            $this->redirect('/login');
        }

        Auth::login($user);
        if (($user['role'] ?? '') === 'superadmin') {
            $this->redirect('/admin/dashboard');
        }
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'title' => 'Create Restaurant Account',
            'errors' => [],
            'old' => [],
        ], 'auth');
    }

    public function register(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::set('error', 'Your form expired. Please try again.');
            $this->redirect('/register');
        }

        $result = (new AuthService())->registerOwner($_POST);

        if ($result['user'] === null) {
            $this->view('auth/register', [
                'title' => 'Create Restaurant Account',
                'errors' => $result['errors'],
                'old' => $this->oldRegistrationInput($_POST),
            ], 'auth');
            return;
        }

        Auth::login($result['user']);
        Flash::set('success', 'Your restaurant account was created. Complete your profile below.');
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            $this->redirect('/dashboard');
        }

        Auth::logout();
        Flash::set('success', 'You have been signed out.');
        $this->redirect('/login');
    }

    /** @param array<string, mixed> $input
     *  @return array<string, string>
     */
    private function oldRegistrationInput(array $input): array
    {
        return [
            'name' => trim((string) ($input['name'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
            'restaurant_name' => trim((string) ($input['restaurant_name'] ?? '')),
            'restaurant_email' => trim((string) ($input['restaurant_email'] ?? '')),
            'phone' => trim((string) ($input['phone'] ?? '')),
            'address' => trim((string) ($input['address'] ?? '')),
        ];
    }
}
