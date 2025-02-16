<?php

namespace App\Services;

use App\Repositories\ProductRepository;

/**
 * Clase FilterProductService
 *
 * Esta clase proporciona servicios para filtrar productos.
 */
class FilterProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository;
    }

    /**
     * Obtiene los productos por categoría.
     *
     * @param  mixed  $id  El ID de la categoría.
     * @return array Un array con la clave 'success' conteniendo los productos encontrados.
     */
    public function getProductByCategory($id)
    {
        $products = $this->productRepository->findProductByCategory($id);

        return ['success' => $products];
    }
}
