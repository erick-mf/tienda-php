<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\OrderService;
use App\Services\UserService;

/**
 * Clase CartController
 *
 * Esta clase maneja las operaciones relacionadas con el carrito de compras.
 */
class CartController
{
    private Pages $page;

    private UserService $userService;

    private OrderService $OrderService;

    private PayPalController $paypalController;

    public function __construct()
    {
        $this->page = new Pages;
        $this->userService = new UserService;
        $this->OrderService = new OrderService;
        $this->paypalController = new PayPalController;
    }

    /**
     * Muestra el contenido del carrito.
     */
    public function getCart()
    {
        $this->page->render('cart/show_cart', []);
    }

    /**
     * Añade productos al carrito.
     */
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
                if ($_SESSION['order'][$productId]['cantidad'] < $_SESSION['order'][$productId]['stock']) {
                    // Si el producto ya está en el carrito, incrementa la cantidad
                    $_SESSION['order'][$productId]['cantidad'] += $cant;
                    $_SESSION['message'] = ['text' => 'La cantidad del producto a sido aumentada', 'type' => 'success-message'];
                } else {
                    $_SESSION['message'] = ['text' => 'No se puede seguir aumentando el producto. No queda stock', 'type' => 'error-message'];
                }
            } else {
                // Si es un nuevo producto se añade al carrito con detalles
                $_SESSION['order'][$productId] = [
                    'cantidad' => $cant,
                    'nombre' => $_POST['product_name'],
                    'precio' => $_POST['product_price'],
                    'stock' => $_POST['product_stock'],
                    'imagen' => $_POST['product_image'],
                ];
                $_SESSION['message'] = ['text' => 'Producto agregado al carrito', 'type' => 'success-message'];
            }

            header('Location: /');
            exit;
        }
    }

    /**
     * Elimina todos los productos del carrito.
     */
    public function deleteProducts()
    {
        unset($_SESSION['order']);
        $this->OrderService->deleteOrder($_SESSION['user_id']);
        header('Location: /cart');
        exit;
    }

    /**
     * Elimina un producto específico del carrito.
     *
     * @param  mixed  $id  El ID del producto a eliminar.
     * @return bool True si el producto fue eliminado, false en caso contrario.
     */
    public function deleteProductId($id)
    {
        if (isset($_SESSION['order'][$id])) {
            unset($_SESSION['order'][$id]);
            header('Location: /cart');

            return true;
        }

        return false;
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function updateProductQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            if (isset($_SESSION['order'][$productId])) {
                $curretQuantity = $_SESSION['order'][$productId]['cantidad'];
                $stock = $_SESSION['order'][$productId]['stock'];

                if ($quantity === 'decrease' && $curretQuantity > 1) {
                    $_SESSION['order'][$productId]['cantidad']--;
                    $_SESSION['message'] = ['text' => 'La cantidad del producto a disminuido', 'type' => 'success-message'];
                } elseif ($quantity === 'increase' && $curretQuantity < $stock) {
                    $_SESSION['order'][$productId]['cantidad']++;
                    $_SESSION['message'] = ['text' => 'La cantidad del producto a aumentado', 'type' => 'success-message'];
                } else {
                    $_SESSION['message'] = ['text' => 'La cantidad del producto no se a podido actualizar', 'type' => 'error-message'];
                }
            }
        }
        header('Location: /cart');
        exit;
    }

    /**
     * Procesa el checkout del carrito.
     */
    public function checkout()
    {
        if (! isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'client') {
            $_SESSION['message'] = ['text' => 'Debes iniciar sessión para continuar con la compra', 'type' => 'error-message'];
            header('Location: /cart');
            exit;
        }
        $result = $this->userService->findUserById((int) $_SESSION['user_id']);
        if (! $result['success']) {
            $_SESSION['message'] = ['text' => 'Error en el proceso de compra', 'type' => 'error-message'];
        } else {
            $this->page->render('cart/checkout');
        }
    }

    /**
     * Procesa el pago del pedido.
     */
    public function payment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'address' => $_SESSION['user_address'],
                'state' => $_POST['state'],
                'city' => $_POST['city'],
                'cost' => $this->calculateTotal($_SESSION['order']),
                'status' => 'confirmado',
                'date' => date('Y-m-d'), // Formato: año-mes-dia
                'time' => date('H:i:s'), // Formato: hora-mint-seg
            ];
            $result = $this->OrderService->newOrder($orderData);
            if (! $result['success']) {
                $errors = $result['errors'];
                $this->page->render('cart/checkout', ['errors' => $errors]);
            } else {
                // NOTE: volver a generar el token despues de 9hrs
                $paypalResult = $this->paypalController->createOrder();
                if ($paypalResult['success']) {
                    header('Location: /paypal/capture-order?orderId='.$paypalResult['orderId']);
                    exit;
                } else {
                    $_SESSION['message'] = ['text' => 'Error al crear la orden en PayPal', 'type' => 'error-message'];
                    header('Location: /cart');
                    exit;
                }
            }
        }
    }

    /**
     * Calcula el total del pedido.
     *
     * @param  array  $orderItems  Los items del pedido.
     * @return float El total del pedido.
     */
    private function calculateTotal($orderItems)
    {
        $total = 0;
        foreach ($orderItems as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return $total;
    }
}
