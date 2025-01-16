<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Config\DatabaseConnection;

class UserApiController {
    private $dbConn;
    private $pdo;

    public function __construct() {
        $this->dbConn = new DatabaseConnection();
        $this->pdo = $this->dbConn->connect();
    }

    /**
     * Crea un nuevo usuario en la base de datos utilizando un modelo de usuario.
     * @param User $user Modelo de usuario.
     * @return string Mensaje de éxito o error.
     */
    public function createUser(User $user, ) {
        try {
            // Query para insertar el usuario
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (email, password) VALUES (:email, :password)");
            $stmt->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword()  // Aquí se pasa la contraseña encriptada
            ]);
            return "Usuario creado exitosamente.";
        } catch (\PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}