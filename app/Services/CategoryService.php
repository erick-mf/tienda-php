<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository;
    }

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

    public function show()
    {
        return $this->categoryRepository->show();
    }

    public function delete(int $id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function findCategoryId(int $id)
    {
        return $this->categoryRepository->findCategoryId($id);
    }

    public function edit(int $id, string $newName)
    {
        return $this->categoryRepository->edit($id, $newName);
    }
}
