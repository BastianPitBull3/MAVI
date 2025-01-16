<?php
class User {
    private $email;
    private $password;

    /**
     * Constructor para inicializar el modelo de usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña en texto plano del usuario.
     */
    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Obtiene el correo electrónico del usuario.
     * @return string Correo electrónico.
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Obtiene la contraseña encriptada del usuario.
     * @return string Contraseña encriptada.
     */
    public function getPassword() {
        return $this->password;
    }
}
