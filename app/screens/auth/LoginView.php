<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Agencia de Viajes</title>
    <link rel="stylesheet" href="../../assets/styles.css"> 
    <style>
        body.auth-page { 
            margin: 0;
            padding: 0;
            height: 100vh; 
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;    
            justify-content: center; 
            background-color: #2388C7;
            position: relative;      
        }

        .auth-split-container { 
            display: flex; 
            gap: 40px;
            flex-wrap: wrap; 
            justify-content: center; 
            align-items: flex-start;
            width: 100%; 
            max-width: 1000px; 
            padding: 20px;
            box-sizing: border-box;
            z-index: 2;
        }

        .auth-col { 
            flex: 1; 
            min-width: 320px;
            max-width: 450px;
        }

        .btn-volver {
            position: absolute;
            top: 40px;
            left: 40px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            z-index: 3;
        }

        .msg-alerta { 
            background-color: #ff6b35; 
            padding: 12px 20px; 
            margin-bottom: 25px; 
            border-radius: 50px;
            text-align: center;
            max-width: 80%;
        }
    </style>
</head>
<body class="auth-page">

    <a href="../index.php" class="btn-volver">
        <- Volver al Inicio
    </a>

    <?php if(!empty($mensaje)): ?>
        <div class="msg-alerta"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <div class="auth-split-container">
        
        <div class="auth-col">
            <div class="auth-card">
                <h2>Iniciar Sesión</h2>
                <form action="" method="POST" class="auth-form">
                    <input type="email" name="login_email" class="auth-input" placeholder="Email" value="<?php echo isset($emailValue) ? htmlspecialchars($emailValue) : ''; ?>" required>
                    <input type="password" name="login_pass" class="auth-input" placeholder="Contraseña" required>
                    <button type="submit" name="btn_login" class="auth-btn" style="background-color: #0B2447;">ENTRAR</button>
                </form>
            </div>
        </div>

        <div class="auth-col">
            <div class="auth-card">
                <h2>Registrarse</h2>
                <form action="" method="POST" class="auth-form">
                    <input type="text" name="reg_nombre" class="auth-input" placeholder="Nombre" required>
                    <input type="text" name="reg_apellidos" class="auth-input" placeholder="Apellidos" required>
                    <input type="email" name="reg_email" class="auth-input" placeholder="Email" required>
                    <input type="password" name="reg_pass" class="auth-input" placeholder="Contraseña" required>
                    <button type="submit" name="btn_registro" class="auth-link-btn" style="width:100%; margin-top: 10px;">CREAR CUENTA</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>