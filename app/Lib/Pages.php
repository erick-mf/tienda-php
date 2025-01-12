<?php

namespace App\Lib;

class Pages
{
    public function render(string $page_name, array $params = null): void
    {
        if ($params != null) {
            foreach ($params as $name => $value) {
                $$name = $value;
            }
        }
        include_once "../app/views/layouts/header.php";
        include_once "../app/views/$page_name.php";
        include_once "../app/views/layouts/footer.php";
        ;
    }

}
