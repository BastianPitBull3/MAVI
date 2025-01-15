<?php

namespace App\Controllers;

class LoginController
{
    /**
     * Muestra la vista de login.
     */
    public function login()
    {
        require_once __DIR__ . '/../Views/login.php'; // Carga la vista de login
    }
}
