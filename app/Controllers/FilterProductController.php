<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\FilterProductService;

/**
 * Clase FilterProductController
 *
 * Esta clase maneja las operaciones de filtrado de productos.
 */
class FilterProductController
{
    private FilterProductService $filterProductService;

    private Pages $page;

    public function __construct()
    {
        $this->filterProductService = new FilterProductService;
        $this->page = new Pages;
    }

    /**
     * Obtiene y muestra los productos de una categoría específica.
     *
     * @param  mixed  $id  El ID de la categoría.
     */
    public function getCategoryByName($id)
    {
        $result = $this->filterProductService->getProductByCategory((int) $id);

        if (! $result['success']) {
            header('Location: /');
            exit;
        }
        $products = $result['success'];
        $this->page->render('product/getProductsByCategory', ['products' => $products]);

    }
}
