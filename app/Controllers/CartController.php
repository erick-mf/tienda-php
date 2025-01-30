<?php

namespace App\Controllers;

use App\Lib\Pages;

class CartController
{
    private Pages $page;

    public function __construct()
    {
        $this->page = new Pages;
    }

    public function addProducts()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $cant = 1;

            // Inicializa el array de orden si no existe
            if (! isset($_SESSION['order'])) {
                $_SESSION['order'] = [];
            }

            // Añade o actualiza el producto en el array
            if (isset($_SESSION['order'][$productId])) {
                // Si el producto ya está en el carrito, incrementa la cantidad
                $_SESSION['order'][$productId]['cantidad'] += $cant;
            } else {
                // Si es un nuevo producto se añade al carrito con detalles
                $_SESSION['order'][$productId] = [
                    'cantidad' => $cant,
                    'nombre' => $_POST['product_name'],
                    'precio' => $_POST['product_price'],
                    'stock' => $_POST['product_stock'],
                    'imagen' => $_POST['product_image'],
                ];
            }

            header('Location: /');
            exit;
        }
    }

    public function getCart()
    {
        $this->page->render('cart/show_cart', []);
    }

    public function deleteProducts()
    {
        unset($_SESSION['order']);
        header('Location: /');
        exit;
    }

    public function deleteProductId($id)
    {
        if (isset($_SESSION['order'][$id])) {
            unset($_SESSION['order'][$id]);

            header('Location: /cart');

            return true;
        }

        return false;
    }

    public function updateProductId($id, $newQuantity)
    {
        if (isset($_SESSION['order'][$id])) {
            // Verifica que la nueva cantidad sea válida
            if ($newQuantity > 0 && $newQuantity <= $_SESSION['order'][$id]['stock']) {
                $_SESSION['order'][$id]['cantidad'] = $newQuantity;
                header('Location: /cart');

                return true; // Indica que el producto fue actualizado exitosamente
            } else {
                header('Location: /cart');

                return false;
            }
        }

        // El producto no fue encontrado en la sesión
        header('Location: /cart');

        return false;
    }
}
