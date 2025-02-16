<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

/**
 * Clase ProductService
 *
 * Esta clase proporciona servicios relacionados con la gestión de productos.
 */
class ProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository;
    }

    /**
     * Guarda un nuevo producto.
     *
     * @param  array  $productData  Los datos del nuevo producto.
     * @return array Un array con las claves 'success', 'errors' (si los hay) y 'product' (si se guardó con éxito).
     */
    public function save(array $productData): array
    {
        $product = new Product;
        $product->setCategory_id($productData['category_id']);
        $product->setName($productData['name']);
        $product->setDescription($productData['description']);
        $product->setPrice($productData['price']);
        $product->setStock($productData['stock']);
        $product->setOffer($productData['offer']);
        $product->setDate($productData['date']);
        $product->setImage($productData['image']);

        $errors = $product->validate();
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($this->productRepository->findProduct($productData['name'])) {
            return ['success' => false, 'errors' => ['name' => 'El producto ya está registrado']];
        }

        try {
            $savedProduct = $this->productRepository->save($product);
            if ($savedProduct) {
                return ['success' => true, 'product' => $product];
            } else {
                throw new \Exception('No se pudo completar el registro del producto');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Obtiene todos los productos.
     *
     * @return array|null Un array con todos los productos o null si no hay productos.
     */
    public function getProducts(): ?array
    {
        return $this->productRepository->getProducts();

    }

    /**
     * Busca un producto por su ID.
     *
     * @param  int  $id  El ID del producto a buscar.
     * @return array|null Los datos del producto o null si no se encuentra.
     */
    public function findProductId(int $id)
    {
        return $this->productRepository->findProductID($id);
    }

    /**
     * Elimina un producto por su ID.
     *
     * @param  int  $id  El ID del producto a eliminar.
     * @return bool True si el producto fue eliminado con éxito, false en caso contrario.
     */
    public function delete(int $id)
    {
        return $this->productRepository->delete($id);
    }

    /**
     * Edita un producto existente.
     *
     * @param  int  $id  El ID del producto a editar.
     * @param  array  $productData  Los datos actualizados del producto.
     * @return array Un array con la clave 'success' indicando si la edición fue exitosa y 'errors' si hubo errores.
     */
    public function edit(int $id, array $productData)
    {
        $product = new Product;
        $product->setId($id);
        // $product->setCategory_id($productData['category_id']);
        // $product->setName($productData['name']);
        // $product->setDescription($productData['description']);
        // $product->setPrice($productData['price']);
        // $product->setStock($productData['stock']);
        // $product->setOffer($productData['offer']);
        // $product->setDate($productData['date']);
        // $product->setImage($productData['image']);
        $product->fromArray($productData);

        $errors = $product->validate(true);
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $result = $this->productRepository->edit($product);

        return ['success' => $result];
    }
}
