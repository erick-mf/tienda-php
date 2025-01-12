<?php

namespace App\Controllers;

use App\Lib\Pages;

class HomeController
{
    private Pages $pages;

    public function __construct()
    {
        $this->pages = new Pages();
    }

    public function index()
    {
        return $this->pages->render("home/index", []);
    }
}
