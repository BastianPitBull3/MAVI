<<?php

use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;

// Definir las rutas
return [
    '/' => [IndexController::class, 'index'],
    '/login' => [LoginController::class, 'login'],
    '/dashboard' => [DashboardController::class, 'getClients']
];