<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\UserService;
use Exception;

class AuthController
{
    private UserService $userService;

    private Pages $page;

    public function __construct()
    {
        $this->userService = new UserService;
        $this->page = new Pages;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['user']['email'] ?? '';
            $password = $_POST['user']['password'] ?? '';

            $errors = [];

            if (empty($email)) {
                $errors['email'] = 'El email es requerido';
            }
            if (empty($password)) {
                $errors['clave'] = 'La contraseña es requerida';
            }

            if (empty($errors)) {
                $result = $this->userService->login($email, $password);

                if ($result['success']) {
                    $_SESSION['user_id'] = $result['user']['id'];
                    $_SESSION['user_name'] = $result['user']['nombre'];
                    $_SESSION['user_rol'] = $result['user']['rol'];

                    header('Location: /');
                    exit;
                } else {
                    $errors['general'] = $result['error'];
                }
            }

            $this->page->render('auth/login', ['errors' => $errors]);
        } else {
            $this->page->render('auth/login', []);
        }
    }

    public function register(): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['user']['name'] ?? '',
                'surnames' => $_POST['user']['surnames'] ?? '',
                'address' => $_POST['user']['address'] ?? '',
                'email' => $_POST['user']['email'] ?? '',
                'phone' => $_POST['user']['phone'] ?? '',
                'password' => $_POST['user']['password'] ?? '',
                'role' => $_POST['user']['role'] ?? 'client',
            ];

            try {
                $result = $this->userService->register($userData);
                if ($result['success']) {

                    header('Location: /login');
                    exit;
                } else {
                    $this->page->render('auth/register', ['errors' => $result['errors']]);
                }
            } catch (Exception $e) {
                $this->page->render('auth/register', ['error' => $e->getMessage()]);
            }
        } else {
            $this->page->render('auth/register', []);
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
