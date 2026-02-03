<?php
session_start();

// Validar acceso: solo administradores
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../public/authentication/Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="../assets/styles.css"> 
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-wrapper {
            margin-top: 40px;
            background-color: #ffffff;
            width: 90%;
            max-width: 800px;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            text-align: center;
        }

        .dash-head {
            margin-bottom: 35px;
            border-bottom: 1px solid #e1e1e1;
            padding-bottom: 20px;
        }

        .dash-head h1 {
            color: #0B2447;
            margin: 0 0 5px 0;
            font-size: 2rem;
        }

        .dash-head p {
            color: #777;
            font-size: 1rem;
            margin: 0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 10px;
            margin-bottom: 40px;
        }

        .action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            min-height: 80px;
        }

        .theme-create { background: #28a745; }
        .theme-read   { background: #17a2b8; }
        .theme-update { background: #ffc107; }
        .theme-delete { background: #dc3545; }

        .footer-nav {
            border-top: 1px solid;
            padding-top: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper"> 
        <div class="dash-head">
            <h1>Panel administrador</h1>
        </div>
        <div class="actions-grid">
            
            <a href="crud/Create.php" class="action-card theme-create">
                Nuevo Viaje
            </a>
            <a href="crud/Read.php" class="action-card theme-read">
                Listado Viajes
            </a>
            <a href="crud/Update.php" class="action-card theme-update">
                Modificar Viajes
            </a>
            <a href="crud/Delete.php" class="action-card theme-delete">
                Eliminar Viajes
            </a>
        </div>
        <div class="footer-nav">
            <a href="../public/authentication/Logout.php">Cerrar Sesión</a>
            <a href="../public/index.php">Volver a la web pública</a>
        </div>
    </div>
</body>
</html>