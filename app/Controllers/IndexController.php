<?php

namespace App\Controllers;

class IndexController
{
    /**
     * Redirige según la existencia del token JWT.
     * Si no existe, envía al login; si existe, al dashboard.
     */
    public function index()
    {
        // Comprobar si hay un token en localStorage a través de JavaScript
        echo "
            <script>
                const token = localStorage.getItem('jwt_token');
                if (token) {
                    // Si el token existe, redirigir al dashboard
                    window.location.href = '/dashboard';
                } else {
                    // Si no existe, redirigir al login
                    window.location.href = '/login';
                }
            </script>
        ";
        exit();
    }
}
