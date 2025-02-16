<?php

namespace App\Lib;

use App\Services\CategoryService;

class Pages
{
    private CategoryService $categories;

    public function __construct()
    {
        $this->categories = new CategoryService;
    }

    public function render(string $page_name, ?array $params = null): void
    {
        if ($params != null) {
            $params['categories'] = $this->categories->show();
            foreach ($params as $name => $value) {
                $$name = $value;
            }
        }
        include_once '../app/views/layouts/header.php';
        include_once "../app/views/$page_name.php";
        include_once '../app/views/layouts/footer.php';

    }
}
