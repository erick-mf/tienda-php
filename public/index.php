<?php

require_once '../vendor/autoload.php';
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__.'/..');
    $dotenv->safeLoad();

    session_start();
    require_once '../app/config/config.php';
    require_once '../app/Routes/routes.php';
} catch (Exception $e) {
    exit('Error inesperado: '.$e->getMessage());
}
