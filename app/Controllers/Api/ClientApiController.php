<?php
namespace App\Config;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../src/Config/DatabaseConnection.php';
require_once __DIR__ . '/../src/Config/JwtHandler.php';
require_once __DIR__ . '/../src/Config/vendor/autoload.php';

class ClientController {
    private $pdo;
    private $jwtHandler;
    private $db;

    public function __construct($db) {
        $this->pdo = $db->connect();  // Usamos la conexión de DB
        $this->jwtHandler = new JwtHandler();
    }

    public function validateJwt() {
        $headers = getallheaders();
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
        
        if (!$jwt) {
            echo json_encode(["message" => "Token no proporcionado"]);
            http_response_code(401);  // Unauthorized
            exit();
        }

        $decoded = $this->jwtHandler->validate_jwt($jwt);

        if (!$decoded) {
            echo json_encode(["message" => "Token inválido o expirado"]);
            http_response_code(401);  // Unauthorized
            exit();
        }

        return $decoded;
    }

    public function getClients() {
        // Corregir uso de PDO sin espacio de nombres
        $stmt = $this->pdo->query("SELECT * FROM clientes");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);  // Usar PDO de forma global
    }

    public function createClient($name, $lastname, $email, $tel, $address) {
        $stmt = $this->pdo->prepare("INSERT INTO clientes (name, lastname, email, tel, address) VALUES (:name, :lastname, :email, :tel, :address)");
        $stmt->execute(['name' => $name, 'lastname' => $lastname, 'email' => $email, 'tel' => $tel, 'address' => $address]);
        return json_encode(["message" => "Cliente creado"]);
    }

    public function updateClient($id, $name, $lastname, $email, $tel, $address) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET name = :name, 
                                                      lastname = :lastname, 
                                                      email = :email, 
                                                      tel = :tel, 
                                                      address = :address 
                                                      WHERE id = :id");
        $stmt->execute(['name' => $name, 'lastname' => $lastname, 'email' => $email, 'tel' => $tel, 'address' => $address, 'id' => $id]);
        return json_encode(["message" => "Cliente actualizado"]);
    }

    public function deleteClient($id) {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return json_encode(["message" => "Cliente eliminado"]);
    }

    public function handleRequest($action) {
        // Validar el JWT antes de cualquier acción
        $this->validateJwt();

        switch ($action) {
            case 'get':
                echo json_encode(["clients" => $this->getClients()]);
                break;
            case 'create':
                $data = json_decode(file_get_contents("php://input"));
                echo $this->createClient($data->name, $data->lastname, $data->email, $data->tel, $data->address);
                break;
            case 'update':
                $data = json_decode(file_get_contents("php://input"));
                echo $this->updateClient($data->id, $data->name, $data->lastname, $data->email, $data->tel, $data->address);
                break;
            case 'delete':
                $data = json_decode(file_get_contents("php://input"));
                echo $this->deleteClient($data->id);
                break;
            default:
                echo json_encode(["message" => "Acción no válida"]);
                http_response_code(400);  // Bad Request
        }
    }
}
