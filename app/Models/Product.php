<?php

namespace App\Models;

use App\Traits\ProductValidationTrait;

class Product
{
    use ProductValidationTrait;

    private $id;

    private $category_id;

    private $name;

    private $description;

    private $price;

    private $stock;

    private $offer;

    private $date;

    private $image;

    public function id()
    {
        return $this->id;
    }

    public function category_id()
    {
        return $this->category_id;
    }

    public function name()
    {
        return $this->name;
    }

    public function description()
    {
        return $this->description;
    }

    public function price()
    {
        return $this->price;
    }

    public function stock()
    {
        return $this->stock;
    }

    public function offer()
    {
        return $this->offer;
    }

    public function date()
    {
        return $this->date;
    }

    public function image()
    {
        return $this->image;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setCategory_id($category_id): void
    {
        $this->category_id = $category_id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function setStock($stock): void
    {
        $this->stock = $stock;
    }

    public function setOffer($offer): void
    {
        $this->offer = $offer;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function fromArray($data)
    {
        if (! empty($data)) {
            foreach ($data as $name => $value) {
                $method = 'set'.ucfirst($name);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        return $this;
    }
}
