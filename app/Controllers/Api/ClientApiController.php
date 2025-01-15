<?php
namespace App\Controllers;

use App\Models\Client;
use JwtHandler;

class ClientApiController {
    private $cliente;
    private $jwtHandler;

    public function __construct($db) {
        $this->cliente = new Client($db->connect());
        $this->jwtHandler = new JwtHandler();
    }

    private function validateJwt() {
        $headers = getallheaders();
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

        if (!$jwt || !$this->jwtHandler->validate_jwt($jwt)) {
            http_response_code(401);
            echo json_encode(["message" => "Token inválido o expirado"]);
            exit();
        }
    }

    public function handleRequest($action) {
        $this->validateJwt();

        switch ($action) {
            case 'get':
                echo json_encode(["clients" => $this->cliente->obtenerTodos()]);
                break;

            case 'create':
                $data = json_decode(file_get_contents("php://input"), true);
                $id = $this->cliente->crear($data['name'], $data['lastname'], $data['email'], $data['tel'], $data['address']);
                echo json_encode(["message" => "Cliente creado", "id" => $id]);
                break;

            case 'update':
                $data = json_decode(file_get_contents("php://input"), true);
                $this->cliente->actualizar($data['id'], $data['name'], $data['lastname'], $data['email'], $data['tel'], $data['address']);
                echo json_encode(["message" => "Cliente actualizado"]);
                break;

            case 'delete':
                $data = json_decode(file_get_contents("php://input"), true);
                $this->cliente->eliminar($data['id']);
                echo json_encode(["message" => "Cliente eliminado"]);
                break;

            default:
                http_response_code(400);
                echo json_encode(["message" => "Acción no válida"]);
                break;
        }
    }
}
