<?php

namespace App\Controllers;

use App\Lib\Pages;
use App\Services\CategoryService;

class CategoryController
{
    private CategoryService $categoryService;

    private Pages $page;

    public function __construct()
    {
        $this->categoryService = new CategoryService;
        $this->page = new Pages;
    }

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

    public function show()
    {
        $results = $this->categoryService->show();
        $msg = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);

        if (empty($results)) {
            $this->page->render('category/show', []);
        } else {
            $this->page->render('category/show', ['results' => $results, 'msg' => $msg]);
        }
    }

    public function delete($idStrg)
    {
        $id = (int) $idStrg;
        if (! $this->categoryService->delete($id)) {
            $_SESSION['message'] = 'Error al eliminar la categoría';
        }
        header('Location: /admin/category');
        exit;
    }

    public function showEditCategory($id)
    {
        $category = $this->categoryService->findCategoryId((int) $id);

        if (! $category) {
            header('Location: /admin/category');
            exit;
        }
        $this->page->render('category/edit', ['result' => $category]);
    }

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
