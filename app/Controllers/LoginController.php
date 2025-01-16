<?php

namespace App\Controllers;

use App\Config\DatabaseConnection;
use App\Controllers\AuthController;

class LoginController
{
    private $pdo;

    public function __construct() {
        // Configuración de conexión a la base de datos
        $host = "localhost";
        $db = "mavicrudapi";
        $user = "root";
        $password = "";

        $dbConnection = new DatabaseConnection($host, $db, $user, $password);
        $this->pdo = $dbConnection->connect(); // Obtenemos la instancia de PDO
    }

    public function auth()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibimos y decodificamos los datos JSON
            $data = json_decode(file_get_contents("php://input"));

            if (empty($data->email) || empty($data->password)) {
                echo json_encode(['success' => false, 'message' => 'Por favor ingresa usuario y contraseña']);
                return;
            }

            // Autenticación
            $authController = new AuthController($this->pdo);
            $response = $authController->login($data->email, $data->password);

            echo $response;
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public function show()
    {
        include __DIR__ . '/../Views/login.php'; // Asegúrate de que esta vista exista
    }
}
