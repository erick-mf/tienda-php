<?php

namespace App\Models;

use App\Traits\UserValidationTrait;

class User
{
    use UserValidationTrait;

    private $id_user;

    private $name;

    private $surnames;

    private $address;

    private $email;

    private $phone;

    private $password;

    private $role;

    private $token;

    private $token_exp;

    private $is_confirmed = false;

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

    public function token()
    {
        return $this->token;
    }

    public function token_exp()
    {
        return $this->token_exp;
    }

    public function is_confirmed()
    {
        return $this->is_confirmed;
    }

    public function setId_user($id_user): void
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

    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function setToken_exp($token_exp): void
    {
        $this->token_exp = $token_exp;
    }

    public function setIs_confirmed($is_confirmed): void
    {
        $this->is_confirmed = $is_confirmed;
    }
}
