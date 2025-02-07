<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\AdminService;

class AdminController
{
    private AdminService $adminService;

    private Pages $page;

    public function __construct()
    {
        $this->adminService = new AdminService;
        $this->page = new Pages;
    }

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

    public function deleteUser($idStrg)
    {
        $result = $this->adminService->deleteUser((int) $idStrg);
        header('Location: /admin/users');
        exit;
    }

    public function showEditUser($id)
    {
        $user = $this->adminService->findUserById((int) $id);
        if (! $user['success']) {
            header('Location: /admin/users');
            exit;
        }
        $this->page->render('admin/editUser', ['user' => $user['success']]);
    }

    public function updateUser($id)
    {
        $user = $this->adminService->findUserById($id);
        $updateUser = [
            'id' => $id ?? $user['id'],
            'name' => $_POST['user']['name'] ?? $user['nombre'],
            'surnames' => $_POST['user']['surnames'] ?? $user['apellidos'],
            'address' => $_POST['user']['address'] ?? $user['direccion'],
            'email' => $_POST['user']['email'] ?? $user['email'],
            'phone' => $_POST['user']['phone'] ?? $user['telefono'],
            'role' => $_POST['user']['role'] ?? $user['rol'],
            'confirmation' => false,
        ];
        if (empty($updateUser)) {
            $this->page->render('admin/editUser', ['user' => ['id' => $id]]);

            return;
        }
        $result = $this->adminService->editUser($updateUser);
        if (! $result['success']) {
            $this->page->render('admin/editUser', ['errors' => $result['success'], 'user' => $user]);
        } else {
            header('Location: /admin/users');
            exit;
        }
    }
}
