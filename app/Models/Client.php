<?php
namespace App\Models;

class Client {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM clientes");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function crear($name, $lastname, $email, $tel, $address) {
        $stmt = $this->pdo->prepare("INSERT INTO clientes (name, lastname, email, tel, address) VALUES (:name, :lastname, :email, :tel, :address)");
        $stmt->execute(compact('name', 'lastname', 'email', 'tel', 'address'));
        return $this->pdo->lastInsertId();
    }

    public function actualizar($id, $name, $lastname, $email, $tel, $address) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET name = :name, lastname = :lastname, email = :email, tel = :tel, address = :address WHERE id = :id");
        return $stmt->execute(compact('id', 'name', 'lastname', 'email', 'tel', 'address'));
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
