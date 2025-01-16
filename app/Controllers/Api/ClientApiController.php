<?php
namespace App\Controllers;

use App\Models\Client;

class ClientApiController {
    private $cliente;

    public function __construct($db) {
        $this->cliente = new Client($db->connect());
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'get':
                echo json_encode(["clients" => $this->cliente->obtenerTodos()]);
                break;

            case 'create':
                $data = json_decode(file_get_contents("php://input"));
                $id = $this->cliente->crear($data['name'], $data['lastname'], $data['email'], $data['tel'], $data['address']);
                echo json_encode(["message" => "Cliente creado", "id" => $id]);
                break;

            case 'update':
                $data = json_decode(file_get_contents("php://input"));
                $this->cliente->actualizar($data['id'], $data['name'], $data['lastname'], $data['email'], $data['tel'], $data['address']);
                echo json_encode(["message" => "Cliente actualizado"]);
                break;

            case 'delete':
                $data = json_decode(file_get_contents("php://input"));
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
