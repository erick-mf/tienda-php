<?php

namespace App\Services;

use App\Lib\Email;
use App\Lib\Security;
use App\Models\User;
use App\Repositories\UserRepository;

/**
 * Clase UserService
 *
 * Esta clase proporciona servicios relacionados con la gestión de usuarios.
 */
class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    /**
     * Realiza el proceso de login de un usuario.
     *
     * @param  string  $email  El email del usuario.
     * @param  string  $password  La contraseña del usuario.
     * @return array Un array con las claves 'success' y 'user' o 'error'.
     */
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

    /**
     * Registra un nuevo usuario.
     *
     * @param  array  $userData  Los datos del nuevo usuario.
     * @return array Un array con las claves 'success', 'errors' (si los hay) y 'user' (si se registró con éxito).
     */
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

    /**
     * Busca un usuario por su email.
     *
     * @param  string  $email  El email del usuario a buscar.
     * @return array Un array con la clave 'success' conteniendo el resultado de la búsqueda.
     */
    public function findEmail($email)
    {
        $user = $this->userRepository->findByEmail($email);
        if (! $user) {
            return ['success' => $user];
        }

        return ['success' => $user];
    }

    /**
     * Edita la información de un usuario existente.
     *
     * @param  mixed  $id  El ID del usuario a editar.
     * @param  array  $userData  Los datos actualizados del usuario.
     * @return array Un array con la clave 'success' indicando si la edición fue exitosa y 'errors' si hubo errores.
     */
    public function edit($id, $userData)
    {
        $user = new User;
        $user->setName($userData['name']);
        $user->setSurnames($userData['surnames']);
        $user->setAddress($userData['address']);
        $user->setEmail($userData['email']);
        $user->setPhone($userData['phone']);
        $user->setRole($userData['role']);

        $errors = $user->validate(true);
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        $result = $this->userRepository->edit($user);

        return ['success' => $result];
    }

    /**
     * Confirma la cuenta de un usuario.
     *
     * @param  string  $email  El email del usuario a confirmar.
     * @param  bool  $is_confirmed  El estado de confirmación.
     * @return array Un array con la clave 'success' indicando si la confirmación fue exitosa.
     */
    public function confirmation($email, $is_confirmed)
    {
        $user_confirmed = $this->userRepository->confirmation($email, $is_confirmed);
        if (! $user_confirmed) {
            return ['success' => $user_confirmed];
        }
        $user = $this->userRepository->findByEmail($email);
        if (! $user) {
            return ['success' => false];
        }
        $dataToken = [
            'name' => $user['nombre'],
            'email' => $user['email'],
        ];
        $newToken = Security::createTokenWhithoutExpiration($dataToken);
        $updateToken = $this->userRepository->updateToken($email, $newToken, null);

        if (! $updateToken) {
            return ['success' => false];
        }

        return ['success' => $user_confirmed];
    }

    /**
     * Busca un usuario por su ID.
     *
     * @param  mixed  $id  El ID del usuario a buscar.
     * @return array Un array con la clave 'success' conteniendo el resultado de la búsqueda.
     */
    public function findUserById($id)
    {
        $result = $this->userRepository->findUserById($id);

        return ['success' => $result];
    }
}
