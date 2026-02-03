<?php
class Database {
    // Parámetros de configuración (idealmente deberían estar en un config.php, pero aquí funciona bien)
    private $host = 'localhost';
    private $db_name = 'agencia_viajes'; // Asegúrate que coincide con tu SQL
    private $username = 'root';
    private $password = '';
    public $conn;

    /**
     * Obtiene la conexión a la Base de Datos
     * @return PDO|null Retorna el objeto de conexión o null si falla
     */
    public function getConnection() {
        $this->conn = null;

        try {
            // Data Source Name
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configuración de atributos para reporte de errores y emulación
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>