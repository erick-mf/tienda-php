<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\Category;

class CategoryRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

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
