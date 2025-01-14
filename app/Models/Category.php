<?php

namespace App\Models;

class Category
{
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

    public function validate()
    {
        $errors = [];

        return $errors;
    }
}
