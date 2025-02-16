<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\Order;

/**
 * Clase OrderRepository
 *
 * Esta clase maneja las operaciones de base de datos relacionadas con los pedidos.
 */
class OrderRepository
{
    private DataBase $db;

    private Order $order;

    public function __construct()
    {
        $this->db = new DataBase;
        $this->order = new Order;
    }

    /**
     * Crea un nuevo pedido en la base de datos.
     *
     * @param  Order  $order  El objeto Order a guardar.
     * @return int|false El ID del nuevo pedido si se guarda correctamente, o false si falla.
     */
    public function newOrder(Order $order)
    {
        $sql = 'INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora) 
                VALUES (:usuario_id, :provincia, :localidad, :direccion, :coste, :estado, :fecha, :hora)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $order->user_id());
        $stmt->bindValue(':provincia', $order->state());
        $stmt->bindValue(':localidad', $order->city());
        $stmt->bindValue(':direccion', $order->address());
        $stmt->bindValue(':coste', $order->cost());
        $stmt->bindValue(':estado', $order->status());
        $stmt->bindValue(':fecha', $order->date());
        $stmt->bindValue(':hora', $order->time());

        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // tengo que devolver el id porque sino no puedo hacer el insert en linea_pedidos
        }

        return false;
    }

    /**
     * Obtiene los pedidos temporales (en progreso) de un usuario.
     *
     * @param  mixed  $user_id  El ID del usuario.
     * @return array|false Un array con los detalles del pedido temporal, o false si no se encuentra.
     */
    public function getOrderTemp($user_id)
    {
        $sql = "SELECT pedidos.id, pedidos.estado, lineas_pedidos.producto_id, lineas_pedidos.unidades, 
                   productos.nombre, productos.precio, productos.stock, productos.imagen
            FROM pedidos
            JOIN lineas_pedidos ON pedidos.id = lineas_pedidos.pedido_id
            JOIN productos ON lineas_pedidos.producto_id = productos.id
            WHERE pedidos.usuario_id = :user_id AND pedidos.estado = 'en progreso'";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $orderTemp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (! $orderTemp) {
            return false;
        }

        return $orderTemp;
    }

    /**
     * Obtiene todos los pedidos confirmados.
     *
     * @return array|false Un array con todos los pedidos confirmados, o false si no hay pedidos.
     */
    public function getOrders()
    {
        $sql = "
SELECT 
    pedidos.id,
    pedidos.fecha,
    pedidos.coste,
    pedidos.estado,
    pedidos.provincia,
    pedidos.localidad,
    pedidos.direccion,
    usuarios.email,
    usuarios.direccion,
    usuarios.nombre,
    usuarios.apellidos,
    usuarios.telefono
FROM 
    pedidos
JOIN 
    usuarios ON pedidos.usuario_id = usuarios.id
WHERE 
    pedidos.estado = 'confirmado'
ORDER BY 
    pedidos.fecha DESC;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (! $orders) {
            return false;
        }

        return $orders;
    }

    /**
     * Actualiza el estado de un pedido a 'entregado'.
     *
     * @param  mixed  $id  El ID del pedido a actualizar.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateStatus($id)
    {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = 'entregado' WHERE id = :id");
        $stmt->bindValue(':id', $id);
        if (! $stmt->execute()) {
            return false;
        }
        if ($stmt->rowCount() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Elimina un pedido en progreso y sus líneas asociadas.
     *
     * @param  mixed  $id  El ID del usuario cuyo pedido en progreso se eliminará.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function deleteOrder($id)
    {
        $stmtLines = $this->db->prepare("DELETE FROM lineas_pedidos WHERE pedido_id IN (SELECT id FROM pedidos WHERE estado = 'en progreso' AND usuario_id = :id)");
        $stmtLines->bindValue(':id', $id);
        if (! $stmtLines->execute()) {
            return false;
        }

        $stmtOrder = $this->db->prepare("DELETE FROM pedidos WHERE estado = 'en progreso' AND usuario_id = :id");
        $stmtOrder->bindValue(':id', $id);
        if (! $stmtOrder->execute()) {
            return false;
        }

        if ($stmtOrder->rowCount() === 0) {
            return false;
        }

        return true;
    }
}
