<?php

require_once __DIR__ . '/../../Config/DatabaseConnection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AuthController {
    private $dbConn;
    private $pdo;

    public function __construct() {
        $this->dbConn = new App\Config\DatabaseConnection();
        $this->pdo = $this->dbConn->connect();
    }

    public function login() {
        header('Content-Type: application/json');

        // Decodificar los datos JSON enviados desde la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
            return;
        } 

        $email = $data['email'];
        $password = $data['password'];

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $data['email']]);
            $user = $stmt->fetch();

            if ($user && password_verify($data['password'], $user['password'])) {
                echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso"]);
            } else {
                http_response_code(401);
                echo json_encode(["success" => false, "message" => "Correo electrónico o contraseña incorrectos"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error interno del servidor", "error" => $e->getMessage()]);
        }
    }
}

// Instanciar el controlador y llamar al método según sea necesario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    $authController->login();
}
