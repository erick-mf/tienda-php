<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\User;
use PDO;

/**
 * Clase UserRepository
 * Esta clase maneja las operaciones de base de datos relacionadas con los usuarios.
 */
class UserRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

    /**
     * Busca un usuario por su email.
     *
     * @param  string  $email  La dirección de email a buscar.
     * @return array|null Los datos del usuario como un array asociativo, o null si no se encuentra.
     */
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

    /**
     * Busca un usuario por su ID.
     *
     * @param  mixed  $id  El ID del usuario a buscar.
     * @return array|null Los datos del usuario como un array asociativo, o null si no se encuentra.
     */
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

    /**
     * Guarda un nuevo usuario en la base de datos.
     *
     * @param  User  $user  El objeto User a guardar.
     * @return User|null El objeto User guardado, o una Excepción si la operación falló.
     */
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

    /**
     * Obtiene todos los usuarios de la base de datos.
     *
     * @return array|false Un array de todos los usuarios, o false si no se encuentran usuarios.
     */
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

    /**
     * Elimina un usuario de la base de datos.
     *
     * @param  mixed  $id  El ID del usuario a eliminar.
     * @return bool True si el usuario fue eliminado, false en caso contrario.
     */
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

    /**
     * Edita la información de un usuario existente.
     *
     * @param  User  $user  El objeto User con la información actualizada.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function edit(User $user)
    {
        $sql = 'UPDATE usuarios SET
        nombre = :name,
        apellidos = :surnames,
        direccion = :address,
        telefono = :phone,
        rol = :role,
        confirmacion = :confirmation
        WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $user->id_user());
        $stmt->bindValue(':name', $user->name());
        $stmt->bindValue(':surnames', $user->surnames());
        $stmt->bindValue(':address', $user->address());
        $stmt->bindValue(':phone', $user->phone());
        $stmt->bindValue(':role', $user->role());
        $stmt->bindValue(':confirmation', $user->is_confirmed(), PDO::PARAM_BOOL);

        if (! $stmt->execute()) {
            return false;
        }

        return true;

    }

    /**
     * Actualiza el estado de confirmación de un usuario.
     *
     * @param  string  $email  El email del usuario a actualizar.
     * @param  bool  $is_confirmed  El nuevo estado de confirmación.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function confirmation($email, $is_confirmed)
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET confirmacion = :is_confirmed WHERE email = :email');
        $stmt->bindValue(':is_confirmed', $is_confirmed, PDO::PARAM_BOOL);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Actualiza el token y su fecha de expiración para un usuario.
     *
     * @param  string  $email  El correo electrónico del usuario a actualizar.
     * @param  string  $token  El nuevo token.
     * @param  string  $expiration  La fecha/hora de expiración del token.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateToken($email, $token, $expiration)
    {
        $sql = 'UPDATE usuarios SET token = :token, token_exp = :expiration WHERE email = :email';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expiration', $expiration);
        $stmt->bindValue(':email', $email);

        return $stmt->execute();
    }

    /**
     * Obtiene el token de un usuario.
     *
     * @param  string  $token  El token a buscar.
     * @return array|null Los datos del token como un array asociativo, o null si no se encuentra.
     */
    public function getToken($token)
    {
        $sql = 'SELECT token, rol FROM usuarios WHERE token = :token';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! $result) {
            return null;
        }

        return $result;

    }
}
