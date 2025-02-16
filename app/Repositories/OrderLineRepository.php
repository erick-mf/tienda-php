<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\OrderLine;

/**
 * Clase OrderLineRepository
 *
 * Esta clase maneja las operaciones de base de datos relacionadas con las líneas de pedido.
 */
class OrderLineRepository
{
    private DataBase $db;

    private OrderLine $orderLine;

    public function __construct()
    {
        $this->db = new DataBase;
        $this->orderLine = new OrderLine;
    }

    /**
     * Crea una nueva línea de pedido en la base de datos.
     *
     * @param  OrderLine  $orderLine  El objeto OrderLine a guardar.
     * @return bool True si la línea de pedido se guarda correctamente, false en caso contrario.
     */
    public function newOrderLine(OrderLine $orderLine)
    {
        $sql = 'INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) 
                VALUES (:order_id, :product_id, :quantity)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':order_id', $orderLine->order_id());
        $stmt->bindValue(':product_id', $orderLine->product_id());
        $stmt->bindValue(':quantity', $orderLine->quantity());

        return $stmt->execute();
    }
}
