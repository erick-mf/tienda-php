<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\Product;

class ProductRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

    public function findProduct(string $name): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE nombre = :name');
        $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
        $stmt->execute();

        $product = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! $product) {
            return null;
        }

        return $product;
    }

    public function findProductID(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE id = :id');
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! $product) {
            return null;
        }

        return $product;
    }

    public function save(Product $product): ?Product
    {
        $sql = 'INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen)
            VALUES (:category_id, :name, :description, :price, :stock, :offer, :date, :image)';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':category_id', $product->category_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':name', $product->name(), \PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->description(), \PDO::PARAM_STR);
        $stmt->bindValue(':price', $product->price(), \PDO::PARAM_STR);
        $stmt->bindValue(':stock', $product->stock(), \PDO::PARAM_INT);
        $stmt->bindValue(':offer', $product->offer(), \PDO::PARAM_STR);
        $stmt->bindValue(':date', $product->date(), \PDO::PARAM_STR);
        $stmt->bindValue(':image', $product->image(), \PDO::PARAM_STR);

        if (! $stmt->execute()) {
            return null;
        }

        return $product;
    }

    public function getProducts(): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM productos');
        $stmt->execute();

        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (! $products) {
            return null;
        }

        return $products;
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM productos WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        if (! $stmt->rowCount() > 0) {
            return false;
        }

        return true;
    }

    public function edit(Product $product): bool
    {
        $sql = 'UPDATE productos SET
            categoria_id = :category_id,
            nombre = :name,
            descripcion = :description,
            precio = :price,
            stock = :stock,
            oferta = :offer,
            fecha = :date,
            imagen = :image
            WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $product->id(), \PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $product->category_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':name', $product->name(), \PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->description(), \PDO::PARAM_STR);
        $stmt->bindValue(':price', $product->price(), \PDO::PARAM_STR);
        $stmt->bindValue(':stock', $product->stock(), \PDO::PARAM_INT);
        $stmt->bindValue(':offer', $product->offer(), \PDO::PARAM_STR);
        $stmt->bindValue(':date', $product->date(), \PDO::PARAM_STR);
        $stmt->bindValue(':image', $product->image(), \PDO::PARAM_STR);

        if (! $stmt->execute()) {
            return false;
        }

        return true;
    }
}
