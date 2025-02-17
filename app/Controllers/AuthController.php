<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Lib\Security;
use App\Services\UserService;
use Exception;

/**
 * Clase AuthController
 *
 * Esta clase maneja las operaciones de autenticación y registro de usuarios.
 */
class AuthController
{
    private UserService $userService;

    private OrderController $orderController;

    private Pages $page;

    public function __construct()
    {
        $this->userService = new UserService;
        $this->orderController = new OrderController;
        $this->page = new Pages;
    }

    /**
     * Maneja el proceso de inicio de sesión.
     *
     * Procesa las solicitudes POST de inicio de sesión y renderiza el formulario de login.
     */
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
                    $_SESSION['user_address'] = $result['user']['direccion'];
                    $this->orderController->getOrderTemp($result['user']['id']);

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

    /**
     * Maneja el proceso de registro de usuarios.
     *
     * Procesa las solicitudes POST de registro y renderiza el formulario de registro.
     */
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
                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                        header('Location: /admin/users');
                        exit;
                    } else {
                        header('Location: /login');
                        exit;
                    }
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

    /**
     * Maneja el proceso de cierre de sesión.
     *
     * Guarda el pedido temporal, destruye la sesión y redirige al usuario.
     */
    public function logout(): void
    {
        if (isset($_SESSION['user_id']) && ! empty($_SESSION['user_id']) && $_SESSION['user_role'] === 'client') {
            $this->orderController->saveOrderTemp((int) $_SESSION['user_id']);
        }
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * Maneja el proceso de confirmación de cuenta.
     *
     * @param  string  $token  El token de confirmación.
     */
    public function confirmation($token)
    {
        // $token = Security::getToken();
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

    public function resetPass()
    {
        $this->page->render('auth/reset_pass');
    }
}
