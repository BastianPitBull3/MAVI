<?php
namespace App\Config;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../src/Config/DatabaseConnection.php';
require_once __DIR__ . '/../src/Config/JwtHandler.php';
require_once __DIR__ . '/../src/Config/vendor/autoload.php';

use App\Config\JwtHandler;

class AuthController {
    private $pdo;
    private $jwtHandler;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->jwtHandler = new JwtHandler();  // Instanciamos JwtHandler
    }

    /**
     * Autentica un usuario y genera un token JWT.
     * @param string $email
     * @param string $password
     * @return string JSON con el token o mensaje de error.
     */
    public function login($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Usamos el JwtHandler para generar el token
                $jwt = $this->jwtHandler->generate_jwt([
                    "id" => $user['id'],
                    "email" => $user['email']
                ]);
                return json_encode(["token" => $jwt]);
            } else {
                http_response_code(401);
                return json_encode(["message" => "Credenciales inválidas"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(["message" => "Error en el servidor", "error" => $e->getMessage()]);
        }
    }
}

// Procesar la solicitud
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    // Crear una instancia del controlador de autenticación
    $authController = new AuthController($pdo);
    echo $authController->login($data->email, $data->password);
} else {
    echo json_encode(["message" => "Por favor ingresa usuario y contraseña"]);
}
