<?php

namespace App\Middlewares;

class AdminMiddleware
{
    public static function isValid(): bool
    {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            return true;
        }

        return false;
    }
}
