<section class="section-URBAN"> 
    <div class="contenedor-tarjetas">
        <a href="../public/Results.php?filtro=andalucia" style="display: contents;">
        <div class="tarjeta" style="background-image:url('../assets/navigationimg/andalucia_urban.jpg');">
            <h3><p>VISITA EL ARTE <BR> DE ANDALUCÍA</p></h3>
        </div>
        </a>

        <a href="../public/Results.php?filtro=europa" style="display: contents;">
            <div  class="tarjeta" style="background-image: url('../assets/navigationimg/berlin.jpg');">
                <h3><p>VIAJA POR <BR>  EUROPA</p></h3>
            </div>
        </a>
        <a href="../public/Results.php?filtro=promotions" style="display: contents;">
            <div class="tarjeta" style="background-image: url('../assets/navigationimg/latam.jpg');">
                <h3><p>SEMANA SANTA <br>PROMOCIONES</p></h3>
            </div>  
        </a>
        <a href="../public/Results.php?filtro=verano" style="display: contents;">
            <div class="tarjeta" style="background-image: url('../assets/navigationimg/us.jpg');">
                <h3> <p> VERANO 2026 <BR>LLENO DE ARTE </p></h3>
            </div> 
        </a>

    </div>
</section>

<article> 
    <div>
        <H1>NUESTRA ESENCIA:</H1>
        <p>
            En <strong>URBAN TRIPS</strong>, no solo organizamos viajes; diseñamos <strong>inmersiones culturales</strong>
            en el corazón de las metrópolis más vibrantes del mundo. Creemos que las paredes de una ciudad cuentan historias
            más honestas que cualquier libro de texto. Nuestra misión es conectar a viajeros curiosos con el pulso creativo de
            los barrios, transformando el concepto tradicional de "hacer turismo" en una experiencia de
            descubrimiento artístico y social.
        </p>
    </div>
</article>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tarjetas = document.querySelectorAll(".tarjeta");
        tarjetas.forEach((tarjeta) => {           
            // --- EVENTO 1: CUANDO EL RATÓN SE MUEVE SOBRE LA TARJETA ---
            tarjeta.addEventListener("mousemove", (e) => {
                // Obtenemos las dimensiones y posición de la tarjeta en la pantalla
                const rect = tarjeta.getBoundingClientRect();
                const width = rect.width;
                const height = rect.height;
                // Calculamos la posición del ratón relativa al centro de la tarjeta
                // (El centro será 0,0. Arriba-izquierda será negativo, abajo-derecha positivo)
                const mouseX = e.clientX - rect.left - width / 2;
                const mouseY = e.clientY - rect.top - height / 2;
                // --- CÁLCULO DE INCLINACIÓN (Elegante y notable) ---
                // Dividimos la posición del ratón por un número.
                // Un número MAYOR = efecto más SUTIL (ej: / 25)
                // Un número MENOR = efecto más EXTREMO (ej: / 10)
                // Hemos ajustado estos valores para el punto medio que pediste.
                const rotateY = mouseX / 25; // La rotación en Y depende de la posición X del ratón
                const rotateX = mouseY / -25; // La rotación en X depende de la Y (invertida para el efecto "empujar")
                // Aplicamos la transformación 3D instantáneamente
                // 'scale(1.05)' hace que se acerque un poquito para más impacto
                tarjeta.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
            });
            // --- EVENTO 2: CUANDO EL RATÓN SALE DE LA TARJETA ---
            tarjeta.addEventListener("mouseleave", () => {
                tarjeta.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale(1)";
            });
        });
    });
</script>