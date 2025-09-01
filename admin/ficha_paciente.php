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
            if (isset($_GET['pagina'])) {
                $numpagina = $_GET['pagina'];
            }
            if (isset($_GET['paginapresion'])) {
                $numpagina_presion = $_GET['paginapresion'];
            }

        if(isset($_GET['id_paciente'])){
            $id_paciente = $_GET['id_paciente'];

            $datos_personales = "SELECT nombre, apellido, genero, fecha_nacimiento, dni, correo_electronico,
            localidad, domicilio, obra_social, peso, talla, promotor 
            FROM pacientes WHERE id_paciente = $id_paciente";

            $num_glucemia = "SELECT * FROM glucemia";
            $datos_glucemia = "SELECT sede, localidad, estado, derivacion, observacion, fecha 
            FROM glucemia WHERE id_paciente = $id_paciente LIMIT $cantidadregistros OFFSET " . ($cantidadregistros*$numpagina);

            $num_presion = "SELECT * FROM presion";
            $datos_presion = "SELECT sede, localidad, estado, derivacion, observacion, fecha 
            FROM presion WHERE id_paciente = $id_paciente LIMIT $cantidadregistros_presion OFFSET " . ($cantidadregistros_presion*$numpagina_presion);

            $datos_sangreoculta = "SELECT estado, fecha, observacion FROM sangre_oculta 
            WHERE id_paciente = $id_paciente";

            $datos_sifilis = "SELECT sifilis, observacion, derivacion FROM sifilis 
            WHERE id_paciente = $id_paciente";

            $datos_vih = "SELECT vih, observacion, derivacion FROM vih 
            WHERE id_paciente = $id_paciente";

            $datos_vph = "SELECT estado, fecha, observacion FROM vph 
            WHERE id_paciente = $id_paciente";

            $datos_mamografia = "SELECT observacion, turno FROM mamografia 
            WHERE id_paciente = $id_paciente";

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
                    $sifilis = $datossifilis['sifilis'];
                    $observacionsifilis = $datossifilis['observacion'];
                    $derivacionsifilis = $datossifilis['derivacion'];
                }

                if ($datosvih = $resultadovih->fetch_assoc()) {
                    $vih = $datosvih['vih'];
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
                    $localidad = $datospersonales['localidad'];
                    $domicilio = $datospersonales['domicilio'];
                    $obra_social = $datospersonales['obra_social'];
                    $peso = $datospersonales['peso'];
                    $talla = $datospersonales['talla'];
                    $promotor = $datospersonales['promotor'];
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
                
                echo "<div class='info-card'>";
                echo "<h4>Peso</h4>";
                echo "<p>" . $peso . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Talla</h4>";
                echo "<p>" . $talla . "</p>";
                echo "</div>";
                
                echo "<div class='info-card'>";
                echo "<h4>Promotor</h4>";
                echo "<p>" . $promotor . "</p>";
                echo "</div>";
                
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
                        echo "<td>" . $datosglucemia['estado'] . "</td>";
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

    <div class="studies-container">
        <div class="sangreoculta">
            <h3>Sangre Oculta</h3>
            <div class="study-content">
                <?php 
                    if (isset($nombre)) {
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Estado:</span>";
                        echo "<span class='data-value'>" . $estadosangreoculta . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Fecha:</span>";
                        echo "<span class='data-value'>" . $fechasangreoculta . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Observación:</span>";
                        echo "<span class='data-value'>" . $observacionsangreoculta . "</span>";
                        echo "</div>";
                    }
                ?>
            </div>
            <div class="button-container">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM sangre_oculta WHERE id_paciente = $id_paciente";
                    $result = $conex->query($sql);
                    $row = $result->fetch_assoc();
                    $registrosSangreOcultaExisten = $row['count'] > 0;

                    if ($registrosSangreOcultaExisten) {
                        echo '<button style="display: none;"><a href="cargar/cargar_sangreoculta.php?id=' . $id_paciente . '">Cargar</a></button>';
                    } else {
                        echo '<button><a href="cargar/cargar_sangreoculta.php?id=' . $id_paciente . '"class=cargar>Cargar</a></button>';
                    }
                ?>
                <button><a href='editar/editar_sangreoculta.php?id=<?php echo $id_paciente; ?>' class='editar'>Editar</a></button>
            </div>
        </div>

        <div class="sifilis">
            <h3>Sifilis</h3>
            <div class="study-content">
                <?php
                    if (isset($nombre)) {
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Sifilis:</span>";
                        echo "<span class='data-value'>" . $sifilis . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Observación:</span>";
                        echo "<span class='data-value'>" . $observacionsifilis . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Derivación:</span>";
                        echo "<span class='data-value'>" . $derivacionsifilis . "</span>";
                        echo "</div>";
                    }
                ?>
            </div>
            <div class="button-container">
                <?php
                    $sqlSifilis = "SELECT COUNT(*) AS count FROM sifilis WHERE id_paciente = $id_paciente";
                    $resultSifilis = $conex->query($sqlSifilis);
                    $rowSifilis = $resultSifilis->fetch_assoc();
                    $registrosSifilisExisten = $rowSifilis['count'] > 0;

                    if ($registrosSifilisExisten) {
                        echo '<button style="display: none;"><a href="cargar/cargar_sifilis.php?id=' . $id_paciente . '">Cargar</a></button>';
                    } else {
                        echo '<button><a href="cargar/cargar_sifilis.php?id=' . $id_paciente . '"class=cargar>Cargar</a></button>';
                    }
                ?>
                <button><a href='editar/editar_sifilis.php?id=<?php echo $id_paciente; ?>' class='editar'>Editar</a></button>
            </div>
        </div>

        <div class="vih">
            <h3>VIH</h3>
            <div class="study-content">
                <?php
                    if (isset($nombre)) {
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>VIH:</span>";
                        echo "<span class='data-value'>" . $vih . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Observación:</span>";
                        echo "<span class='data-value'>" . $observacionvih . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Derivación:</span>";
                        echo "<span class='data-value'>" . $derivacionvih . "</span>";
                        echo "</div>";
                    }
                ?>
            </div>
            <div class="button-container">
                <?php
                    $sqlvih = "SELECT COUNT(*) AS count FROM vih WHERE id_paciente = $id_paciente";
                    $resultvih = $conex->query($sqlvih);
                    $rowvih = $resultvih->fetch_assoc();
                    $registrosvihExisten = $rowvih['count'] > 0;

                    if ($registrosvihExisten) {
                        echo '<button style="display: none;"><a href="cargar/cargar_vih.php?id=' . $id_paciente . '">Cargar</a></button>';
                    } else {
                        echo '<button><a href="cargar/cargar_vih.php?id=' . $id_paciente . '"class=cargar>Cargar</a></button>';
                    }
                ?>
                <button><a href='editar/editar_vih.php?id=<?php echo $id_paciente; ?>' class='editar'>Editar</a></button>
            </div>
        </div>

        <div class="vph">
            <h3>VPH</h3>
            <div class="study-content">
                <?php
                    if (isset($nombre)) {
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>VPH:</span>";
                        echo "<span class='data-value'>" . $estadovph . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Fecha:</span>";
                        echo "<span class='data-value'>" . $fechavph . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Observación:</span>";
                        echo "<span class='data-value'>" . $observacionvph . "</span>";
                        echo "</div>";
                    }
                ?>
            </div>
            <div class="button-container">
                <?php
                    $sqlvph = "SELECT COUNT(*) AS count FROM vph WHERE id_paciente = $id_paciente";
                    $resultvph = $conex->query($sqlvph);
                    $rowvph = $resultvph->fetch_assoc();
                    $registrosvphExisten = $rowvph['count'] > 0;

                    if ($registrosvphExisten) {
                        echo '<button style="display: none;"><a href="cargar/cargar_vph.php?id=' . $id_paciente . '">Cargar</a></button>';
                    } else {
                        echo '<button><a href="cargar/cargar_vph.php?id=' . $id_paciente . '"class=cargar>Cargar</a></button>';
                    }
                ?>
                <button><a href='editar/editar_vph.php?id=<?php echo $id_paciente; ?>' class='editar'>Editar</a></button>
            </div>
        </div>

        <?php if (isset($genero) && $genero === "Femenino"){?>
            <div class="mamografia">
                <h3>Mamografía</h3>
                <div class="study-content">
                    <?php
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Observación:</span>";
                        echo "<span class='data-value'>" . $observacionmamografia . "</span>";
                        echo "</div>";
                        
                        echo "<div class='data-item'>";
                        echo "<span class='data-label'>Turno:</span>";
                        echo "<span class='data-value'>" . $turnomamografia . "</span>";
                        echo "</div>";
                    ?>
                </div>
                <div class="button-container">
                    <?php
                        $sqlmam = "SELECT COUNT(*) AS count FROM mamografia WHERE id_paciente = $id_paciente";
                        $resultmam = $conex->query($sqlmam);
                        $rowmam = $resultmam->fetch_assoc();
                        $registrosmamExisten = $rowmam['count'] > 0;

                        if ($registrosmamExisten) {
                            echo '<button style="display: none;"><a href="cargar/cargar_mam.php?id=' . $id_paciente . '">Cargar</a></button>';
                        } else {
                            echo '<button><a href="cargar/cargar_mam.php?id=' . $id_paciente . '"class=cargar>Cargar</a></button>';
                        }
                    ?>
                    <button><a href='editar/editar_mam.php?id=<?php echo $id_paciente; ?>' class='editar'>Editar</a></button>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
    </div>
    </body>
</html>