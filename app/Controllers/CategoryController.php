<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\CategoryService;

/**
 * Clase CategoryController
 *
 * Esta clase maneja las operaciones relacionadas con las categorías.
 */
class CategoryController
{
    private CategoryService $categoryService;

    private Pages $page;

    public function __construct()
    {
        $this->categoryService = new CategoryService;
        $this->page = new Pages;
    }

    /**
     * Crea una nueva categoría.
     *
     * Maneja las solicitudes POST para crear una nueva categoría y renderiza el formulario.
     */
    public function new(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryData = [
                'name' => $_POST['category']['name'],
            ];

            try {
                $result = $this->categoryService->new($categoryData);
                if ($result['success']) {
                    header('Location: /admin/category');
                    exit;
                } else {
                    $this->page->render('category/new', ['errors' => $result['errors']]);
                }
            } catch (\Exception $e) {
                $this->page->render('category/new', ['error' => $e->getMessage()]);
            }
        } else {
            $this->page->render('category/new', []);
        }
    }

    /**
     * Muestra todas las categorías.
     *
     * Obtiene y muestra la lista de todas las categorías.
     */
    public function show()
    {
        $results = $this->categoryService->show();

        if (empty($results)) {
            $this->page->render('category/show', []);
        } else {
            $this->page->render('category/show', ['results' => $results]);
        }
    }

    /**
     * Elimina una categoría.
     *
     * @param  string  $idStrg  El ID de la categoría a eliminar.
     */
    public function delete($idStrg)
    {
        $id = (int) $idStrg;
        $result = $this->categoryService->delete($id);
        header('Location: /admin/category');
        exit;
    }

    /**
     * Muestra el formulario de edición de una categoría.
     *
     * @param  mixed  $id  El ID de la categoría a editar.
     */
    public function showEditCategory($id)
    {
        $category = $this->categoryService->findCategoryId((int) $id);

        if (! $category) {
            header('Location: /admin/category');
            exit;
        }
        $this->page->render('category/edit', ['category' => $category]);
    }

    /**
     * Actualiza la información de una categoría.
     *
     * @param  mixed  $id  El ID de la categoría a actualizar.
     */
    public function updateCategory($id)
    {
        $category = $this->categoryService->findCategoryId((int) $id);
        $updateCategory = [
            'id' => $id ?? $category['id'],
            'name' => $_POST['category']['name'] ?? $category['nombre'],
        ];
        if (empty($updateCategory)) {
            $this->page->render('category/edit', ['category' => ['id' => $id]]);

            return;
        }

        $result = $this->categoryService->edit($updateCategory);
        if (! $result['success']) {
            $this->page->render('category/edit', ['errors' => $result['errors'], 'category' => $category]);
        } else {
            header('Location: /admin/category');
            exit;
        }
    }
}
