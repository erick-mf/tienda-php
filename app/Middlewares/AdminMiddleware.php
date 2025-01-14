<?php

namespace App\Middlewares;

class AdminMiddleware
{
    public static function isValid(): bool
    {
        if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin') {
            return true;
        }

        return false;
    }
}
