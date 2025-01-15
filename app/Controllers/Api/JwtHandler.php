<?php

namespace App\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        $expirationTime = $issuedAt + 3600; // 1 hora
        $payload = [
            "iss" => $this->issuer,
            "aud" => $this->audience,
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "data" => $data
        ];

        return JWT::encode($payload, $this->key, $this->alg);
    }

    public function validate_jwt($jwt) {
        try {
            $jwt = JWT::decode($jwt, new Key($this->key, $this->alg));
            echo $jwt;
            return $jwt; /* JWT::decode($jwt, new Key($this->key, $this->alg)); */
        } catch (\Exception $e) {
            return null;
        }
    }
}
