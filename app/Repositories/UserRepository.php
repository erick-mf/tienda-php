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

    public function findUserById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->bindValue(':id', $id);
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

    public function getUsers()
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (! $users) {
            return false;
        }

        return $users;
    }

    public function deleteUser($id)
    {
        // $id = 1000;
        $sql = 'DELETE FROM usuarios WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $count = $stmt->rowCount();
        if ($count === 0) {
            return false;
        }

        return true;
    }

    public function edit(User $user)
    {
        $sql = 'UPDATE usuarios SET
        nombre = :name,
        apellidos = :surnames,
        direccion = :address,
        email = :email,
        telefono = :phone,
        rol = :role,
        confirmacion = :confirmation
        WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $user->id_user());
        $stmt->bindValue(':name', $user->name());
        $stmt->bindValue(':surnames', $user->surnames());
        $stmt->bindValue(':address', $user->address());
        $stmt->bindValue(':email', $user->email());
        $stmt->bindValue(':phone', $user->phone());
        $stmt->bindValue(':role', $user->role());
        $stmt->bindValue(':confirmation', $user->is_confirmed(), PDO::PARAM_BOOL);

        if (! $stmt->execute()) {
            return false;
        }

        return true;

    }

    public function confirmation($email, $is_confirmed)
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET confirmacion = :is_confirmed WHERE email = :email');
        $stmt->bindValue(':is_confirmed', $is_confirmed, PDO::PARAM_BOOL);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
