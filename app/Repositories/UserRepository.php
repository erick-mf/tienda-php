<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\User;
use PDO;

class UserRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $user) {
            return null;
        }

        return $user;
    }

    public function save(User $user): ?User
    {
        $sql = 'INSERT INTO usuarios (nombre, apellidos, direccion, email, telefono, password, rol, token, token_exp, confirmacion)
        VALUES (:nombre, :apellidos, :direccion, :email, :telefono, :password, :rol, :token, :token_exp, :confirmacion)';

        try {
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':nombre', $user->name(), PDO::PARAM_STR);
            $stmt->bindValue(':apellidos', $user->surnames(), PDO::PARAM_STR);
            $stmt->bindValue(':direccion', $user->address(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $user->email(), PDO::PARAM_STR);
            $stmt->bindValue(':telefono', $user->phone(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $user->password(), PDO::PARAM_STR);
            $stmt->bindValue(':rol', $user->role(), PDO::PARAM_STR);
            $stmt->bindValue(':token', $user->token(), PDO::PARAM_STR);
            $stmt->bindValue(':token_exp', $user->token_exp(), PDO::PARAM_STR);
            $stmt->bindValue(':confirmacion', $user->is_confirmed(), PDO::PARAM_BOOL);

            if (! $stmt->execute()) {
                throw new \PDOException('Error al insertar el usuario en la base de datos');
            }

            return $user;
        } catch (\PDOException $e) {
            throw new \Exception('Error al guardar el usuario: '.$e->getMessage());
        }
    }

    public function confirmation($email, $is_confirmed)
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET confirmacion = :is_confirmed WHERE email = :email');
        $stmt->bindValue(':is_confirmed', $is_confirmed, PDO::PARAM_BOOL);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
