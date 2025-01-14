<?php
namespace App\Config;

require_once __DIR__ . '/vendor/autoload.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Exception;

class JwtHandler {
    private $key;
    private $issuer;
    private $audience;
    private $alg;

    public function __construct($key = "secret_key", $issuer = "localhost", $audience = "usuarios", $alg = "HS256") {
        $this->key = $key;
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->alg = $alg;
    }

    public function generate_jwt($data) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 36000;
        $payload = [
            "iss" => $this->issuer,
            "aud" => $this->audience,
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "data" => $data
        ];

        return JWT::encode($payload, $this->key, $this->alg);
    }

    /**
     * @param string $jwt
     * @return object|null
     */
    public function validate_jwt($jwt) {
        try {
            $decoded = JWT::decode($jwt, $this->key, $this->alg); // El tercer parámetro es un array de algoritmos permitidos
            return (object) $decoded;
        } catch (Exception $e) {  // Usar JWTException en lugar de Exception
            return null;
        }
    }
}
