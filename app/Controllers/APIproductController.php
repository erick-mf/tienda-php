<?php

namespace App\Controllers;

use App\Lib\FieldMapper;
use App\Lib\ResponseHttp;
use App\Services\ProductService;

/**
 * Clase APIproductController
 *
 * Esta clase maneja las operaciones de la API relacionadas con los productos.
 */
class APIproductController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService;
    }

    /**
     * Crea un nuevo producto o productos.
     *
     * Maneja solicitudes POST para crear nuevos productos.
     */
    public function newProduct()
    {
        ResponseHttp::setHeaders();
        ResponseHttp::authAPI();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHttp::statusMessages(405, 'Método no permitido');

            return;
        }

        $content = file_get_contents('php://input');
        $data = json_decode($content, true);

        if (empty($data)) {
            ResponseHttp::statusMessages(400, 'No se han proporcionado datos');

            return;
        }

        // Asegurarse de que sea un array
        if (! is_array($data)) {
            $data = [$data];
        }

        $created = 0;
        $errors = [];
        $sourceImageDir = $_ENV['SOURCE_IMG_DIR'];

        foreach ($data as $index => $value) {
            $productErrors = [];

            // Validar la imagen
            if (empty($value['image'])) {
                $productErrors['image'] = 'La imagen es obligatoria.';
            } else {
                $sourceImagePath = $sourceImageDir.$value['image'];
                if (! file_exists($sourceImagePath)) {
                    $productErrors['image'] = 'No se encontró la imagen para el producto.';
                } else {
                    $fileName = $value['image'];
                    $destinationPath = $_SERVER['DOCUMENT_ROOT'].IMG_URL.$fileName;

                    if (! copy($sourceImagePath, $destinationPath)) {
                        $productErrors['image'] = 'Error al copiar la imagen para el producto.';
                    } else {
                        $value['image'] = $fileName;
                    }
                }
            }

            // Añadir fecha actual si no se proporciona
            if (! isset($value['date'])) {
                $value['date'] = date('Y-m-d');
            }

            $result = $this->productService->save($value);
            if (! $result['success']) {
                $productErrors = array_merge($productErrors, $result['errors']);
            } else {
                $created++;
            }

            if (! empty($productErrors)) {
                $errors = $productErrors;
            }
        }

        if (! empty($errors)) {
            ResponseHttp::statusMessages(500, 'Error al crear algunos productos: ', $errors);
        } else {
            ResponseHttp::statusMessages(201, 'Productos creados: '.$created);
        }
    }

    /**
     * Obtiene todos los productos.
     *
     * Maneja solicitudes GET para obtener todos los productos.
     */
    public function getAll()
    {
        ResponseHttp::setHeaders();
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            ResponseHttp::statusMessages(405, 'Método no permitido');

            return;
        }

        $result = $this->productService->getProducts();
        if (! $result) {
            ResponseHttp::statusMessages(404, 'No se encontraron productos');

            return;
        } else {
            $products = $result;

            foreach ($products as &$product) {
                $product['imagen'] = IMG_URL_API . $product['imagen'];
            }

            ResponseHttp::statusMessages(200, 'Productos encontrados', $products);

            return;
        }
    }

    /**
     * Obtiene un producto por su ID.
     *
     * Maneja solicitudes GET para obtener un producto específico.
     *
     * @param  mixed  $id  El ID del producto a obtener.
     */
    public function getProductById($id)
    {
        ResponseHttp::setHeaders();
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            ResponseHttp::statusMessages(405, 'Método no permitido');

            return;
        }
        $result = $this->productService->findProductId($id);
        if (! $result) {
            ResponseHttp::statusMessages(404, 'No se encocntro el producto');

            return;
        } else {
            $product = $result;
            $product['imagen']= IMG_URL_API . $product['imagen'];

            ResponseHttp::statusMessages(200, 'Producto encontrado', $product);

            return;
        }
    }

    /**
     * Edita un producto existente.
     *
     * Maneja solicitudes PUT para actualizar un producto.
     *
     * @param  mixed  $id  El ID del producto a editar.
     */
    public function productEdit($id)
    {
        ResponseHttp::setHeaders();
        ResponseHttp::authAPI();
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            ResponseHttp::statusMessages(405, 'Método no permitido');

            return;
        }

        $newContent = file_get_contents('php://input');
        $newData = json_decode($newContent, true);

        if (empty($newData)) {
            ResponseHttp::statusMessages(400, 'No se han proporcionado nuevos datos');

            return;
        }

        if (! is_array($newData)) {
            ResponseHttp::statusMessages(400, 'La estructura de los datos no es la correcta');

            return;
        }

        $existingProduct = $this->productService->findProductId($id);
        if (! $existingProduct) {
            ResponseHttp::statusMessages(404, 'No se encontro el producto');

            return;
        }

        // NOTE: Usar ingles en la db me hubiera evitado esto
        $normalizedNewData = FieldMapper::normalizeData('product', $newData);
        $normalizedExistingProduct = FieldMapper::normalizeData('product', $existingProduct);

        // Combinar los datos normalizados
        $updateData = array_merge($normalizedExistingProduct, $normalizedNewData);
        $result = $this->productService->edit($id, $updateData);
        if (! $result['success']) {
            ResponseHttp::statusMessages(500, 'Error al editar el producto', $result['errors']);

            return;
        } else {
            $product = $result['success'];
            ResponseHttp::statusMessages(200, 'Producto editado correctamente');

            return;
        }
    }

    /**
     * Elimina un producto.
     *
     * Maneja solicitudes DELETE para eliminar un producto.
     *
     * @param  mixed  $id  El ID del producto a eliminar.
     */
    public function deleteProduct($id)
    {
        ResponseHttp::setHeaders();
        ResponseHttp::authAPI();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            ResponseHttp::statusMessages(405, 'Método no permitido');

            return;
        }
        $result = $this->productService->delete($id);
        if (! $result) {
            ResponseHttp::statusMessages(404, 'Producto no eliminado');

            return;
        } else {
            ResponseHttp::statusMessages(200, 'Producto eliminado');

            return;
        }
    }
}
