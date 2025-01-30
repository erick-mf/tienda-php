<?php

namespace App\Services;

use App\Lib\Email;
use App\Lib\Security;
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
        if ($user['confirmacion'] === 0) {
            return ['success' => false, 'error' => 'La cuenta no ha sido confirmada todavia revisa tu correo'];
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

        $dataToken = [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ];
        $tokenResult = Security::createToken($dataToken);
        $user->setToken($tokenResult['token']);
        $user->setToken_exp(date('Y-m-d H:i:s', $tokenResult['exp']));

        $errors = $user->validate();
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($this->userRepository->findByEmail($userData['email'])) {
            return ['success' => false, 'errors' => ['email' => 'El email ya está registrado']];
        }

        $user->setPassword(password_hash($userData['password'], PASSWORD_DEFAULT));

        try {
            $savedUser = $this->userRepository->save($user);
            if ($savedUser) {
                $sendEmail = new Email($user->email(), $user->name(), $user->token());
                $sendEmail->sendConfirmation();

                return ['success' => true, 'user' => $savedUser];
            } else {
                throw new \Exception('No se pudo completar el registro del usuario');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function findEmail($email)
    {
        $user = $this->userRepository->findByEmail($email);
        if (! $user) {
            return ['success' => $user];
        }

        return ['success' => $user];
    }

    public function confirmation($email, $is_confirmed)
    {
        $user_confirmed = $this->userRepository->confirmation($email, $is_confirmed);
        if (! $user_confirmed) {
            return ['success' => $user_confirmed];
        }

        return ['success' => $user_confirmed];
    }
}
