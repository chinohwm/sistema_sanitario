<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Paciente || Secretaría Salud</title>
    <link rel="icon" href="../img/icono.png">
    <link rel="stylesheet" href="../css/ficha1.css">
    </head>
<body>
    <div class="center">
    <?php
            session_start();

            if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
                header("Location: ../login.php");
                exit();
            }
        
            $id_cargo = $_SESSION['id_cargo'];
       
            if ($id_cargo == 1) {
                include("../layouts/nav_admin.html"); 
            } elseif ($id_cargo == 2) {
                include("../layouts/nav_promotor.html"); 
            }
            include ("../db/conexion.php");
            $cantidadregistros = "5";
            $numpagina = "0";
            $cantidadregistros_presion = "5";  
            $numpagina_presion = "0";
            // 👇 Agregá estas nuevas
$numpagina_sifilis = 0;
$numpagina_vih = 0;
$numpagina_vph = 0;
$numpagina_mamografia = 0;
            if (isset($_GET['pagina'])) {
                $numpagina = $_GET['pagina'];
            }
            if (isset($_GET['paginapresion'])) {
                $numpagina_presion = $_GET['paginapresion'];
            }

        if(isset($_GET['id_paciente'])){
            $id_paciente = $_GET['id_paciente'];

           $datos_personales = "
SELECT p.nombre, p.apellido, p.genero, p.fecha_nacimiento, p.dni, p.correo_electronico,
       p.domicilio, p.obra_social, p.celular,
       l.nombre AS nombre_localidad
FROM pacientes p
LEFT JOIN localidades_la_matanza l ON p.localidad = l.id
WHERE p.id_paciente = $id_paciente";



           $datos_glucemia = "
SELECT g.sede, l.nombre AS localidad, g.estado, g.derivacion, g.observacion, g.fecha
FROM glucemia g
LEFT JOIN localidades_la_matanza l ON g.localidad = l.id
WHERE g.id_paciente = $id_paciente
LIMIT $cantidadregistros OFFSET " . ($cantidadregistros*$numpagina);
$num_glucemia = "SELECT * FROM glucemia WHERE id_paciente = $id_paciente";

$datos_presion = "
SELECT pr.sede, l.nombre AS localidad, pr.estado, pr.derivacion, pr.observacion, pr.fecha
FROM presion pr
LEFT JOIN localidades_la_matanza l ON pr.localidad = l.id
WHERE pr.id_paciente = $id_paciente
LIMIT $cantidadregistros_presion OFFSET " . ($cantidadregistros_presion*$numpagina_presion);
$num_presion = "SELECT * FROM presion WHERE id_paciente = $id_paciente";

$datos_sangreoculta = "
SELECT s.sede,
       l.nombre AS localidad,
       s.estado,
       s.observacion,
       s.fecha,
       s.derivacion
FROM sangre_oculta s
LEFT JOIN localidades_la_matanza l ON s.localidad = l.id
WHERE s.id_paciente = $id_paciente
LIMIT $cantidadregistros OFFSET " . ($cantidadregistros*$numpagina);


$datos_sifilis = "
SELECT s.sede,
       l.nombre AS localidad,
       s.estado,
       s.derivacion,
       s.observacion,
       s.fecha
FROM sifilis s
LEFT JOIN localidades_la_matanza l ON s.localidad = l.id
WHERE s.id_paciente = $id_paciente
LIMIT $cantidadregistros OFFSET " . ($cantidadregistros * $numpagina_sifilis);



// VIH
$datos_vih = "
SELECT v.sede,
       l.nombre AS localidad,
       v.estado,
       v.derivacion,
       v.observacion,
       v.fecha
FROM vih v
LEFT JOIN localidades_la_matanza l ON v.localidad = l.id
WHERE v.id_paciente = $id_paciente
LIMIT $cantidadregistros OFFSET " . ($cantidadregistros * $numpagina_vih);
$resultadovih = $conex->query($datos_vih);

// VPH
$datos_vph = "
SELECT v.sede,
       l.nombre AS localidad,
       v.estado,
       v.derivacion,
       v.observacion,
       v.fecha
FROM vph v
LEFT JOIN localidades_la_matanza l ON v.localidad = l.id
WHERE v.id_paciente = $id_paciente
LIMIT $cantidadregistros OFFSET " . ($cantidadregistros * $numpagina_vph);
$resultadovph = $conex->query($datos_vph);

// MAMOGRAFIA
$datos_mamografia = "
SELECT m.sede,
       l.nombre AS localidad,
       m.estado,
       m.derivacion,
       m.observacion,
       m.fecha
FROM mamografia m
LEFT JOIN localidades_la_matanza l ON m.localidad = l.id
WHERE m.id_paciente = $id_paciente
LIMIT $cantidadregistros OFFSET " . ($cantidadregistros * $numpagina_mamografia);
$resultadomamografia = $conex->query($datos_mamografia);


                $resultadopersonales = $conex->query($datos_personales);
                $resultadoglucemia = $conex->query($datos_glucemia);
                $resultadopresion = $conex->query($datos_presion);
                $resultadosangreoculta = $conex->query($datos_sangreoculta);
                $resultadosifilis = $conex->query($datos_sifilis);
                $resultadovih = $conex->query($datos_vih);
                $resultadovph = $conex->query($datos_vph);
                $resultadomamografia = $conex->query($datos_mamografia);
                $resultadonumglucemia = $conex->query($num_glucemia);
                $cantidadresultadoglucemia = $resultadonumglucemia->num_rows;
                $resultadonumpresion = $conex->query($num_presion);
                $cantidadresultadopresion = $resultadonumpresion->num_rows;
                



                $genero = null;
                $estadosangreoculta = "No hay datos cargados.";
                $fechasangreoculta = "No hay datos cargados.";
                $observacionsangreoculta = "No hay datos cargados.";
                $sifilis = "No hay datos cargados.";
                $observacionsifilis = "No hay datos cargados.";
                $derivacionsifilis = "No hay datos cargados.";
                $vih = "No hay datos cargados.";
                $observacionvih = "No hay datos cargados.";
                $derivacionvih = "No hay datos cargados.";
                $estadovph = "No hay datos cargados.";
                $fechavph = "No hay datos cargados.";
                $observacionvph = "No hay datos cargados.";
                $observacionmamografia = "No hay datos cargados.";
                $turnomamografia = "No hay datos cargados.";
                

                if ($datossangreoculta = $resultadosangreoculta->fetch_assoc()) {
                    $estadosangreoculta = $datossangreoculta['estado'];
                    $fechasangreoculta = $datossangreoculta['fecha'];
                    $observacionsangreoculta = $datossangreoculta['observacion'];
                }

                if ($datossifilis = $resultadosifilis->fetch_assoc()) {
                  
                    $observacionsifilis = $datossifilis['observacion'];
                    $derivacionsifilis = $datossifilis['derivacion'];
                }

                if ($datosvih = $resultadovih->fetch_assoc()) {
                    
                    $observacionvih = $datosvih['observacion'];
                    $derivacionvih = $datosvih['derivacion'];
                }

                if ($datosmamografia = $resultadomamografia->fetch_assoc()) {
                    $observacionmamografia = $datosmamografia['observacion'];
                    $turnomamografia = $datosmamografia['turno'];
                }

                if ($datosvph = $resultadovph->fetch_assoc()) {
                    $estadovph = $datosvph['estado'];
                    $fechavph = $datosvph['fecha'];
                    $observacionvph = $datosvph['observacion'];
                }

                if ($datospersonales = $resultadopersonales->fetch_assoc()) {
                    $nombre = $datospersonales['nombre'];
                    $apellido = $datospersonales['apellido'];
                    $genero = $datospersonales['genero'];
                    $fecha_nacimiento = $datospersonales['fecha_nacimiento'];
                    $dni = $datospersonales['dni'];
                    $correo_electronico = $datospersonales['correo_electronico'];
                    $localidad = $datospersonales['nombre_localidad'];
                    $domicilio = $datospersonales['domicilio'];
                    $obra_social = $datospersonales['obra_social'];
                    $celular = $datospersonales['celular'];

               
                }

        }
        ?>
    <h3>Ficha De Salud</h3>
    <div class="conteiner">
    <div class="datos_personales">
        <img src="../img/usuario.png" alt="foto">
        <div class="datos_personales1">
        <?php 
            if (isset($nombre)) {
                echo "<div class='info-card'>";
                echo "<h4>Nombre completo</h4>";
                echo "<p>" . $nombre . " " . $apellido . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Género</h4>";
                echo "<p>" . $genero . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Fecha de nacimiento</h4>";
                echo "<p>" . $fecha_nacimiento . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>DNI</h4>";
                echo "<p>" . $dni . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Correo electrónico</h4>";
                echo "<p>" . $correo_electronico . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Localidad</h4>";
                echo "<p>" . $localidad . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Domicilio</h4>";
                echo "<p>" . $domicilio . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Obra social</h4>";
                echo "<p>" . $obra_social . "</p>";
                echo "</div>";

                // Botón de WhatsApp
if (!empty($celular)) {
    // Convertir el número al formato correcto
    $numero_formateado = preg_replace('/[^0-9]/', '', $celular); // elimina espacios, guiones, etc.
    if (strpos($numero_formateado, '54') !== 0) {
        $numero_formateado = '54' . $numero_formateado; // agrega +54 si no está
    }

    $mensaje = urlencode("Hola $nombre, le escribimos desde la Secretaría de Salud. ¿Cómo se encuentra?");
    
    echo "<div class='info-card'>";
    echo "<h4>Contacto</h4>";
    echo "<a href='https://wa.me/$numero_formateado?text=$mensaje' target='_blank' 
            style='background-color:#25D366;
                   color:white;
                   padding:10px 15px;
                   border-radius:8px;
                   text-decoration:none;
                   font-weight:bold;
                   display:inline-block;'>
            📱 Enviar WhatsApp
          </a>";
    echo "</div>";
} else {
    echo "<div class='info-card'><h4>Contacto</h4><p>No tiene celular registrado.</p></div>";
}

                
              
                
                echo "<div class='actions-container'>";
                echo "<button><a href='editar/editar_paciente.php?id=" . $id_paciente . "' class='editar'>Editar</a></button>";
                
                if ($id_cargo == 1 || $id_cargo == 2) {
                    echo "<button class='eliminar1'><a href='eliminar/eliminar_pacientes.php?id=" . $id_paciente . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este paciente?\")' class='eliminar'>Eliminar</a></button>";
                }
                echo "</div>";
                
            } else {
                echo "No se encontraron datos para el paciente con ID: $id_paciente";
            }
        ?>
        </div>
    </div>
    
    <form action="generar_pdf.php" method="post" target="_blank">
        <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
        <button type="submit">Imprimir PDF</button>
    </form>

    <div class="presion">
        <h3>Presión</h3>
        <?php 
            if (isset($nombre)) {
                if ($datospresion = $resultadopresion->fetch_assoc()) {
                    echo "<table border=1>";
                    echo "<tr>";
                    echo "<th>Sede</th>";
                    echo "<th>Localidad</th>";
                    echo "<th>Estado</th>";
                    echo "<th>Derivación</th>";
                    echo "<th>Observación</th>";
                    echo "<th>Fecha</th>";
              
                    echo "</tr>";

                    do {
                        echo "<tr>";
                        echo "<td>" . $datospresion['sede'] . "</td>";
                        echo "<td>" . $datospresion['localidad'] . "</td>";
                        echo "<td>" . $datospresion['estado'] . "</td>";
                        echo "<td>" . $datospresion['derivacion'] . "</td>";
                        echo "<td>" . $datospresion['observacion'] . "</td>";
                        echo "<td>" . $datospresion['fecha'] . "</td>";
                        
                        echo "</tr>";
                    } while ($datospresion = $resultadopresion->fetch_assoc());

                    echo "</table>";
                    
                    echo "<div class='pagination'>";
                    
                    if ($cantidadresultadopresion > $cantidadregistros_presion) {
                        $total_paginas_presion = ceil($cantidadresultadopresion / $cantidadregistros_presion);
                    
                        $pagina_anterior_presion = max($numpagina_presion - 1, 0);
                        if ($pagina_anterior_presion >= 0) {
                            echo "<a href='ficha_paciente.php?id_paciente=$id_paciente&pagina=$numpagina&paginapresion=$pagina_anterior_presion'><<</a>";
                        }
                    
                        echo "<span>" . $numpagina_presion . "</span>";
                    
                        $pagina_siguiente_presion = $numpagina_presion + 1;
                        if ($pagina_siguiente_presion < $total_paginas_presion) {
                            echo "<a href='ficha_paciente.php?id_paciente=$id_paciente&pagina=$numpagina&paginapresion=$pagina_siguiente_presion'>>></a>";
                        }
                    }
                    echo "</div>";
                } else {
                    echo "No hay datos de presión para este paciente.";
                }
            }
        ?>
        <button><a href='cargar/cargar_presion.php?id=<?php echo $id_paciente; ?>' class='cargar'>Cargar</a></button>
    </div>

    <div class="glucemia">
        <h3>Glucemia</h3>
        <?php 
            if (isset($nombre)) {
                if ($datosglucemia = $resultadoglucemia->fetch_assoc()) {
                    echo "<table border=1>";
                    echo "<tr>";
                    echo "<th>Sede</th>";
                    echo "<th>Localidad</th>";
                    echo "<th>Estado</th>";
                    echo "<th>Derivación</th>";
                    echo "<th>Observación</th>";
                    echo "<th>Fecha</th>";
                    echo "</tr>";

                    do {
                        echo "<tr>";
                        echo "<td>" . $datosglucemia['sede'] . "</td>";
                        echo "<td>" . $datosglucemia['localidad'] . "</td>";
                        echo "<td>" . ($datosglucemia['estado'] == 1 ? "Seguimiento" : "Caso cerrado") . "</td>";

                        echo "<td>" . $datosglucemia['derivacion'] . "</td>";
                        echo "<td>" . $datosglucemia['observacion'] . "</td>";
                        echo "<td>" . $datosglucemia['fecha'] . "</td>";
                        echo "</tr>";
                    } while ($datosglucemia = $resultadoglucemia->fetch_assoc());

                    echo "</table>";

                    echo "<div class='pagination'>";
                    
                    if ($cantidadresultadoglucemia > $cantidadregistros) {
                        $total_paginas = ceil($cantidadresultadoglucemia / $cantidadregistros);

                        $pagina_anterior = max($numpagina - 1, 0);
                        if ($pagina_anterior >= 0) {
                            echo "<a href='ficha_paciente.php?id_paciente=$id_paciente&pagina=$pagina_anterior&paginapresion=$numpagina_presion'><<</a>";
                        }

                        echo "<span>" . $numpagina . "</span>";

                        $pagina_siguiente = $numpagina + 1;
                        if ($pagina_siguiente < $total_paginas) {
                            echo "<a href='ficha_paciente.php?id_paciente=$id_paciente&pagina=$pagina_siguiente&paginapresion=$numpagina_presion'>>></a>";
                        }
                    }

                    echo "</div>";
                } else {
                    echo "No hay datos de glucemia para este paciente.";
                }
            }
        ?>
        <button><a href='cargar/cargar_glu.php?id=<?php echo $id_paciente; ?>' class='cargar'>Cargar</a></button>
    </div>
