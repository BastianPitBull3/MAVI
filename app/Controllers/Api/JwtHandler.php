<?php

require_once __DIR__ . '/../../../vendor/autoload.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtHandler {
    private $key;
    private $issuer;
    private $audience;
    private $alg;

    public function __construct($key = null, $issuer = "localhost", $audience = "usuarios", $alg = "HS256") {
        $this->key = $key ?: getenv('JWT_SECRET') ?: 'default_secret_key';
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->alg = $alg;
    }

    public function generate_jwt($data) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 36000; // 10 horas
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
            $decoded = JWT::decode($jwt, $this->key, $this->alg);
            return (object) $decoded;
        } catch (ExpiredException $e) {
            // El token ha expirado
            return null;
        } catch (SignatureInvalidException $e) {
            // La firma no es válida
            return null;
        } catch (\Exception $e) {
            // Otro error
            return null;
        }
    }
}

