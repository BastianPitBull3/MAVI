<?php

use App\Controllers\AuthController;
use App\Config\DatabaseConnection;

// Obtén los parámetros del formulario de login
$email = $_POST['email'] ?? ''; // Usamos un valor por defecto si no se recibe
$password = $_POST['password'] ?? '';

// Conexión a la base de datos
$host = "localhost";
$db = "mavicrudapi";
$user = "root";
$password = "";

$pdo = new DatabaseConnection($host, $db, $user, $password);

// Llamada al método de login pasando los parámetros
$authController = new AuthController($pdo);
$authController->login($email, $password);
