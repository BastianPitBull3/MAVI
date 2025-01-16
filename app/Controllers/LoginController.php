<?php

namespace App\Controllers;

use App\Config\DatabaseConnection;
//use AuthController;

class LoginController
{
    public function __construct() {

    }

    public function show()
    {
        include __DIR__ . '/../Views/login.php'; // Asegúrate de que esta vista exista
    }
}
