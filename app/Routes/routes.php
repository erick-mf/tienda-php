<?php

namespace App\Routes;

use App\Controllers\AuthController;
use App\Controllers\CartController;
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
Router::get('confirmation/:param', [AuthController::class, 'confirmation']);
Router::get('logout', [AuthController::class, 'logout']);
// Categorias
Router::get(ADMIN_URL.'/category', [CategoryController::class, 'show']);
Router::get(ADMIN_URL.'/category/new', [CategoryController::class, 'new']);
Router::post(ADMIN_URL.'/category/new', [CategoryController::class, 'new']);
Router::post(ADMIN_URL.'/category/delete/:id', [CategoryController::class, 'delete']);
Router::get(ADMIN_URL.'/category/edit/:id', [CategoryController::class, 'showEditCategory']);
Router::post(ADMIN_URL.'/category/edit/:id', [CategoryController::class, 'updateCategory']);
// Productos
Router::get('products', [ProductController::class, 'getProducts']);
Router::get(ADMIN_URL.'/product/new', [ProductController::class, 'new']);
Router::post(ADMIN_URL.'/product/new', [ProductController::class, 'new']);
Router::post(ADMIN_URL.'/product/delete/:id', [ProductController::class, 'delete']);
Router::get(ADMIN_URL.'/product/edit/:id', [ProductController::class, 'showEditProduct']);
Router::post(ADMIN_URL.'/product/edit/:id', [ProductController::class, 'updateProduct']);
// Carrito
Router::get('cart', [CartController::class, 'getCart']);
Router::post('/cart/add', [CartController::class, 'addProducts']);
Router::get('/cart/delete', [CartController::class, 'deleteProducts']);
Router::post('/cart/delete/:id', [CartController::class, 'deleteProductId']);
Router::post('/cart/update/:id', [CartController::class, 'updateProductId']);
