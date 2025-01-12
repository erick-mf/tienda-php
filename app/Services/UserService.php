<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Credenciales inválidas'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function register(array $userData): array
    {
        $user = new User;
        $user->setName($userData['name']);
        $user->setSurnames($userData['surnames']);
        $user->setAddress($userData['address']);
        $user->setEmail($userData['email']);
        $user->setPhone($userData['phone']);
        $user->setPassword($userData['password']);
        $user->setRole($userData['role']);

        $errors = $user->validate();
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($this->userRepository->findByEmail($userData['email'])) {
            return ['success' => false, 'errors' => ['email' => 'El email ya está registrado']];
        }

        $user->setPassword(password_hash($userData['password'], PASSWORD_DEFAULT));

        // $savedUser = $this->userRepository->save($user);
        // if ($savedUser) {
        //     return ['success' => true, 'user' => $savedUser];
        // } else {
        //     throw new \Exception("Error en insertar los datos");            ;
        // }
        try {
            $savedUser = $this->userRepository->save($user);
            if ($savedUser) {
                return ['success' => true, 'user' => $savedUser];
            } else {
                throw new \Exception('Error personalizado: No se pudo completar el registro del usuario');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
    }
}
