<?php
session_start();
require_once '../../classes/Database.php';

if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../../public/authentication/Login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$id_viaje = $_GET['id'] ?? null;

if (!$id_viaje) {
    header("Location: Read.php");
    exit();
}

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM viajes WHERE id_viaje = ?");
$stmt->execute([$id_viaje]);
$viaje = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreImagen = $viaje['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $target_dir = "../../assets/list/";
        $nombreImagen = time() . "_" . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_dir . $nombreImagen);
    }

    $sql = "UPDATE viajes SET titulo=?, descripcion=?, fecha_inicio=?, fecha_fin=?, precio=?, destacado=?, tipo_viaje=?, plazas=?, imagen=? WHERE id_viaje=?";
    $stmtUpdate = $conn->prepare($sql);

    if ($stmtUpdate->execute([
                $_POST['titulo'], $_POST['descripcion'], $_POST['fecha_inicio'], $_POST['fecha_fin'],
                $_POST['precio'], $_POST['destacado'], $_POST['tipo_viaje'], $_POST['plazas'],
                $nombreImagen, $id_viaje
            ])) {
        header("Location: Read.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Viaje</title>
        <style>
            body {
                background-color: #2388C7;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: 'Segoe UI', sans-serif;
                margin:0;
            }
            .dashboard-wrapper {
                background: white;
                padding: 40px;
                border-radius: 12px;
                max-width: 800px;
                width: 90%;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }
            .auth-input {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            .form-label {
                display: block;
                margin-bottom: 5px;
                color: #333;
                font-weight: bold;
                font-size: 0.9em;
            }
            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            .full-width {
                grid-column: span 2;
            }
            .btn-save {
                background-color: #ffc107;
                color: #333;
                padding: 12px 20px;
                border: none;
                border-radius: 50px;
                cursor: pointer;
                font-weight: bold;
            }
            .btn-cancel {
                background-color: #6c757d;
                color: white;
                padding: 12px 20px;
                text-decoration: none;
                border-radius: 50px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="dashboard-wrapper">
            <h2 style="color: #0B2447; border-bottom: 1px solid #eee; padding-bottom: 15px;">Editar Viaje #<?php echo $viaje['id_viaje']; ?></h2>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div>
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="auth-input" value="<?php echo htmlspecialchars($viaje['titulo']); ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Tipo</label>
                        <input type="text" name="tipo_viaje" class="auth-input" value="<?php echo htmlspecialchars($viaje['tipo_viaje']); ?>">
                    </div>
                    <div class="full-width">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="auth-input" style="height: 100px;"><?php echo htmlspecialchars($viaje['descripcion']); ?></textarea>
                    </div>
                    <div>
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="auth-input" value="<?php echo $viaje['fecha_inicio']; ?>">
                    </div>
                    <div>
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="auth-input" value="<?php echo $viaje['fecha_fin']; ?>">
                    </div>
                    <div>
                        <label class="form-label">Precio</label>
                        <input type="number" step="0.01" name="precio" class="auth-input" value="<?php echo $viaje['precio']; ?>">
                    </div>
                    <div>
                        <label class="form-label">Plazas</label>
                        <input type="number" name="plazas" class="auth-input" value="<?php echo $viaje['plazas']; ?>">
                    </div>
                    <div>
                        <label class="form-label">Destacado</label>
                        <select name="destacado" class="auth-input">
                            <option value="1" <?php if ($viaje['destacado']) echo 'selected'; ?>>Sí</option>
                            <option value="0" <?php if (!$viaje['destacado']) echo 'selected'; ?>>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Cambiar Imagen (Opcional)</label>
                        <input type="file" name="imagen" class="auth-input">
                    </div>
                </div>
                <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="Read.php" class="btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-save">Actualizar Datos</button>
                </div>
            </form>
        </div>
    </body>
</html>