<?php

namespace App\Routes;

use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Lib\Router;
use App\Middlewares\AdminMiddleware;

// Middlewares
Router::addMiddleware(ADMIN_URL, AdminMiddleware::class);
// Usuarios
Router::get('', [HomeController::class, 'index']);
Router::get('login', [AuthController::class, 'login']);
Router::post('login', [AuthController::class, 'login']);
Router::get('register', [AuthController::class, 'register']);
Router::post('register', [AuthController::class, 'register']);
Router::get('logout', [AuthController::class, 'logout']);
Router::get('/products', [ProductController::class, 'getProducts']);
// Categorias
Router::get(ADMIN_URL.'/category', [CategoryController::class, 'show']);
Router::get(ADMIN_URL.'/category/new', [CategoryController::class, 'new']);
Router::post(ADMIN_URL.'/category/new', [CategoryController::class, 'new']);
Router::post(ADMIN_URL.'/category/delete/:id', [CategoryController::class, 'delete']);
Router::get(ADMIN_URL.'/category/edit/:id', [CategoryController::class, 'showEditCategory']);
Router::post(ADMIN_URL.'/category/edit/:id', [CategoryController::class, 'updateCategory']);
// Productos
Router::get(ADMIN_URL.'/product/new', [ProductController::class, 'new']);
Router::post(ADMIN_URL.'/product/new', [ProductController::class, 'new']);
Router::post(ADMIN_URL.'/product/delete/:id', [ProductController::class, 'delete']);
Router::get(ADMIN_URL.'/product/edit/:id', [ProductController::class, 'showEditProduct']);
Router::post(ADMIN_URL.'/product/edit/:id', [ProductController::class, 'updateProduct']);
// Carrito
Router::dispatch();
