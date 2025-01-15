<?php

require_once '../vendor/autoload.php';

use App\Controllers\IndexController;

// Obtener la URL solicitada
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Enrutador simple
switch ($requestUri) {
    case '/':
        // Llama al método index() del controlador IndexController
        $controller = new IndexController();
        $controller->index();
        break;

    case '/login':
        // Renderizar vista de login
        echo "<h1>Página de Login</h1>";
        break;

    case '/dashboard':
        // Renderizar vista del dashboard
        echo "<h1>Dashboard</h1>";
        break;

    default:
        // Página no encontrada
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}




