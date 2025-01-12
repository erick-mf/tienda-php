<?php

namespace App\Models;

class User
{
    private $id_user;

    private $name;

    private $surnames;

    private $address;

    private $email;

    private $phone;

    private $password;

    private $role;

    public function id_user()
    {
        return $this->id_user;
    }

    public function name()
    {
        return $this->name;
    }

    public function surnames()
    {
        return $this->surnames;
    }

    public function address()
    {
        return $this->address;
    }

    public function email()
    {
        return $this->email;
    }

    public function phone()
    {
        return $this->phone;
    }

    public function password()
    {
        return $this->password;
    }

    public function role()
    {
        return $this->role;
    }

    public function setIdUser($id_user): void
    {
        $this->id_user = $id_user;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setSurnames($surnames): void
    {
        $this->surnames = $surnames;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function validate()
    {
        $errors = [];
        return $errors;
    }
}
