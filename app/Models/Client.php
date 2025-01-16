<?php

namespace App\Models;

require_once __DIR__ ."/../Config/DatabaseConnection.php";

class Client {

    private $dbConn;
    private $pdo;

    public function __construct() {
        $this->dbConn = new \App\Config\DatabaseConnection();
        $this->pdo = $this->dbConn->connect();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM clientes");
        $result = $stmt->fetchAll($this->pdo::FETCH_ASSOC);
        $this->dbConn->disconnect();
        return $result;
    }

    public function create($name, $lastname, $email, $address) {
        $stmt = $this->pdo->prepare("
            INSERT INTO clientes (name, lastname, email, address) 
            VALUES (:name, :lastname, :email, :address)
        ");
        $stmt->execute([
            'name' => $name,
            'lastname' => $lastname,
            'email' => $email,
            'address' => $address
        ]);
        $result = $this->pdo->lastInsertId();
        $this->dbConn->disconnect();
        return $result;
    }

    public function update($id, $name, $lastname, $email, $address) {
        $stmt = $this->pdo->prepare("
            UPDATE clientes 
            SET name = :name, lastname = :lastname, email = :email, address = :address 
            WHERE id = :id
        ");
        $result = $stmt->execute([
            'name' => $name,
            'lastname' => $lastname,
            'email' => $email,
            'address' => $address,
            'id' => $id
        ]);
        $this->dbConn->disconnect();
        return $result;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id = :id");
        $result = $stmt->execute(['id' => $id]);
        $this->dbConn->disconnect();
        return $result;
    }
}
