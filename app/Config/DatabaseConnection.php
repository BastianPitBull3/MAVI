<?php

namespace App\Config;

use PDO;
require_once __DIR__ . "/../env/Env.php";

class DatabaseConnection {
    private $dsn;
    private $host;
    private $db;
    private $user;
    private $password;
    private $pdo;

    /**
     * Inicializa los datos de conexión.
     */
    public function __construct() {
        $this->dsn = new \App\env\Env();
        $this->host = $this->dsn->params()['host'];
        $this->db = $this->dsn->params()['db'];
        $this->user = $this->dsn->params()['user'];
        $this->password = $this->dsn->params()['password'];
    }

    /**
     * Establece y retorna la conexión con la base de datos.
     */
    public function connect() {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db}";
                $this->pdo = new PDO($dsn, $this->user, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                json_encode(['message' => 'Conexión exitosa']);
            } catch (\PDOException $e) {
                die("Conexión fallida: " . $e->getMessage());
            }
        return $this->pdo;
    }

    /**
     * Cierra la conexión activa con la base de datos.
     */
    public function disconnect() {
        $this->pdo = null;
    }
}
