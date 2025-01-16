<?php

namespace App\Controllers;

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        header('Content-Type: application/json');

        try {
            echo json_encode(["message" => "entra"]);
            // Lógica para validar el correo (sin autenticación basada en contraseñas)
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                // Si el usuario existe, devuelve una respuesta de éxito simple
                echo json_encode(["success" => true, "message" => "Bienvenido, " . $user['email']]);
            } else {
                // Si el usuario no existe, devuelve un mensaje de error
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error interno del servidor"]);
        }
    }
}
