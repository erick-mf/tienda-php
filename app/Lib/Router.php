<?php

namespace App\Lib;

class Router
{
    private static $routes = [];

    private static $middlewares = [];

    public static function get(string $action, $controller): void
    {
        self::$routes['GET'][trim($action, '/')] = $controller;
    }

    public static function post(string $action, $controller): void
    {
        self::$routes['POST'][trim($action, '/')] = $controller;
    }

    public static function put(string $action, $controller): void
    {
        self::$routes['PUT'][trim($action, '/')] = $controller;
    }

    public static function delete(string $action, $controller): void
    {
        self::$routes['DELETE'][trim($action, '/')] = $controller;
    }

    public static function addMiddleware($prefijo, $middleware)
    {
        self::$middlewares[$prefijo] = $middleware;
    }

    public static function dispatch()
    {
        $pages = new Pages;
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        foreach (self::$routes[$method] as $route => $controller) {
            $pattern = preg_replace('/:\w+/', '([^/]+)', trim($route, '/')); // Convierte :param en una expresión regular
            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches); // Elimina el primer match (URL completa)

                // Verificar middleware
                foreach (self::$middlewares as $prefix => $middleware) {
                    if (strpos($route, $prefix) === 0 && ! $middleware::isValid()) {
                        ResponseHttp::statusMessages(403, 'Acceso denegado');

                        return $pages->render('errors/error403', []);
                    }
                }

                // Ejecutar controlador
                if (is_array($controller)) {
                    $controllerName = $controller[0];
                    $methodName = $controller[1];

                    if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                        $controllerInstance = new $controllerName;

                        return call_user_func_array([$controllerInstance, $methodName], $matches);
                    }
                } else {
                    return call_user_func_array($controller, $matches);
                }
            }
        }

        ResnponseHttp::statusMessages(404, 'Ruta no encontrada');

        return $pages->render('errors/error404', []);
    }
}
