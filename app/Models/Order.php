<?php

namespace App\Models;

use App\Traits\OrderValidationTrait;

class Order
{
    use OrderValidationTrait;

    private $id;

    private $user_id;

    private $state;

    private $city;

    private $address;

    private $cost;

    private $status = 'en progreso';

    private $date;

    private $time;

    public function id()
    {
        return $this->id;
    }

    public function user_id()
    {
        return $this->user_id;
    }

    public function state()
    {
        return $this->state;
    }

    public function city()
    {
        return $this->city;
    }

    public function address()
    {
        return $this->address;
    }

    public function cost()
    {
        return $this->cost;
    }

    public function status()
    {
        return $this->status;
    }

    public function date()
    {
        return $this->date;
    }

    public function time()
    {
        return $this->time;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setUser_id($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function setCity($city): void
    {
        $this->city = $city;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function setCost($cost): void
    {
        $this->cost = $cost;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function setTime($time): void
    {
        $this->time = $time;
    }

    public function fromArray(array $orderData): self
    {
        if (! empty($orderData)) {
            foreach ($orderData as $name => $value) {
                $method = 'set'.ucfirst($name);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        return $this;
    }
}
