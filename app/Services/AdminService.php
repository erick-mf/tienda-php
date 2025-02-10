<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AdminService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    public function findUserById($id)
    {
        $result = $this->userRepository->findUserById($id);

        return ['success' => $result];
    }

    public function getUsers()
    {
        $result = $this->userRepository->getUsers();

        return ['success' => $result];
    }

    public function deleteUser($id)
    {
        $result = $this->userRepository->deleteUser($id);
        if (! $result) {
            $_SESSION['message'] = 'El usuario no ha sido eliminado';

            return false;
        }

        return true;
    }

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
