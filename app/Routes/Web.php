<?php

namespace App\Routes;

use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;

class Web 
{
    public function __construct() {
        $this->routes = [
            '/CRUD-API/public/' => [IndexController::class, 'index'],
            '/login' => [LoginController::class, 'show'],
            '/login/auth' => [LoginController::class, 'auth'],
            '/dashboard' => [DashboardController::class, 'getClients']
        ];
    }
    
    public function routes(){
        return $this->routes;
    }
}


