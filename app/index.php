<?php

namespace App\Config;
session_start();

// Verificar si existe un token en la sesión
if (isset($_SESSION['token'])) {
    // Redirigir al dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

