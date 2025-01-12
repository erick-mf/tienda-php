<?php

namespace App\Lib;

use PDO;
use PDOException;

class DataBase extends PDO
{
    private string $server;
    private string $db;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->server = $_ENV["SERVER"];
        $this->db=  $_ENV["DB"];
        $this->username = $_ENV["USERNAME"];
        $this->password = $_ENV["PASSWORD"];

        try {
            $opciones = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            parent::__construct("mysql:host={$this->server};dbname={$this->db}", $this->username, $this->password, $opciones);
        } catch (PDOException $e) {
            throw new PDOException("Error de conexión: " . $e->getMessage());
        }
    }
}
