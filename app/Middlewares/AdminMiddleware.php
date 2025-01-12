<?php

namespace App\Middlewares;

class AdminMiddleware
{
    public static function isValid(): bool
    {
        if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador') {
            return true;
        }

        return false;
    }
}
