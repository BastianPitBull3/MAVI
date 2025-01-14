<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Carga el autoloader de Composer
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtHandler {
    private $key;
    private $issuer;
    private $audience;

    /**
     * Constructor para inicializar las propiedades clave, emisor y audiencia.
     * @param string $key Clave secreta para firmar el JWT.
     * @param string $issuer Emisor del JWT.
     * @param string $audience Audiencia del JWT.
     */
    public function __construct($key = "secret_key", $issuer = "localhost", $audience = "usuarios") {
        $this->key = $key;
        $this->issuer = $issuer;
        $this->audience = $audience;
    }

    /**
     * Genera un JWT con los datos proporcionados.
     * @param array $data Datos que se incluirán en el payload del JWT.
     * @return string Token JWT codificado.
     */
    public function generate_jwt($data) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 36000; // Expira en 10 horas
        $payload = [
            "iss" => $this->issuer,
            "aud" => $this->audience,
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "data" => $data
        ];
        return JWT::encode($payload, $this->key, "HS256");
    }

    /**
     * Valida un JWT y devuelve los datos decodificados si es válido.
     * @param string $jwt Token JWT a validar.
     * @return object|null Datos decodificados del JWT o null si es inválido.
     */
    public function validate_jwt($jwt) {
        try {
            $keyObj = new Key($this->key, "HS256");
            $decoded = JWT::decode($jwt, $keyObj);
            return (object) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
