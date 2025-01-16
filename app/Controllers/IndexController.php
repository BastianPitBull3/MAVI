<?php

namespace App\Controllers;

use App\Routes\Web;
use App\Controllers\LoginController;

class IndexController
{
    private $controller;
    private $method;
    private $routes;

    public function __construct($controller = null, $method = null)
    {
        $this->controller = $controller;
        $this->method = $method;

        // Carga las rutas definidas en Web
        $web = new Web();
        $this->routes = $web->routes();
    }

    public function index()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($requestUri === "/CRUD-API/public/") {
            $this->controller = new LoginController();
            $this->controller->show();
            return;
        }
        if (isset($this->routes[$requestUri])) {
            [$controllerName, $methodName] = $this->routes[$requestUri];

            if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                $controller = new $controllerName();
                $controller->{$methodName}();
            } else {
                $this->handleError("El controlador o método no existen: $controllerName::$methodName");
            }
        } else {
            $this->handleNotFound();
        }
    }

    private function handleError($message)
    {
        http_response_code(500);
        echo "<h1>Error: $message</h1>";
    }

    private function handleNotFound()
    {
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
    }
}
