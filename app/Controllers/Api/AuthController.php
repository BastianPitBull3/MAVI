<?php

namespace App\Controllers;

use App\Config\JwtHandler;

class AuthController {
    private $pdo;
    private $jwtHandler;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->jwtHandler = new JwtHandler(); // Instancia de JWT
    }

    public function login($email, $password) {
        header('Content-Type: application/json');

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Generar token JWT
                $jwt = $this->jwtHandler->generate_jwt([
                    "id" => $user['id'],
                    "email" => $user['email']
                ]);

                echo "<h1>Success</h1>";

                return json_encode(["success" => true, "token" => $jwt]);
            } else {
                echo ":(";
                http_response_code(401);
                return json_encode(["success" => false, "message" => "Credenciales inválidas"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(["success" => false, "message" => "Error interno del servidor"]);
        }
    }
}
