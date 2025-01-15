<?php

namespace App\Config;

use PDO;  // Asegúrate de importar la clase PDO correctamente

class DatabaseConnection {
    private $host;
    private $db;
    private $user;
    private $password;
    private $pdo;

    /**
     * Constructor para inicializar los datos de conexión.
     * @param string $host Host de la base de datos.
     * @param string $db Nombre de la base de datos.
     * @param string $user Usuario de la base de datos.
     * @param string $password Contraseña del usuario de la base de datos.
     */
    public function __construct($host, $db, $user, $password) {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Establece la conexión con la base de datos.
     * @return PDO Objeto PDO para interactuar con la base de datos.
     * @throws PDOException Si ocurre un error al conectar.
     */
    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db}";
                $this->pdo = new PDO($dsn, $this->user, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                die("Conexión fallida: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    /**
     * Cierra la conexión a la base de datos.
     */
    public function disconnect() {
        $this->pdo = null;
    }
}

// Uso de la clase DatabaseConnection
$host = "localhost";
$db = "mavicrudapi";
$user = "root";
$password = "";

$dbConnection = new DatabaseConnection($host, $db, $user, $password);

// Establecer la conexión
$pdo = $dbConnection->connect();

// Cerrar la conexión (opcional)
$dbConnection->disconnect();
