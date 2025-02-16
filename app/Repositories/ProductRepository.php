<?php

namespace App\Repositories;

use App\Lib\DataBase;
use App\Models\Product;

/**
 * Clase ProductRepository
 *
 * Esta clase maneja las operaciones de base de datos relacionadas con los productos.
 */
class ProductRepository
{
    private DataBase $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

    /**
     * Busca un producto por su nombre.
     *
     * @param  string  $name  El nombre del producto a buscar.
     * @return array|null Los datos del producto como un array asociativo, o null si no se encuentra.
     */
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

    /**
     * Busca un producto por su ID.
     *
     * @param  int  $id  El ID del producto a buscar.
     * @return array|null Los datos del producto como un array asociativo, o null si no se encuentra.
     */
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

    /**
     * Busca productos por categoría.
     *
     * @param  mixed  $id  El ID de la categoría.
     * @return array|false Un array de productos, o false si no se encuentran productos.
     */
    public function findProductByCategory($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE categoria_id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (! $products) {
            return false;
        }

        return $products;
    }

    /**
     * Guarda un nuevo producto en la base de datos.
     *
     * @param  Product  $product  El objeto Product a guardar.
     * @return Product|null El objeto Product guardado, o null si la operación falló.
     */
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

    /**
     * Obtiene todos los productos de la base de datos.
     *
     * @return array|null Un array de todos los productos, o null si no se encuentran productos.
     */
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

    /**
     * Elimina un producto de la base de datos.
     *
     * @param  int  $id  El ID del producto a eliminar.
     * @return bool True si el producto fue eliminado, false en caso contrario.
     */
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

    /**
     * Edita la información de un producto existente.
     *
     * @param  Product  $product  El objeto Product con la información actualizada.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
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
        if ($stmt->rowCount() === 0) {
            return false;
        }

        return true;
    }
}
