<?php
// Clase para gestionar la creación de usuarios
class UserCreator {
    private $pdo;

    /**
     * Constructor para inicializar la conexión a la base de datos.
     * @param PDO $pdo Conexión PDO a la base de datos.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario.
     * @return string Mensaje de éxito o error.
     */
    public function createUser($email, $password) {
        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Query para insertar el usuario
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (email, password) VALUES (:email, :password)");
            $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
            return "Usuario creado exitosamente.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

// Uso de la clase UserCreator
require_once './config/db.php'; // Conexión a la base de datos

// Crear instancia de UserCreator con la conexión PDO
$userCreator = new UserCreator($pdo);

// Datos del usuario
$email = "admin@example.com";
$password = "pw1234";

// Crear usuario
$result = $userCreator->createUser($email, $password);
echo $result;

