<?php
session_start();
require_once '../../classes/Database.php';

// Seguridad
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../../public/authentication/Login.php");
    exit();
}

if (isset($_GET['id'])) {
    $db = new Database();
    $conn = $db->getConnection();
    
    $id = $_GET['id'];
    
    // Consulta para borrar
    $query = "DELETE FROM viajes WHERE id_viaje = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: Read.php");
    } else {
        echo "Error al eliminar el viaje.";
    }
} else {
    header("Location: Read.php");
}
?>