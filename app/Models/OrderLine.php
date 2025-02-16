<?php

namespace App\Models;

class OrderLine
{
    private $id;

    private $order_id;

    private $product_id;

    private $quantity;

    public function id()
    {
        return $this->id;
    }

    public function order_id()
    {
        return $this->order_id;
    }

    public function product_id()
    {
        return $this->product_id;
    }

    public function quantity()
    {
        return $this->quantity;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setOrder_id($order_id): void
    {
        $this->order_id = $order_id;
    }

    public function setProduct_id($product_id): void
    {
        $this->product_id = $product_id;
    }

    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    public function fromArray(array $orderLineData): self
    {
        if (! empty($orderLineData)) {
            foreach ($orderLineData as $name => $value) {
                $method = 'set'.ucfirst($name);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        return $this;
    }
}
