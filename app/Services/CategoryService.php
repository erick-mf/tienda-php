<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

/**
 * Clase CategoryService
 *
 * Esta clase proporciona servicios relacionados con la gestión de categorías.
 */
class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository;
    }

    /**
     * Crea una nueva categoría.
     *
     * @param  array  $categoryData  Los datos de la nueva categoría.
     * @return array Un array con las claves 'success', 'errors' (si los hay) y 'category' (si se creó con éxito).
     */
    public function new(array $categoryData): array
    {
        $category = new Category;
        $category->setName($categoryData['name']);

        $errors = $category->validate();
        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($this->categoryRepository->findCategory($categoryData['name'])) {
            return ['success' => false, 'errors' => ['name' => 'La categoria ya está registrada']];
        }

        try {
            $savedCategory = $this->categoryRepository->save($category);
            if ($savedCategory) {
                return ['success' => true, 'category' => $category];
            } else {
                throw new \Exception('No se pudo completar el registro de la categoria');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Muestra todas las categorías.
     *
     * @return array|null Un array con todas las categorías o null si no hay categorías.
     */
    public function show()
    {
        return $this->categoryRepository->show();
    }

    /**
     * Elimina una categoría por su ID.
     *
     * @param  int  $id  El ID de la categoría a eliminar.
     * @return bool True si la categoría fue eliminada con éxito, false en caso contrario.
     */
    public function delete(int $id): bool
    {
        $result = $this->categoryRepository->delete($id);
        if (! $result) {
            $_SESSION['message'] = 'No se puede eliminar la categoría porque tiene productos asociados.';

            return false;
        }
        $_SESSION['message'] = 'Categoría eliminada exitosamente';

        return true;
    }

    /**
     * Busca una categoría por su ID.
     *
     * @param  int  $id  El ID de la categoría a buscar.
     * @return array|null Los datos de la categoría o null si no se encuentra.
     */
    public function findCategoryId(int $id)
    {
        return $this->categoryRepository->findCategoryId($id);
    }

    /**
     * Edita una categoría existente.
     *
     * @param  array  $categoryData  Los datos actualizados de la categoría.
     * @return array Un array con la clave 'success' indicando si la edición fue exitosa y 'errors' si hubo errores.
     */
    public function edit($categoryData)
    {
        $category = Category::fromArray($categoryData);
        $errors = $category->validate();

        if (! empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $result = $this->categoryRepository->edit($category);

        return ['success' => $result];
    }
}
