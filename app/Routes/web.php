<<?php

use App\Controllers\IndexController;
use App\Controllers\ProductController;

// Definir las rutas
return [
    '/' => [IndexController::class, 'index'],            // Página principal
    '/users' => [UserController::class, 'index'],       // Listar usuarios
    '/users/create' => [UserController::class, 'create'], // Crear usuario
    '/products' => [ProductController::class, 'index'], // Listar productos
    '/products/create' => [ProductController::class, 'create'], // Crear producto
];