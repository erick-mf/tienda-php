<?php

namespace App\Routes;

use App\Controllers\AdminController;
use App\Controllers\APIproductController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Controllers\FilterProductController;
use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Controllers\PayPalController;
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
Router::get('reset-pass', [AuthController::class, 'resetPass']);
Router::get('logout', [AuthController::class, 'logout']);
Router::get('/'.ADMIN_URL.'/users', [AdminController::class, 'getUsers']);
Router::get('/'.ADMIN_URL.'/user/edit/:param', [AdminController::class, 'showEditUser']);
Router::post('/'.ADMIN_URL.'/user/edit/:param', [AdminController::class, 'updateUser']);
Router::post('/'.ADMIN_URL.'/user/delete/:param', [AdminController::class, 'deleteUser']);
// Categorias
Router::get('/category/:param', [FilterProductController::class, 'getCategoryByName']);
Router::get('/'.ADMIN_URL.'/category', [CategoryController::class, 'show']);
Router::get('/'.ADMIN_URL.'/category/new', [CategoryController::class, 'new']);
Router::post('/'.ADMIN_URL.'/category/new', [CategoryController::class, 'new']);
Router::post('/'.ADMIN_URL.'/category/delete/:param', [CategoryController::class, 'delete']);
Router::get('/'.ADMIN_URL.'/category/edit/:param', [CategoryController::class, 'showEditCategory']);
Router::post('/'.ADMIN_URL.'/category/edit/:param', [CategoryController::class, 'updateCategory']);
// Productos
Router::get('products', [ProductController::class, 'getProducts']);
Router::get('/'.ADMIN_URL.'/product/new', [ProductController::class, 'new']);
Router::post('/'.ADMIN_URL.'/product/new', [ProductController::class, 'new']);
Router::post('/'.ADMIN_URL.'/product/delete/:param', [ProductController::class, 'delete']);
Router::get('/'.ADMIN_URL.'/product/edit/:param', [ProductController::class, 'showEditProduct']);
Router::post('/'.ADMIN_URL.'/product/edit/:param', [ProductController::class, 'updateProduct']);
// Carrito
Router::get('cart', [CartController::class, 'getCart']);
Router::post('/cart/add', [CartController::class, 'addProducts']);
Router::get('/cart/delete', [CartController::class, 'deleteProducts']);
Router::post('/cart/delete/:param', [CartController::class, 'deleteProductId']);
Router::post('/cart/update', [CartController::class, 'updateProductQuantity']);
Router::get('/checkout', [CartController::class, 'checkout']);
Router::post('/checkout', [CartController::class, 'payment']);
// Paypal
Router::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
Router::get('/paypal/capture-order', [PayPalController::class, 'captureOrder']);
// Pedidos
Router::get('/'.ADMIN_URL.'/orders', [OrderController::class, 'getOrders']);
Router::post('/'.ADMIN_URL.'/order/confirmation/:param', [OrderController::class, 'updateStatus']);

// ////////////////////////////////////////////////////////////////////
// API
Router::get('/api/v1/products/all', [APIproductController::class, 'getAll']);
Router::get('/api/v1/products/:param', [APIproductController::class, 'getProductById']);
Router::post('/api/v1/products/new', [APIproductController::class, 'newProduct']);
Router::put('/api/v1/products/edit/:param', [APIproductController::class, 'productEdit']);
Router::delete('/api/v1/products/delete/:param', [APIproductController::class, 'deleteProduct']);
