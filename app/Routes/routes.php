<?php

namespace App\Routes;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Lib\Router;
use App\Middlewares\AdminMiddleware;

// Middlewares
Router::addMiddleware('admin', AdminMiddleware::class);
// Usuarios
Router::get('', [HomeController::class, 'index']);
Router::get('login', [AuthController::class, 'login']);
Router::post('login', [AuthController::class, 'login']);
Router::get('admin/register', [AuthController::class, 'register']);
Router::post('register', [AuthController::class, 'register']);
Router::get('logout', [AuthController::class, 'logout']);
Router::dispatch();
