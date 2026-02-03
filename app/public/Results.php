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
        /* =========================================
           ESTRUCTURA Y CONTRASTE (SOLUCIÓN DEFINITIVA)
           ========================================= */
        body {
            background-color: #2388C7; /* FONDO AZUL PARA QUE RESALTE EL TEXTO BLANCO */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Optima, Segoe, "Segoe UI", Candara, Calibri, Arial, sans-serif;
        }

        /* 2. Z-Index para el Menú (Banner) */
        header, nav, .navbar {
            position: relative;
            z-index: 1000 !important;
            background-color: white; /* Aseguramos fondo blanco en el menú */
        }

        /* 3. Contenedor Principal */
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

        .viaje-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            z-index: 10;
        }

        .viaje-card img {
            width: 100%;
            height: 220px; 
            object-fit: cover; 
            border-bottom: 4px solid #FF7E47; 
        }

        .viaje-card h2 {
            color: #0B2447; /* Azul muy oscuro */
            font-size: 1.4rem;
            margin: 20px 20px 10px;
            line-height: 1.2;
        }

        .viaje-card p {
            color: #555;
            padding: 0 20px;
            font-size: 0.95rem;
            flex-grow: 1; 
            display: -webkit-box;
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .viaje-card .precio {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            color: #2388C7; 
            text-align: right;
            padding: 0 20px 10px;
        }

        .viaje-card .btn {
            display: block;
            background-color: #0B2447;
            color: white;
            text-align: center;
            padding: 12px;
            margin: 0 20px 20px; 
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .viaje-card .btn:hover {
            background-color: #FF7E47; /* Naranja al pasar el ratón */
            color: white;
        }

        .lista-viajes > p {
            text-align: center;
            font-size: 1.2rem;
            padding: 20px;
            border-radius: 10px;
            color: #FFFFFF;
            font-weight: bold;
            grid-column: 1 / -1;
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
                                <a href="detalle_viaje.php?id=<?php echo $viaje['id_viaje']; ?>" class="btn">Ver más</a>
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