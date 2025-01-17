<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository;
    }

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

    public function getProducts(): ?array
    {
        return $this->productRepository->getProducts();
    }

    public function findProductId(int $id)
    {
        return $this->productRepository->findProductID($id);
    }

    public function delete(int $id)
    {
        return $this->productRepository->delete($id);
    }

    public function edit(int $id, array $productData)
    {
        $product = new Product;
        $product->setId($id);
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

        $result = $this->productRepository->edit($product);

        return ['success' => $result];
    }
}
