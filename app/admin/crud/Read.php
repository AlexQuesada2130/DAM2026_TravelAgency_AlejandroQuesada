<?php
session_start();
require_once '../../classes/Database.php';

if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../../public/authentication/Login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$query = "SELECT * FROM viajes ORDER BY id_viaje DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Listado de Viajes</title>
        <link rel="stylesheet" href="../../assets/styles.css"> 
        <style>
            body {
                background-color: #2388C7;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                padding: 40px;
                font-family: 'Segoe UI', sans-serif;
            }
            .dashboard-wrapper {
                background: white;
                width: 100%;
                max-width: 1000px;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }
        </style>
    </head>
    <body>
        <div class="dashboard-wrapper">
            <div class="dash-head" style="display:flex; justify-content:space-between; align-items:center;">
                <h1>Listado de Viajes</h1>
                <a href="Create.php" class="btn-logout" style="background:#28a745;">+ Nuevo Viaje</a>
            </div>

            <div style="overflow-x: auto;">
                <table class="crud-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Destino</th>
                            <th>Fechas</th>
                            <th>Precio</th>
                            <th>Plazas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($viajes as $viaje): ?>
                            <tr>
                                <td><?php echo $viaje['id_viaje']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($viaje['titulo']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($viaje['tipo_viaje']); ?></small>
                                </td>
                                <td><?php echo $viaje['fecha_inicio']; ?> / <?php echo $viaje['fecha_fin']; ?></td>
                                <td><?php echo $viaje['precio']; ?>€</td>
                                <td><?php echo $viaje['plazas']; ?></td>
                                <td class="crud-actions">
                                    <a href="Update.php?id=<?php echo $viaje['id_viaje']; ?>" class="btn-action btn-edit">Editar</a>
                                    <a href="Delete.php?id=<?php echo $viaje['id_viaje']; ?>" class="btn-action btn-delete" onclick="return confirm('¿Seguro que quieres borrar este viaje?');">Borrar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px;">
                <a href="../Dashboard.php" style="color: #2388C7; text-decoration: none;">&larr; Volver al Panel</a>
            </div>
        </div>
    </body>
</html>