<?php
session_start();
require_once '../../classes/Database.php';

if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../../public/authentication/Login.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $nombreImagen = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $target_dir = "../../assets/list/"; // Asegúrate de crear esta carpeta
        $nombreImagen = time() . "_" . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_dir . $nombreImagen);
    }

    $sql = "INSERT INTO viajes (titulo, descripcion, fecha_inicio, fecha_fin, precio, destacado, tipo_viaje, plazas, imagen) 
            VALUES (:titulo, :desc, :finicio, :ffin, :precio, :dest, :tipo, :plazas, :img)";

    $stmt = $conn->prepare($sql);

    $datos = [
        ':titulo' => $_POST['titulo'],
        ':desc' => $_POST['descripcion'],
        ':finicio' => $_POST['fecha_inicio'],
        ':ffin' => $_POST['fecha_fin'],
        ':precio' => $_POST['precio'],
        ':dest' => $_POST['destacado'],
        ':tipo' => $_POST['tipo_viaje'],
        ':plazas' => $_POST['plazas'],
        ':img' => $nombreImagen
    ];

    if ($stmt->execute($datos)) {
        header("Location: Read.php");
        exit();
    } else {
        $mensaje = "Error al crear el viaje.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Nuevo Viaje</title>
        <link rel="stylesheet" href="../../assets/styles.css">
        <style>
            body {
                background-color: #2388C7;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: 'Segoe UI', sans-serif;
            }
            .dashboard-wrapper {
                background: white;
                padding: 40px;
                border-radius: 12px;
                max-width: 800px;
                width: 90%;
            }
        </style>
    </head>
    <body>
        <div class="dashboard-wrapper">
            <h2 style="color: #0B2447; margin-bottom: 20px;">Alta de Nuevo Viaje</h2>

<?php if ($mensaje): ?><p style="color:red;"><?php echo $mensaje; ?></p><?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div>
                        <label class="form-label">Título / Destino</label>
                        <input type="text" name="titulo" class="auth-input" required>
                    </div>
                    <div>
                        <label class="form-label">Tipo de Viaje</label>
                        <input type="text" name="tipo_viaje" class="auth-input" placeholder="Ej: Playa, Montaña...">
                    </div>

                    <div class="full-width">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="auth-input"></textarea>
                    </div>

                    <div>
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="auth-input" required>
                    </div>
                    <div>
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="auth-input" required>
                    </div>

                    <div>
                        <label class="form-label">Precio (€)</label>
                        <input type="number" step="0.01" name="precio" class="auth-input" required>
                    </div>
                    <div>
                        <label class="form-label">Plazas Disponibles</label>
                        <input type="number" name="plazas" class="auth-input" required>
                    </div>

                    <div>
                        <label class="form-label">Destacado?</label>
                        <select name="destacado" class="auth-input">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Imagen Principal</label>
                        <input type="file" name="imagen" class="auth-input" style="padding: 9px;">
                    </div>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-logout" style="background-color: #28a745; border:none; cursor:pointer;">Guardar Viaje</button>
                    <a href="Read.php" class="btn-logout" style="text-align:center;">Cancelar</a>
                </div>
            </form>
        </div>
    </body>
</html>