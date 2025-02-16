<?php

define('IMG_URL', '/public/assets/img/');
define('ADMIN_URL', 'admin');

if (! isset($_SESSION['categories'])) {
    $categoryService = new App\Services\CategoryService;
    $_SESSION['categories'] = $categoryService->show();
}
$categories = $_SESSION['categories'];
