<?php

require_once '../vendor/autoload.php'; // Autoload con Composer

use App\Controllers\IndexController;

// Crear una instancia del controlador IndexController
$controller = new IndexController();
$controller->index();





