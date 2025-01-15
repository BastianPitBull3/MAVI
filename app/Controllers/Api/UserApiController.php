<?php

require_once __DIR__ . '../../../config/DatabaseConnection.php';

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
                'password' => $user->getPassword()
            ]);
            return "Usuario creado exitosamente.";
        } catch (\PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

// Crear un modelo de usuario
$email = "admin2@example.com";
$password = "pw1234";
$dbuser = "root";

$db = new \DataBaseConnection($host, $db, $dbuser, $password);

// Crear instancia de UserCreator con la conexión PDO
$userCreator = new UserApiController($db);

// Crear usuario en la base de datos
$result = $userApiController->createUser();
echo $result;
