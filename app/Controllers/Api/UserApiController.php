<?php

require_once __DIR__ . '/../../../config/DatabaseConnection.php';
require_once __DIR__ . '/../../Models/User.php';

class UserApiController {
    private $pdo;

    /**
     * Constructor para inicializar la conexión a la base de datos.
     * @param $db Conexión PDO a la base de datos.
     */
    public function __construct(\DatabaseConnection $pdo) {
        $this->db = $pdo;
        $this->pdo = $pdo->connect();
    }

    /**
     * Crea un nuevo usuario en la base de datos utilizando un modelo de usuario.
     * @param \User $user Modelo de usuario.
     * @return string Mensaje de éxito o error.
     */
    public function createUser(\User $user) {
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

// Parámetros de la base de datos
$host = "localhost";
$db = "mavicrudapi";
$dbuser = "root";
$password = "";

// Crear la conexión a la base de datos
$conn = new \DatabaseConnection($host, $db, $dbuser, $password);

// Crear la instancia del controlador
$userController = new UserApiController($conn);

// Crear un nuevo usuario
$email = 'admin@example.com';  // Reemplaza con el email real
$password = 'pw1234';  // Reemplaza con la contraseña real
$user = new \User($email, $password);  // Crear el objeto User, la contraseña ya se encripta aquí

// Llamar al método para crear el usuario en la base de datos
$result = $userController->createUser($user);

// Imprimir el resultado
echo $result;