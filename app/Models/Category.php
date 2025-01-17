<?php

namespace App\Models;

use App\Traits\CategoryValidateTrait;

class Category
{
    use CategoryValidateTrait;

    private $id;

    private $name;

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public static function fromArray(array $categoryData)
    {
        $category = new self;
        $category->setId($categoryData['id'] ?? null);
        $category->setName($categoryData['name'] ?? '');

        return $category;
    }
}
