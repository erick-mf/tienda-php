<?php

namespace App\Repositories;

use App\Lib\DataBase;

class OrderRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }
}
