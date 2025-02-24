<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\OrderService;

/**
 * Clase OrderController
 *
 * Esta clase maneja las operaciones relacionadas con los pedidos.
 */
class OrderController
{
    private OrderService $orderSerice;

    private Pages $page;

    public function __construct()
    {
        $this->orderSerice = new OrderService;
        $this->page = new Pages;
    }

    /**
     * Guarda un pedido temporal para un usuario.
     *
     * @param  mixed  $user_id  El ID del usuario.
     * @return mixed El resultado de guardar el pedido temporal.
     */
    public function saveOrderTemp($user_id)
    {
        return $this->orderSerice->saveOrderTemp($user_id);
    }

    /**
     * Obtiene el pedido temporal de un usuario y lo guarda en la sesión.
     *
     * @param  mixed  $user_id  El ID del usuario.
     */
    public function getOrderTemp($user_id)
    {
        $items = $this->orderSerice->getOrderTemp($user_id);

        if (! isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }

        $cart = $_SESSION['order'];

        if ($items) {
            foreach ($items as $item) {
                $productId = $item['producto_id'];

                if (isset($cart[$productId])) {
                    // Si el producto existe en ambos, sumar cantidades sin exceder el stock
                    $newQuantity = $cart[$productId]['cantidad'] + $item['unidades'];
                    $maxStock = $cart[$productId]['stock'];
                    $cart[$productId]['cantidad'] = min($newQuantity, $maxStock);
                } else {
                    // Si el producto solo está en el carrito guardado, añadirlo a la sesión
                    $cart[$productId] = [
                        'cantidad' => $item['unidades'],
                        'nombre' => $item['nombre'],
                        'precio' => $item['precio'],
                        'stock' => $item['stock'],
                        'imagen' => $item['imagen'],
                    ];
                }
            }

            $_SESSION['order'] = $cart;
            $this->orderSerice->saveOrderTemp($user_id);

            return $cart;
        }
    }

    /**
     * Obtiene y muestra todos los pedidos.
     */
    public function getOrders()
    {
        $result = $this->orderSerice->getOrders();

        if (! $result['success']) {
            $this->page->render('order/show');
        } else {
            $orders = $result['success'];
            $this->page->render('order/show', ['orders' => $orders]);
        }
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * @param  mixed  $id  El ID del pedido a actualizar.
     */
    public function updateStatus($id)
    {
        $result = $this->orderSerice->updateStatus((int) $id);
        if (! $result) {
            $_SESSION['message'] = ['text' => 'Error al actualizar el estado del pedido', 'type' => 'error-message'];
            header('Location: /admin/orders');
            exit;
        } else {
            $_SESSION['message'] = ['text' => 'Pedido entregado', 'type' => 'success-message'];
            header('Location: /admin/orders');
            exit;
        }
    }
}
