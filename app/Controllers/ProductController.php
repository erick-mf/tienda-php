<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\CategoryService;
use App\Services\ProductService;
use DateTime;

class ProductController
{
    private ProductService $productService;

    private CategoryService $categoryService;

    private Pages $page;

    public function __construct()
    {
        $this->productService = new ProductService;
        $this->categoryService = new CategoryService;
        $this->page = new Pages;
    }

    public function new(): void
    {
        $categories = $this->categoryService->show();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'category_id' => $_POST['product']['category_id'] ?? '',
                'name' => $_POST['product']['name'] ?? '',
                'description' => $_POST['product']['description'] ?? '',
                'price' => $_POST['product']['price'] ?? 0.0,
                'stock' => $_POST['product']['stock'] ?? 0,
                'offer' => $_POST['product']['offer'] ?? '',
                'date' => $_POST['product']['date'] ?? date('Y-m-d'),
                'image' => $_POST['product']['image'] ?? null,
            ];

            $date = new DateTime($productData['date']);
            $productData['date'] = $date->format('Y-m-d');

            if (isset($_FILES['product']['name']['image']) && $_FILES['product']['error']['image'] == 0) {
                $fileName = uniqid().'_'.basename($_FILES['product']['name']['image']);
                $uploadFile = $_SERVER['DOCUMENT_ROOT'].IMG_URL.$fileName; // Ruta completa para mover el archivo

                if (move_uploaded_file($_FILES['product']['tmp_name']['image'], $uploadFile)) {
                    $productData['image'] = $fileName;
                } else {
                    $this->page->render('product/new', ['error' => ['image' => 'Error al subir la imagén']]);
                }
            }

            try {
                $result = $this->productService->save($productData);
                if ($result['success']) {
                    header('Location: /products');
                    exit;
                } else {
                    $this->page->render('product/new', ['errors' => $result['errors'], 'categories' => $categories, 'product' => $productData]);
                }
            } catch (\Exception $e) {
                $this->page->render('product/new', ['error' => $e->getMessage()]);
            }
        } else {
            $this->page->render('product/new', ['categories' => $categories]);
        }
    }

    public function getProducts()
    {
        $products = $this->productService->getProducts();

        if (empty($products)) {
            $this->page->render('product/show', []);
        } else {
            $this->page->render('product/show', ['products' => $products]);
        }
    }

    public function delete($idStrg)
    {
        $id = (int) $idStrg;
        if ($this->productService->delete($id)) {
            header('Location: /products');
            exit;
        }
    }

    public function showEditProduct($id)
    {
        $product = $this->productService->findProductId((int) $id);
        $categories = $this->categoryService->show();

        if (! $product) {
            header('Location: /products');
            exit;
        }
        $this->page->render('product/edit', ['product' => $product, 'categories' => $categories]);
    }

    public function updateProduct($id)
    {
        $product = $this->productService->findProductId((int) $id);
        $categories = $this->categoryService->show();

        $productData = [
            'id' => $_POST['product']['id'] ?? $product['id'],
            'category_id' => $_POST['product']['category_id'] ?? $product['categoria_id'],
            'name' => $_POST['product']['name'] ?? $product['nombre'],
            'description' => $_POST['product']['description'] ?? $product['descripcion'],
            'price' => $_POST['product']['price'] ?? $product['precio'],
            'stock' => $_POST['product']['stock'] ?? $product['stock'],
            'offer' => $_POST['product']['offer'] ?? $product['oferta'],
            'date' => $product['fecha'],
            'image' => $_POST['product']['image'] ?? $product['imagen'],
        ];

        if (isset($_FILES['product']['name']['image']) && $_FILES['product']['error']['image'] == 0) {
            $fileName = uniqid().'_'.basename($_FILES['product']['name']['image']);
            $uploadFile = $_SERVER['DOCUMENT_ROOT'].IMG_URL.$fileName;

            if (move_uploaded_file($_FILES['product']['tmp_name']['image'], $uploadFile)) {
                $productData['image'] = $fileName;
            } else {
                $this->page->render('product/edit', [
                    'error' => ['image' => 'Error al subir la imagen'],
                    'product' => $productData,
                    'categories' => $categories,
                ]);

                return;
            }
        }

        $result = $this->productService->edit((int) $id, $productData);
        if (! $result['success']) {
            // Si hay errores, vuelve a renderizar la página de edición con los errores
            $this->page->render('product/edit', [
                'errors' => $result['errors'],
                'product' => $product,
                'categories' => $categories,
            ]);
        } else {
            header('Location: /products');
            exit;
        }
    }
}