<!-- SANGRE OCULTA -->
<div class="glucemia">
    <h3>Sangre Oculta</h3>
    <?php 
        if (isset($nombre)) {
            if ($datossangreoculta = $resultadosangreoculta->fetch_assoc()) {
                echo "<table border=1>";
                echo "<tr>";
                echo "<th>Sede</th>";
                echo "<th>Localidad</th>";
                echo "<th>Estado</th>";
                echo "<th>Derivación</th>";
                echo "<th>Observación</th>";
                echo "<th>Fecha</th>";
                echo "</tr>";

                do {
                    echo "<tr>";
                    // Mostramos directamente lo que se escribió
                    echo "<td>" . htmlspecialchars($datossangreoculta['sede']) . "</td>";
                    echo "<td>" . htmlspecialchars($datossangreoculta['localidad']) . "</td>";
                    echo "<td>" . ($datossangreoculta['estado'] == 1 ? "Seguimiento" : "Caso cerrado") . "</td>";
                    echo "<td>" . (($datossangreoculta['derivacion'] == '1' || $datossangreoculta['derivacion'] === 1) ? 'Sí' : 'No') . "</td>";
                    echo "<td>" . htmlspecialchars($datossangreoculta['observacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($datossangreoculta['fecha']) . "</td>";
                    echo "</tr>";
                } while ($datossangreoculta = $resultadosangreoculta->fetch_assoc());

                echo "</table>";
            } else {
                echo "No hay datos de Sangre Oculta para este paciente.";
            }
        }
    ?>
    <button><a href='cargar/cargar_sangreoculta.php?id=<?php echo $id_paciente; ?>' class='cargar'>Cargar</a></button>
</div>



<!-- SIFILIS -->
<div class="glucemia">
    <h3>Sífilis</h3>
    <?php 
        if (isset($nombre)) {
            if ($datossifilis = $resultadosifilis->fetch_assoc()) {
                echo "<table border=1>";
                echo "<tr>";
                echo "<th>Sede</th>";
                echo "<th>Localidad</th>";
                echo "<th>Estado</th>";
                echo "<th>Derivación</th>";
                echo "<th>Observación</th>";
                echo "<th>Fecha</th>";
                echo "</tr>";

                do {
                    // Convertimos estado de 0/1 a texto
                    $estado_texto = ($datossifilis['estado'] == 1) ? "Seguimiento" : "Caso cerrado";

                    echo "<tr>";
                    echo "<td>" . $datossifilis['sede'] . "</td>";
                    echo "<td>" . $datossifilis['localidad'] . "</td>";
                    echo "<td>" . $estado_texto . "</td>";
                    echo "<td>" . $datossifilis['derivacion'] . "</td>";
                    echo "<td>" . $datossifilis['observacion'] . "</td>";
                    echo "<td>" . $datossifilis['fecha'] . "</td>";
                    echo "</tr>";
                } while ($datossifilis = $resultadosifilis->fetch_assoc());

                echo "</table>";
            } else {
                echo "No hay datos de Sífilis para este paciente.";
            }
        }
    ?>
    <button><a href='cargar/cargar_sifilis.php?id=<?php echo $id_paciente; ?>' class='cargar'>Cargar</a></button>
</div>



<!-- VIH -->
<div class="glucemia">
    <h3>VIH</h3>
    <?php 
        if (isset($nombre)) {
            if ($datosvih = $resultadovih->fetch_assoc()) {
               echo "<table border=1>";
                    echo "<tr>";
                    echo "<th>Sede</th>";
                    echo "<th>Localidad</th>";
                    echo "<th>Estado</th>";
                    echo "<th>Derivación</th>";
                    echo "<th>Observación</th>";
                    echo "<th>Fecha</th>";
              
                    echo "</tr>";

                do {
                    echo "<tr>";
                    echo "<tr>";
                        echo "<td>" . $datosvih['sede'] . "</td>";
                        echo "<td>" . $datosvih['localidad'] . "</td>";
                        echo "<td>" . ($datosvih['estado'] == 1 ? "Seguimiento" : "Caso cerrado") . "</td>";

                        echo "<td>" . $datosvih['derivacion'] . "</td>";
                        echo "<td>" . $datosvih['observacion'] . "</td>";
                        echo "<td>" . $datosvih['fecha'] . "</td>";
                        echo "</tr>";
                } while ($datosvih = $resultadovih->fetch_assoc());

                echo "</table>";
            } else {
                echo "No hay datos de VIH para este paciente.";
            }
        }
    ?>
    <button><a href='cargar/cargar_vih.php?id=<?php echo $id_paciente; ?>' class='cargar'>Cargar</a></button>
</div>

<!-- VPH -->
<div class="glucemia">
    <h3>VPH</h3>
    <?php 
        if (isset($nombre)) {
            if ($datosvph = $resultadovph->fetch_assoc()) {
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>Sede</th>";
                echo "<th>Localidad</th>";
                echo "<th>Estado</th>";
                echo "<th>Derivación</th>";
                echo "<th>Observación</th>";
                echo "<th>Fecha</th>";
                echo "</tr>";

                do {
                    echo "<tr>";
                    echo "<td>" . $datosvph['sede'] . "</td>";
                    echo "<td>" . $datosvph['localidad'] . "</td>";
                    echo "<td>" . ($datosvph['estado'] == 1 ? "Seguimiento" : "Caso cerrado") . "</td>";
                    echo "<td>" . ($datosvph['derivacion'] == 1 ? "Sí" : "No") . "</td>";
                    echo "<td>" . $datosvph['observacion'] . "</td>";
                    echo "<td>" . $datosvph['fecha'] . "</td>";
                    echo "</tr>";
                } while ($datosvph = $resultadovph->fetch_assoc());

                echo "</table>";
            } else {
                echo "No hay datos de VPH para este paciente.";
            }
        }
    ?>
    <button>
        <a href="cargar/cargar_vph.php?id=<?php echo $id_paciente; ?>" class="cargar">Cargar</a>
    </button>
</div>


<!-- MAMOGRAFÍA (solo mujeres) -->
<?php if (isset($genero) && $genero === "Femenino"){?>
<div class="glucemia">
    <h3>Mamografía</h3>
    <?php 
        if ($datosmamografia = $resultadomamografia->fetch_assoc()) {
            echo "<table border=1>";
            echo "<tr>";
            echo "<th>Observación</th>";
            echo "<th>Turno</th>";
            echo "</tr>";

            do {
                echo "<tr>";
                echo "<td>" . $datosmamografia['observacion'] . "</td>";
                echo "<td>" . $datosmamografia['turno'] . "</td>";
                echo "</tr>";
            } while ($datosmamografia = $resultadomamografia->fetch_assoc());

            echo "</table>";
        } else {
            echo "No hay datos de Mamografía para este paciente.";
        }
    ?>
    <button><a href='cargar/cargar_mam.php?id=<?php echo $id_paciente; ?>' class='cargar'>Cargar</a></button>
</div>
<?php } ?>

    </div>
    </div>
    </div>
    </body>
</html>