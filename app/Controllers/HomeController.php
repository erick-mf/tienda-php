<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\ProductService;

/**
 * Clase HomeController
 *
 * Esta clase maneja las operaciones relacionadas con la página de inicio.
 */
class HomeController
{
    private Pages $pages;

    private ProductService $productService;

    public function __construct()
    {
        $this->pages = new Pages;
        $this->productService = new ProductService;

    }

    /**
     * Muestra la página de inicio.
     *
     * Obtiene los productos y renderiza la página de inicio.
     */
    public function index()
    {
        // $prueba = ['uno' => 1, 'dos' => 2, 'tres' => 3];
        // $_SESSION['array'] = $prueba;
        // $categories = $this->categoryService->show();
        $products = $this->productService->getProducts();
        $this->pages->render('home/index', ['products' => $products]);
    }
}
