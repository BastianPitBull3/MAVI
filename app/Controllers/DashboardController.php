<?php

namespace App\Controllers;

class DashboardController
{
    /**
     * Muestra la vista del dashboard.
     */
    public function dashboard()
    {
        require_once __DIR__ . '/../Views/dashboard.php'; // Carga la vista de dashboard
    }
}