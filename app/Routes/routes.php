<?php

namespace App\Routes;

use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Lib\Router;
use App\Middlewares\AdminMiddleware;

// Middlewares
Router::addMiddleware('admin', AdminMiddleware::class);
// Usuarios
Router::get('', [HomeController::class, 'index']);
Router::get('login', [AuthController::class, 'login']);
Router::post('login', [AuthController::class, 'login']);
Router::get('register', [AuthController::class, 'register']);
Router::post('register', [AuthController::class, 'register']);
Router::get('logout', [AuthController::class, 'logout']);
// Categorias
Router::get('/admin/category', [CategoryController::class, 'show']);
Router::get('/admin/category/new', [CategoryController::class, 'new']);
Router::post('/admin/category/new', [CategoryController::class, 'new']);
Router::post('/admin/category/delete/:id', [CategoryController::class, 'delete']);
Router::get('/admin/category/edit/:id', [CategoryController::class, 'showEditCategory']);
Router::post('/admin/category/edit/:id', [CategoryController::class, 'updateCategory']);
Router::dispatch();
