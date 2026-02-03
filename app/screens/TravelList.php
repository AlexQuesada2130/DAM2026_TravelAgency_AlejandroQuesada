<?php
// app/screens/TravelList.php
require_once '../classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Traemos 6 viajes
$sql = "SELECT * FROM viajes ORDER BY id_viaje DESC LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->execute();
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Configura tu carpeta de imágenes
$carpetaImagenes = "../assets/list/"; 
?>

<section class="section-TRIPS" style="width: 100%; max-width: 100%; box-sizing: border-box;">
    
    <h2 style="color: #0B2447; margin-bottom: 30px; text-align: center; font-size: 2rem;">
        Explora nuestros Destinos
    </h2>

    <div class="flex-viajes-container">
        <?php if (!empty($viajes)): ?>
            <?php foreach ($viajes as $viaje): ?>
                
                <?php 
                    $nombreArchivo = !empty($viaje['imagen']) ? htmlspecialchars($viaje['imagen']) : "default.jpg";
                    $rutaCompleta = $carpetaImagenes . $nombreArchivo;
                    // ID único para conectar el botón con su ventana modal específica
                    $modalID = "modal-" . $viaje['id_viaje'];
                ?>

                <article class="card-viaje-nueva">
                    <div class="card-img-box">
                        <img src="<?php echo $rutaCompleta; ?>" 
                             alt="<?php echo htmlspecialchars($viaje['titulo']); ?>" 
                             onerror="this.src='../assets/companyimg/logofondo.png';"> 
                    </div>
                    
                    <div class="card-info-box">
                        <h3 class="card-viaje-title"><?php echo htmlspecialchars($viaje['titulo']); ?></h3>
                        
                        <div class="card-viaje-price">
                            <?php echo number_format($viaje['precio'], 0, ',', '.'); ?> €
                        </div>

                        <button onclick="abrirModal('<?php echo $modalID; ?>')" class="btn-ver-viaje">
                            Ver Detalles
                        </button>
                    </div>
                </article>

                <div id="<?php echo $modalID; ?>" class="modal-viaje">
                    <div class="modal-contenido">
                        <span class="cerrar-modal" onclick="cerrarModal('<?php echo $modalID; ?>')">&times;</span>
                        
                        <div class="modal-header-img" style="background-image: url('<?php echo $rutaCompleta; ?>');"></div>
                        
                        <div class="modal-body">
                            <h2 style="color: #0B2447; margin-top: 0;"><?php echo htmlspecialchars($viaje['titulo']); ?></h2>
                            
                            <div class="modal-grid-info">
                                <p><strong>Fechas:</strong> <?php echo date("d/m/Y", strtotime($viaje['fecha_inicio'])); ?> al <?php echo date("d/m/Y", strtotime($viaje['fecha_fin'])); ?></p>
                                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($viaje['tipo_viaje'] ?? 'General'); ?></p>
                                <p><strong>Plazas:</strong> <?php echo $viaje['plazas']; ?> disponibles</p>
                                <p><strong>Precio:</strong> <span style="color:#FF7E47; font-weight:bold; font-size:1.2em;"><?php echo number_format($viaje['precio'], 0, ',', '.'); ?> €</span></p>
                            </div>

                            <hr style="border: 1px solid #eee; margin: 15px 0;">
                            
                            <p style="line-height: 1.6; color: #555;">
                                <?php echo nl2br(htmlspecialchars($viaje['descripcion'])); ?>
                            </p>
                            
                            <div style="text-align: center; margin-top: 20px;">
                                <button onclick="cerrarModal('<?php echo $modalID; ?>')" class="btn-ver-viaje" style="background-color: #666;">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; width: 100%; color: white;">No hay ofertas disponibles.</p>
        <?php endif; ?>
    </div>

</section>

<script>
    function abrirModal(id) {
        document.getElementById(id).style.display = "block";
        document.body.style.overflow = "hidden"; // Evita que se haga scroll en la web de fondo
    }

    function cerrarModal(id) {
        document.getElementById(id).style.display = "none";
        document.body.style.overflow = "auto"; // Reactiva el scroll
    }

    // Cerrar si se hace clic fuera del contenido del modal
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-viaje')) {
            event.target.style.display = "none";
            document.body.style.overflow = "auto";
        }
    }
</script>

<style>
    /* --- Estilos de las Tarjetas (Mismos de antes) --- */
    .flex-viajes-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        width: 100%;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .card-viaje-nueva {
        background: white;
        width: 300px;
        height: 380px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        position: relative;
        flex-shrink: 0;
        margin-bottom: 20px;
    }

    .card-img-box {
        height: 200px;
        width: 100%;
        background-color: #eee;
    }
    .card-img-box img { width: 100%; height: 100%; object-fit: cover; }
    
    .card-info-box {
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        flex: 1;
        text-align: center;
    }
    
    .card-viaje-title {
        color: #0B2447; margin: 0; font-size: 1.2rem; font-weight: bold;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .card-viaje-price { font-size: 1.5rem; color: #FF7E47; font-weight: 800; margin: 10px 0; }
    
    .btn-ver-viaje {
        background-color: #0B2447;
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: bold;
        transition: background 0.3s;
        font-size: 1rem;
    }
    .btn-ver-viaje:hover { background-color: #2388C7; }

    /* --- ESTILOS DE LA VENTANA MODAL (DESPLEGABLE) --- */
    .modal-viaje {
        display: none; /* Oculto por defecto */
        position: fixed; 
        z-index: 9999; /* Por encima de todo */
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgba(0,0,0,0.6); /* Fondo negro semitransparente */
        backdrop-filter: blur(5px); /* Efecto borroso chulo */
    }

    .modal-contenido {
        background-color: #fefefe;
        margin: 5% auto; /* Centrado vertical */
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px; /* Ancho máximo */
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        position: relative;
        animation: animatetop 0.4s;
    }

    /* Animación de entrada */
    @keyframes animatetop {
        from {top: -300px; opacity: 0}
        to {top: 0; opacity: 1}
    }

    /* Botón X de cerrar */
    .cerrar-modal {
        color: white;
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    .cerrar-modal:hover { color: #FF7E47; }

    /* Imagen de cabecera del modal */
    .modal-header-img {
        width: 100%;
        height: 200px;
        background-size: cover;
        background-position: center;
        border-radius: 12px 12px 0 0;
    }

    .modal-body { padding: 25px; text-align: left; }

    /* Grid dentro del modal para datos clave */
    .modal-grid-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .modal-grid-info p { margin: 5px 0; color: #333; }

    /* Responsividad del modal */
    @media (max-width: 600px) {
        .modal-contenido { width: 95%; margin: 10% auto; }
        .modal-grid-info { grid-template-columns: 1fr; }
    }
</style>