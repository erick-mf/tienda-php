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
        // Obtener la URI sin el prefijo del proyecto
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $action = trim($uri, '/');

        $param = null;
        if (preg_match('/[0-9]+$/', $action, $match)) {
            $param = $match[0];
            $action = preg_replace('/'.$match[0].'$/', ':id', $action);
        }

        // Verificar middlewares
        foreach (self::$middlewares as $prefix => $middleware) {
            if (strpos($action, $prefix) === 0) {
                if (! $middleware::isValid()) {
                    http_response_code(403);

                    return $pages->render('errors/error403', []);
                }
                break;
            }
        }

        // Verificar si la ruta existe
        if (isset(self::$routes[$method][$action])) {
            $routeAction = self::$routes[$method][$action];

            if (is_array($routeAction)) {
                $controllerName = $routeAction[0];
                $methodName = $routeAction[1];

                if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                    $controller = new $controllerName;

                    return $controller->$methodName($param);
                } else {
                    http_response_code(500);

                    return $pages->render('errors/error500', []);
                }
            } else {
                return $routeAction($param);
            }
        } else {
            http_response_code(404);

            return $pages->render('errors/error404', []);
        }
    }
}
