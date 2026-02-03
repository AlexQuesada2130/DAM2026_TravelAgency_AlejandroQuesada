<?php
require_once '../classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM viajes ORDER BY id_viaje DESC LIMIT 30";
$stmt = $conn->prepare($sql);
$stmt->execute();
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    $nombreArchivo = !empty($viaje['imagen']) ? htmlspecialchars($viaje['imagen']) : "logo.png";
                    $rutaCompleta = $carpetaImagenes . $nombreArchivo;
                    $modalID = "modal-" . $viaje['id_viaje'];
                ?>

                <article class="card-viaje-nueva">
                    <div class="card-img-box">
                        <img src="<?php echo $rutaCompleta; ?>" 
                             alt="<?php echo htmlspecialchars($viaje['titulo']); ?>" 
                             onerror="this.src='../assets/list/logo.png';"> 
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

 
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-viaje')) {
            event.target.style.display = "none";
            document.body.style.overflow = "auto";
        }
    }
</script>

<style>
    
    .flex-viajes-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        width: 95%;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .card-viaje-nueva {
        background: white;
        width: 300px;
        height: 350px;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
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
        font-size: 1rem;
    }

    .modal-viaje {
        display: none;
        position: fixed; 
        z-index: 5;
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
    }

    .modal-contenido {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px;
        border-radius: 12px;
        position: relative;
    }


    .cerrar-modal {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-body { padding: 25px; text-align: left; }

</style>