<?php

use App\Controllers\Api\AuthController;
use App\Controllers\Api\JwtHandler;
use App\Controllers\Api\ClientApiController;
use App\Controllers\Api\UserApiController;

// Definir las rutas
return [
    '/' => [IndexController::class, 'index'],
    '/login' => [LoginController::class, 'login'],
    '/dashboard' => [DashboardController::class, 'getClients']
];