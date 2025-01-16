<?php

namespace App\Routes;

use App\Controllers\IndexController;

class Web 
{
    private $routes;
    public function __construct() {
        $this->routes = [
            '/CRUD-API/public/' => [IndexController::class, 'index'],
        ];
    }
    
    public function routes(){
        return $this->routes;
    }
}


