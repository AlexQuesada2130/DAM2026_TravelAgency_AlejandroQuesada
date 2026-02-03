<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <div class="header-top">
        <a href="../public/index.php" class="imgLogo"></a>
        <div class="contact-info">
            <p>info@urbantrips.com</p>
            <p>(+34) 600 000000</p>
            <br>
            <?php if (isset($_SESSION['id_usuario'])): ?>
                
                <div style="display: inline-block; text-align: right;">
                    <span style="color: #0B2447; margin-right: 5px;">
                        Hola, <b><?php echo htmlspecialchars($_SESSION['nombre']); ?></b>
                    </span>
                    <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == 1): ?>
                        <a href="../admin/Dashboard.php" class="btn-login" style="background-color: #0B2447; color: white; margin-right: 5px; font-size: 14px; padding: 3px 15px;">
                            Panel Admin
                        </a>
                    <?php endif; ?>
                    <a href="../public/authentication/Logout.php" class="btn-login" style="background-color: #d9534f; border-color: #d9343f; font-size: 14px; padding: 3px 15px;">
                        Cerrar Sesión
                    </a>
                </div>
            <?php else: ?> 
                <a href="../public/authentication/Login.php" class="btn-login">Área Cliente</a>
            <?php endif; ?>
        </div>
    </div>
    <nav>
        <a href="index.php"> <b>Home</b> </a>
        <a href="../public/Results.php?filtro=andalucia">Visita Andalucía </a>
        <a href="../public/Results.php?filtro=europa">Viaja por <b>Europa</b></a>
        <a href="../public/Results.php?filtro=promotions">Semana Santa</a>
        <a href="../public/Results.php?filtro=verano">Verano 2026</a>
    </nav>                
</header>