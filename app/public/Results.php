<?php
require_once '../classes/Database.php'; 

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

$db = new Database(); 
$con = $db->getConnection(); 

$sql = "";
$param = "";
$titulo_pagina = "";

switch ($filtro) {
    case 'andalucia':
        $sql = "SELECT * FROM viajes WHERE tipo_viaje = :filtro";
        $param = 'andalucia';
        $titulo_pagina = "Viajes por Andalucía";
        break;
    case 'europa':
        $sql = "SELECT * FROM viajes WHERE tipo_viaje = :filtro";
        $param = 'europa';
        $titulo_pagina = "Destinos en Europa";
        break;
    case 'promotions':
        $sql = "SELECT * FROM viajes WHERE destacado = 1 OR tipo_viaje = 'oferta'";
        $titulo_pagina = "Promociones Semana Santa";
        break;
    case 'verano':
        $sql = "SELECT * FROM viajes WHERE titulo LIKE '%Verano%' OR tipo_viaje = 'verano'";
        $titulo_pagina = "Verano 2026";
        break;
    default:
        $sql = "SELECT * FROM viajes"; 
        $titulo_pagina = "Todos los Viajes";
        break;
}

$stmt = $con->prepare($sql);

if ($filtro == 'andalucia' || $filtro == 'europa') {
    $stmt->bindParam(':filtro', $param);
}

$stmt->execute();
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
    <style>
        body {
            background-color: #2388C7;
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Optima, Segoe, "Segoe UI", Candara, Calibri, Arial, sans-serif;
        }

        .container-resultados {
            flex: 1; 
            position: relative;
            z-index: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 20px;
            box-sizing: border-box;
            color: #FFFFFF; 
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 30px;
            padding-bottom: 40px;
        }

        .viaje-card {
            background-color: white; 
            border-radius: 15px;
            overflow: hidden; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column; 
            height: 100%; 
            position: relative; 
            z-index: 5; 
        }

        .viaje-card img {
            width: 100%;
            height: 220px; 
            object-fit: cover; 
            border-bottom: 4px solid #FF7E47; 
        }

        .viaje-card h2 {
            color: #000000;
            font-size: 1.4rem;
            margin: 20px 20px 10px;
        }

        .viaje-card p {
            color: #555;
            padding: 0 20px;
            font-size: 0.95rem;
            overflow: hidden;
        }

        .viaje-card .precio {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            color: #2388C7; 
            text-align: right;
            padding: 0 20px 10px;
        }

    </style>
        <main class="container-resultados">
            <h1><?php echo $titulo_pagina; ?></h1>
            
            <div class="lista-viajes">
                <?php if (count($viajes) > 0): ?>
                    
                    <div class="grid-container">
                        <?php foreach($viajes as $viaje): ?>
                            <div class="viaje-card">
                                <img src="../assets/list/<?php echo $viaje['imagen']; ?>" alt="<?php echo $viaje['titulo']; ?>">
                                <h2><?php echo $viaje['titulo']; ?></h2>
                                <p><?php echo $viaje['descripcion']; ?></p>
                                <span class="precio"><?php echo $viaje['precio']; ?>€</span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <p>No se encontraron viajes para esta categoría.</p>
                <?php endif; ?>
            </div>
        </main>
    </body>
</html>