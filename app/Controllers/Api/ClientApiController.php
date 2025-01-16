<?php 
namespace App\Controllers\Api;

require_once __DIR__ ."/../../Models/Client.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ClientApiController {

    private $client;

    public function __construct() {
        $this->client = new \App\Models\Client();
    }

    public function handleRequest($action) {
        header('Content-Type: application/json');
        
        try {
            switch ($action) {
                case 'get':
                    try {
                        $clients = $this->client->getAll();
                
                        if ($clients) {
                            http_response_code(200);
                            echo json_encode(["success" => true, "clients" => $clients, "message" => "Clientes obtenidos con éxito"]);
                        } else {
                            http_response_code(404);
                            echo json_encode(["succes" => false, "message" => "No se encontraron clientes"]);
                        }
                    } catch (\Exception $e) {
                        http_response_code(500);
                        echo json_encode(["success" => false, "message" => "Error al obtener los clientes", "error" => $e->getMessage()]);
                    }
                    break;
                case 'create':
                    $data = json_decode(file_get_contents("php://input"), true);

                    if (isset($data['name'], $data['lastname'], $data['email'], $data['address'])) {
                        try {
                            $newClient = $this->client->create(
                                $data['name'], 
                                $data['lastname'], 
                                $data['email'], 
                                $data['address']
                            );
                            http_response_code(200);
                            echo json_encode(["success" => true, "client" => $newClient, "message" => "Cliente creado correctamente"]);
                        } catch (\Exception $e) {
                            http_response_code(500);
                            echo json_encode(["success" => false, "message" => "Error al crear el cliente", "error" => $e->getMessage()]);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(["success" => false, "message" => "Datos incompletos. Asegúrese de enviar 'name', 'lastname', 'email' y 'address'."]);
                    }
                    break;
                case 'update':
                    try {
                        $data = json_decode(file_get_contents("php://input"), true);
                        
                        if (empty($data['id']) || empty($data['name']) || empty($data['lastname']) || empty($data['email']) || empty($data['address'])) {
                            http_response_code(400);
                            echo json_encode(["succes" => false, "message" => "Faltan datos necesarios para actualizar el cliente"]);
                            break;
                        }
                        $updateSuccess = $this->client->update(
                            $data['id'], 
                            $data['name'], 
                            $data['lastname'], 
                            $data['email'], 
                            $data['address']
                        );
                        if ($updateSuccess) {
                            http_response_code(200);
                            echo json_encode(["success" => true, "message" => "Cliente actualizado correctamente"]);
                        } else {
                            http_response_code(404);
                            echo json_encode(["succes" => false, "message" => "Cliente no encontrado"]);
                        }
                    } catch (\Exception $e) {
                        http_response_code(500);
                        echo json_encode(["succes" => false, "message" => "Error al actualizar el cliente", "error" => $e->getMessage()]);
                    }
                    break;
                    case 'delete':
                        try {
                            $data = json_decode(file_get_contents("php://input"), true);
                            if (empty($data['id'])) {
                                http_response_code(400);
                                echo json_encode(["success" => false, "message" => "Falta el ID del cliente para eliminar"]);
                                break;
                            }
                            $deleteSuccess = $this->client->delete($data['id']);
                            if ($deleteSuccess) {
                                http_response_code(200);
                                echo json_encode(["success" => true, "message" => "Cliente eliminado correctamente"]);
                            } else {
                                http_response_code(404);
                                echo json_encode(["success" => false, "message" => "Cliente no encontrado"]);
                            }
                    
                        } catch (\Exception $e) {
                            http_response_code(500);
                            echo json_encode(["success" => false, "message" => "Error al eliminar el cliente", "error" => $e->getMessage()]);
                        }
                        break;
                default:
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "Acción no válida"]);
                    break;
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false, "message" => "Error interno del servidor.", "error" => $e->getMessage()
            ]);
        }
    }
}

$ClientApiController = new ClientApiController();
$ClientApiController->handleRequest($_GET['action']);
