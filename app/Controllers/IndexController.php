<?php

namespace App\Controllers;

use App\Controllers\LoginController;

class IndexController
{
    private $controller;

    public function __construct(){
        
    }

    public function index()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($requestUri === "/CRUD-API/public/") {
            $this->controller = new LoginController();
            $this->controller->show();
            return;
        }
    }
}
