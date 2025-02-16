<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

/**
 * Clase AdminService
 *
 * Esta clase proporciona servicios relacionados con la administración de usuarios.
 */
class AdminService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
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

    /**
     * Obtiene todos los usuarios.
     *
     * @return array Un array con la clave 'success' conteniendo el resultado de la obtención de usuarios.
     */
    public function getUsers()
    {
        $result = $this->userRepository->getUsers();

        return ['success' => $result];
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param  mixed  $id  El ID del usuario a eliminar.
     * @return bool True si el usuario fue eliminado con éxito, false en caso contrario.
     */
    public function deleteUser($id)
    {
        $result = $this->userRepository->deleteUser($id);
        if (! $result) {
            $_SESSION['message'] = 'El usuario no ha sido eliminado';

            return false;
        }

        return true;
    }

    /**
     * Edita la información de un usuario.
     *
     * @param  array  $userData  Los datos actualizados del usuario.
     * @return array Un array con las claves 'success' y posiblemente 'errors'.
     */
    public function editUser($userData)
    {
        $user = new User;
        $user->setId_user($userData['id']);
        $user->setName($userData['name']);
        $user->setSurnames($userData['surnames']);
        $user->setAddress($userData['address']);
        $user->setPhone($userData['phone']);
        $user->setRole($userData['role']);
        $user->setIs_confirmed(false);

        $errors = $user->validate(true);
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        $result = $this->userRepository->edit($user);
        if (! $result) {
            $_SESSION['message'] = 'Error al editar al usuario';

            return ['success' => false];
        }

        return ['success' => $user];
    }
}
