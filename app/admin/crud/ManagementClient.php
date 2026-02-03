<?php
session_start();
require_once '../../classes/Database.php';

// 1. SEGURIDAD: Solo Admin
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: ../../public/authentication/Login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$mensaje = "";

// --- LÓGICA: CREAR USUARIO (POST) ---
if (isset($_POST['btn_crear'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $passRaw = $_POST['password'];
    $esAdmin = $_POST['es_admin'];

    // Validar si el email ya existe
    $stmtCheck = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
    $stmtCheck->execute([':email' => $email]);

    if ($stmtCheck->rowCount() > 0) {
        $mensaje = "<span style='color:red;'>Error: El email ya está registrado.</span>";
    } else {
        // Hashear contraseña
        $passHash = password_hash($passRaw, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, es_admin) VALUES (:nom, :ape, :mail, :pass, :rol)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([':nom' => $nombre, ':ape' => $apellidos, ':mail' => $email, ':pass' => $passHash, ':rol' => $esAdmin])) {
            $mensaje = "<span style='color:green;'>¡Usuario creado correctamente!</span>";
        } else {
            $mensaje = "<span style='color:red;'>Error al crear usuario.</span>";
        }
    }
}

// --- LÓGICA: BORRAR USUARIO (GET) ---
if (isset($_GET['delete_id'])) {
    $idBorrar = $_GET['delete_id'];
    // Evitar que el admin se borre a sí mismo
    if ($idBorrar != $_SESSION['id_usuario']) {
        $stmtDel = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        $stmtDel->execute([':id' => $idBorrar]);
        header("Location: ManagementClient.php"); // Recargar para limpiar URL
        exit();
    } else {
        $mensaje = "<span style='color:red;'>No puedes borrar tu propia cuenta.</span>";
    }
}

// --- LÓGICA: LEER LISTADO (SELECT) ---
$query = "SELECT * FROM usuarios ORDER BY id_usuario DESC";
$stmtList = $conn->prepare($query);
$stmtList->execute();
$usuarios = $stmtList->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestión de Clientes</title>
        <link rel="stylesheet" href="../assets/styles.css">
        <style>
            body {
                background-color: #2388C7;
                padding: 20px;
                font-family: 'Segoe UI', sans-serif;
            }

            .admin-panel {
                background: white;
                max-width: 1200px;
                margin: 0 auto;
                border-radius: 12px;
                padding: 30px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                display: grid;
                grid-template-columns: 1fr 2fr;
                gap: 40px;
            }

            /* Cabecera Púrpura */
            .panel-header {
                grid-column: 1 / -1;
                border-bottom: 2px solid #f0f0f0;
                margin-bottom: 20px;
                padding-bottom: 10px;
                color: #6f42c1; /* Púrpura */
            }

            /* Estilos Formulario Lateral */
            .form-box {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                height: fit-content;
            }
            .form-title {
                color: #563d7c;
                margin-top: 0;
            }

            /* Estilos Tabla */
            .table-responsive {
                overflow-x: auto;
            }
            .client-table {
                width: 100%;
                border-collapse: collapse;
            }
            .client-table th {
                background-color: #6f42c1;
                color: white;
                padding: 10px;
                text-align: left;
            }
            .client-table td {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }

            /* Etiquetas de Rol */
            .badge {
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 0.8em;
                font-weight: bold;
            }
            .badge-admin {
                background-color: #6f42c1;
                color: white;
            }
            .badge-user {
                background-color: #e9ecef;
                color: #333;
            }

            /* Botones Acción */
            .btn-purple {
                background-color: #6f42c1;
                color: white;
                border: none;
                cursor: pointer;
                width: 100%;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>

        <div class="admin-panel">

            <div class="panel-header">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h1 style="margin:0;">Gestión de Usuarios</h1>
                    <a href="../Dashboard.php" style="text-decoration:none; color:#666; font-weight:bold;">&larr; Volver al Panel</a>
                </div>
                <?php if ($mensaje) echo "<p style='margin-top:10px;'>$mensaje</p>"; ?>
            </div>

            <div class="form-box">
                <h3 class="form-title">Alta Nuevo Usuario</h3>
                <form action="" method="POST">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="auth-input" required>

                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" class="auth-input" required>

                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="auth-input" required>

                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="auth-input" required>

                    <label class="form-label">Rol</label>
                    <select name="es_admin" class="auth-input">
                        <option value="0">Usuario Cliente</option>
                        <option value="1">Administrador</option>
                    </select>

                    <button type="submit" name="btn_crear" class="btn-action btn-purple" style="padding:12px;">Crear Usuario</button>
                </form>
            </div>

            <div class="table-box">
                <h3 class="form-title">Usuarios Registrados (<?php echo count($usuarios); ?>)</h3>
                <div class="table-responsive">
                    <table class="client-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><?php echo $u['id_usuario']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($u['nombre']); ?></strong><br>
                                        <?php echo htmlspecialchars($u['apellidos']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td>
                                        <?php if ($u['es_admin'] == 1): ?>
                                            <span class="badge badge-admin">Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-user">Cliente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="crud-actions">
                                        <a href="UpdateClient.php?id=<?php echo $u['id_usuario']; ?>" class="btn-action btn-edit">Editar</a>

                                        <?php if ($u['id_usuario'] != $_SESSION['id_usuario']): ?>
                                            <a href="ManagementClient.php?delete_id=<?php echo $u['id_usuario']; ?>" 
                                               class="btn-action btn-delete" 
                                               onclick="return confirm('¿Eliminar a este usuario? Esta acción es irreversible.');">
                                                Borrar
                                            </a>
                                        <?php else: ?>
                                            <small style="color:#999;">(Tú)</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>