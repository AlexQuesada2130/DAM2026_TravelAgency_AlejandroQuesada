<?php
session_start();

require_once '../../classes/Database.php';
require_once '../../classes/User.php';

$mensaje = "";
$emailValue = ""; 

// --- LÓGICA (CONTROLADOR) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $usuarioObj = new Usuario(); 

    if (isset($_POST['btn_login'])) {
        $email = trim($_POST['login_email']);
        $pass = trim($_POST['login_pass']);

        $user = $usuarioObj->login($email, $pass);

        if ($user) {
            // Login correcto:
            if ($user['es_admin'] == 1) {
                header("Location: ../../admin/Dashboard.php"); 
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            $mensaje = "Usuario o contraseña incorrectos.";
            $emailValue = $email;
        }
    }

    if (isset($_POST['btn_registro'])) {
        $nombre = trim($_POST['reg_nombre']);
        $apellidos = trim($_POST['reg_apellidos']);
        $email = trim($_POST['reg_email']);
        $pass = trim($_POST['reg_pass']);

        if ($usuarioObj->registrar($nombre, $apellidos, $email, $pass)) {
            $mensaje = "¡Registro completado inicia sesión!";
        } else {
            $mensaje = "Este correo ya fue registrado";
        }
    }
}

// Si no hay POST, o si hubo error, cargamos la vista.
require_once '../../screens/auth/LoginView.php';
?>