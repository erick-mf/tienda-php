<?php

namespace App\Services;

use App\Repositories\UserRepository;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    public function getUsers()
    {
        $result = $this->userRepository->getUsers();

        return ['success' => $users];
    }
}
