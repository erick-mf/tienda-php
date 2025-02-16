<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\AdminService;

/**
 * Clase AdminController
 *
 * Esta clase maneja las operaciones de administración relacionadas con los usuarios.
 */
class AdminController
{
    private AdminService $adminService;

    private Pages $page;

    public function __construct()
    {
        $this->adminService = new AdminService;
        $this->page = new Pages;
    }

    /**
     * Obtiene y muestra todos los usuarios.
     *
     * Maneja solicitudes GET para obtener y mostrar la lista de usuarios.
     */
    public function getUsers()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $result = $this->adminService->getUsers();
            if (! $result['success']) {
                $this->page->render('admin/getUsers', []);
            } else {
                $this->page->render('admin/getUsers', ['users' => $result['success']]);
            }
        }
    }

    /**
     * Elimina un usuario.
     *
     * @param  string  $idStrg  El ID del usuario a eliminar.
     */
    public function deleteUser($idStrg)
    {
        $result = $this->adminService->deleteUser((int) $idStrg);
        header('Location: /admin/users');
        exit;
    }

    /**
     * Muestra el formulario de edición de un usuario.
     *
     * @param  mixed  $id  El ID del usuario a editar.
     */
    public function showEditUser($id)
    {
        if (empty($id) || ! is_numeric($id)) {
            header('Location: /admin/users');
            exit;
        }

        $userResult = $this->adminService->findUserById((int) $id);
        if (! $userResult['success'] || empty($userResult['success'])) {
            header('Location: /admin/users');
            exit;
        }
        $user = $userResult['success'];
        $this->page->render('admin/editUser', ['user' => $user]);
    }

    /**
     * Actualiza la información de un usuario.
     *
     * @param  mixed  $id  El ID del usuario a actualizar.
     */
    public function updateUser($id)
    {
        if (empty($id) || ! is_numeric($id)) {
            header('Location: /admin/users');
            exit;
        }
        $userResult = $this->adminService->findUserById((int) $id);

        if (! $userResult['success'] || empty($userResult['success'])) {
            header('Location: /admin/users');
            exit;
        }

        $user = $userResult['success'];
        $updateUser = [
            'id' => $id,
            'name' => $_POST['user']['name'] ?? $user['nombre'],
            'surnames' => $_POST['user']['surnames'] ?? $user['apellidos'],
            'address' => $_POST['user']['address'] ?? $user['direccion'],
            'phone' => $_POST['user']['phone'] ?? $user['telefono'],
            'role' => $_POST['user']['role'] ?? $user['rol'],
            'confirmation' => false,
        ];

        $result = $this->adminService->editUser($updateUser);
        if (! $result['success']) {
            $this->page->render('admin/editUser', ['errors' => $result['success'], 'user' => $user]);
        } else {
            header('Location: /admin/users');
            exit;
        }
    }
}
