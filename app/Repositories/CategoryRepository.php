<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\Category;

/**
 * Clase CategoryRepository
 *
 * Esta clase maneja las operaciones de base de datos relacionadas con las categorías.
 */
class CategoryRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

    /**
     * Busca una categoría por su nombre.
     *
     * @param  string  $name  El nombre de la categoría a buscar.
     * @return array|null Los datos de la categoría como un array asociativo, o null si no se encuentra.
     */
    public function findCategory(string $name): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categorias WHERE nombre = :name');
        $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
        $stmt->execute();

        $category = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! $category) {
            return null;
        }

        return $category;
    }

    /**
     * Busca una categoría por su ID.
     *
     * @param  int  $id  El ID de la categoría a buscar.
     * @return array|null Los datos de la categoría como un array asociativo, o null si no se encuentra.
     */
    public function findCategoryId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categorias WHERE id = :id');
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $category = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! $category) {
            return null;
        }

        return $category;
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     *
     * @param  Category  $category  El objeto Category a guardar.
     * @return Category|null El objeto Category guardado, o null si la operación falló.
     *
     * @throws \PDOException Si hay un error al guardar la categoría.
     */
    public function save(Category $category): ?Category
    {
        $sql = 'INSERT INTO categorias (nombre) VALUES (:name)';

        try {
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':name', $category->name(), \PDO::PARAM_STR);

            if (! $stmt->execute()) {
                throw new \PDOException('Error al insertar la categoria en la base de datos');
            }

            return $category;
        } catch (\PDOException $e) {
            throw new \PDOException('Error al guardar la categoria: '.$e->getMessage());
        }
    }

    /**
     * Obtiene todas las categorías de la base de datos.
     *
     * @return array|null Un array de todas las categorías, o null si no se encuentran categorías.
     */
    public function show(): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categorias');
        $stmt->execute();

        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (! $categories) {
            return null;
        }

        return $categories;
    }

    /**
     * Elimina una categoría de la base de datos.
     *
     * @param  int  $id  El ID de la categoría a eliminar.
     * @return bool True si la categoría fue eliminada, false si no se pudo eliminar o si tiene productos asociados.
     */
    public function delete(int $id): bool
    {
        $sqlAux = 'SELECT COUNT(*) FROM productos WHERE categoria_id = :id';
        $stmtAux = $this->db->prepare($sqlAux);
        $stmtAux->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmtAux->execute();

        if ($stmtAux->fetchColumn() > 0) {
            return false;
        }

        $sql = 'DELETE FROM categorias WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Edita la información de una categoría existente.
     *
     * @param  Category  $category  El objeto Category con la información actualizada.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function edit(Category $category): bool
    {
        $sql = 'UPDATE categorias SET nombre = :newName WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $category->id(), \PDO::PARAM_INT);
        $stmt->bindValue(':newName', $category->name(), \PDO::PARAM_STR);

        if (! $stmt->execute()) {
            return false;
        }

        return true;
    }
}
