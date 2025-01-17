<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\CategoryService;
use App\Services\ProductService;

class HomeController
{
    private Pages $pages;

    private CategoryService $categoryService;

    private ProductService $productService;

    public function __construct()
    {
        $this->pages = new Pages;
        $this->categoryService = new CategoryService;
        $this->productService = new ProductService;

    }

    public function index()
    {
        $prueba = ['uno' => 1, 'dos' => 2, 'tres' => 3];
        $_SESSION['array'] = $prueba;
        $categories = $this->categoryService->show();
        $products = $this->productService->getProducts();
        $this->pages->render('home/index', ['products' => $products, 'categories' => $categories]);
    }
}
