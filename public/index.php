<?php

require_once '../vendor/autoload.php';
use App\Lib\Router;
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__.'/..');
    $dotenv->safeLoad();

    session_start();
    require_once '../app/config/config.php';
    require_once '../app/Routes/routes.php';
    Router::dispatch();
} catch (Exception $e) {
    exit('Error inesperado: '.$e->getMessage());
}
