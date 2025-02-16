<?php

namespace App\Services;

use App\Lib\Email;
use App\Models\Order;
use App\Models\OrderLine;
use App\Repositories\OrderLineRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

/**
 * Clase OrderService
 *
 * Esta clase proporciona servicios relacionados con la gestión de pedidos.
 */
class OrderService
{
    private OrderRepository $orderRepository;

    private OrderLineRepository $orderLineRepository;

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository;
        $this->orderLineRepository = new OrderLineRepository;
        $this->userRepository = new UserRepository;
    }

    /**
     * Crea un nuevo pedido.
     *
     * @param  array  $orderData  Los datos del nuevo pedido.
     * @return array Un array con las claves 'success' y posiblemente 'errors'.
     */
    public function newOrder($orderData)
    {
        $order = new Order;
        $order->fromArray($orderData);
        $errors = $order->validate();

        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $savedOrder = $this->orderRepository->newOrder($order);
        if ($savedOrder) {
            // Guardar las líneas de pedido
            foreach ($_SESSION['order'] as $productId => $item) {
                $orderLine = new OrderLine;
                $orderLineData = [
                    'order_id' => (int) $savedOrder,
                    'product_id' => (int) $productId,
                    'quantity' => $item['cantidad'],
                ];
                $orderLine->fromArray($orderLineData);
                $this->orderLineRepository->newOrderLine($orderLine);
            }

            $user = $this->userRepository->findUserById($order->user_id());

            $emailData = [
                'fecha' => $order->date(),
                'hora' => $order->time(),
                'estado' => $order->status(),
                'coste' => $order->cost(),
                'direccion' => $order->address(),
                'localidad' => $order->city(),
                'provincia' => $order->state(),
                'productos' => $_SESSION['order'] ?? [],
            ];

            $email = new Email($user['email'], $user['nombre'], '');
            $email->sendOrder($emailData);
        }

        return ['success' => $savedOrder];
    }

    /**
     * Guarda un pedido temporal para un usuario.
     *
     * @param  mixed  $user_id  El ID del usuario.
     */
    public function saveOrderTemp($user_id)
    {
        $order = new Order;
        $order->setUser_id($user_id);
        $order_id = $this->orderRepository->newOrder($order);

        foreach ($_SESSION['order'] as $productId => $item) {
            $orderLine = new OrderLine;
            $orderLine->setOrder_id($order_id);
            $orderLine->setProduct_id($productId);
            $orderLine->setQuantity($item['cantidad']);
            $this->orderLineRepository->newOrderLine($orderLine);
        }
    }

    /**
     * Obtiene el pedido temporal de un usuario.
     *
     * @param  mixed  $user_id  El ID del usuario.
     * @return array|false Los datos del pedido temporal o false si no existe.
     */
    public function getOrderTemp($user_id)
    {
        $result = $this->orderRepository->getOrderTemp($user_id);
        if (! $result) {
            return false;
        }

        return $result;

    }

    /**
     * Obtiene todos los pedidos.
     *
     * @return array Un array con la clave 'success' conteniendo todos los pedidos.
     */
    public function getOrders()
    {
        return ['success' => $this->orderRepository->getOrders()];
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * @param  mixed  $id  El ID del pedido a actualizar.
     * @return array Un array con la clave 'success' indicando si la actualización fue exitosa.
     */
    public function updateStatus($id)
    {
        return ['success' => $this->orderRepository->updateStatus($id)];
    }

    /**
     * Elimina un pedido.
     *
     * @param  mixed  $id  El ID del pedido a eliminar.
     * @return array Un array con la clave 'success' indicando si la eliminación fue exitosa.
     */
    public function deleteOrder($id)
    {
        return ['success' => $this->orderRepository->deleteOrder($id)];
    }
}
