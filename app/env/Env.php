<?php

namespace App\env;

class Env
{
    private $host;
    private $db;
    private $user;
    private $password;

    public function __construct() {
        $this->host = "localhost";
        $this->db = "mavicrudapi";
        $this->user = "root";
        $this->password = "";
    }

    public function params(){
        return [
            'host' => $this->host,
            'db' => $this->db,
            'user' => $this->user,
            'password' => $this->password
        ];
    }
}