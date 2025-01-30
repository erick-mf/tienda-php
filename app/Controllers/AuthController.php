<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Lib\Security;
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
                $errors['password'] = 'La contraseña es requerida';
            }

            if (empty($errors)) {
                $result = $this->userService->login($email, $password);

                if ($result['success']) {
                    $_SESSION['user_id'] = $result['user']['id'];
                    $_SESSION['user_name'] = $result['user']['nombre'];
                    $_SESSION['user_role'] = $result['user']['rol'];

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

    public function confirmation($token)
    {
        $headers = getallheaders();
        $userToken = Security::validateToken($token);
        if (isset($userToken) && ! empty($userToken)) {
            $email = $userToken->data->email;
            $userConfirmed = $this->userService->findEmail($email);
            if (! $userConfirmed) {
                $this->page->render('auth/confirmation_error', []);
            } else {
                $this->userService->confirmation($email, true);
                $this->page->render('auth/confirmation_success', []);
            }
        }
    }
}
