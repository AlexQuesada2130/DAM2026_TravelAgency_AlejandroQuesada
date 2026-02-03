<?php
require_once 'Database.php'; // Asumo que tienes tu clase de conexión aquí

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para Registrar
    public function registrar($nombre, $apellidos, $email, $password) {
        $query = "INSERT INTO " . $this->table . " (nombre, apellidos, email, password, es_admin) VALUES (:nombre, :apellidos, :email, :password, 0)";
        
        $stmt = $this->conn->prepare($query);

        // Limpieza básica y Hashing de contraseña (Seguridad)
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para Login
    public function login($email, $password) {
        $query = "SELECT id_usuario, nombre, password, es_admin FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar contraseña hasheada
            if (password_verify($password, $row['password'])) {
                // Iniciar sesión
                session_start();
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['es_admin'] = $row['es_admin'];
                return $row; // Devolvemos los datos para redireccionar en el controlador
            }
        }
        return false;
    }
}
?>