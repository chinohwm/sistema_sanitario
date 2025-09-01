<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Administrador || Secretaría De Salud</title>
    <link rel="stylesheet" href="../css/indexx.css">
    <link rel="icon" href="../img/icono.png">
    
        

</head>
<body>
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

        include("../db/conexion.php");

        $cantidadregistros = 10;
        $numpagina = isset($_GET['pagina']) ? max(0, (int)$_GET['pagina']) : 0;

        $filtroBusqueda = isset($_GET['filtroBusqueda']) ? trim($_GET['filtroBusqueda']) : '';

        $whereConditions = [];
        $params = [];
        $types = '';

        // Importante: Para que la búsqueda en tiempo real del JS funcione en el cliente,
        // la consulta PHP ya no debe filtrar automáticamente. El JS ocultará/mostrará.
        // Sin embargo, si quieres que la paginación de PHP siga funcionando con la búsqueda inicial,
        // puedes mantener la lógica del WHERE para la primera carga de la página.
        // Para una demostración de solo JS, quitaría el WHERE de PHP.
        // Pero dado que tienes paginación, lo mejor es que el JS solo filtre lo que ya está visible en la página actual.

        // Mantendremos la lógica de PHP para el filtro inicial y paginación
        if (!empty($filtroBusqueda)) {
            $whereConditions[] = "(nombre LIKE ? OR apellido LIKE ? OR dni LIKE ?)";
            $params[] = "%$filtroBusqueda%";
            $params[] = "%$filtroBusqueda%";
            $params[] = "%$filtroBusqueda%";
            $types .= 'sss';
        }

        $whereClause = !empty($whereConditions) ? " WHERE " . implode(" AND ", $whereConditions) : "";
    ?>

    <div class="container">
        <div class="search-container">
            <form method="GET" action="">
                <div class="search-input-wrapper">
                    <input type="text"
                           id="search-input" name="filtroBusqueda"
                           placeholder="Buscar por nombre, apellido o DNI..."
                           value="<?php echo htmlspecialchars($filtroBusqueda); ?>">
                    <button type="submit" class="btn btn-search">Buscar</button>
                    <a href="index.php" class="btn btn-clear">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="patients-container">
            <table class="patients-table" id="patients-table"> <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        try {
                            $query = "SELECT id_paciente, nombre, apellido, dni
                                      FROM pacientes" .
                                      $whereClause .
                                      " ORDER BY apellido, nombre
                                      LIMIT ? OFFSET ?";

                            $stmt = $conex->prepare($query);
                            if (!$stmt) {
                                throw new Exception("Error preparando consulta: " . $conex->error);
                            }

                            $tempParams = $params;
                            $tempParams[] = $cantidadregistros;
                            $tempParams[] = $cantidadregistros * $numpagina;
                            $tempTypes = $types . 'ii';

                            if (!empty($tempParams)) {
                                $stmt->bind_param($tempTypes, ...$tempParams);
                            }

                            $stmt->execute();
                            $resultado = $stmt->get_result();

                            if ($resultado->num_rows > 0) {
                                while ($fila = $resultado->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='patient-id'>" . htmlspecialchars($fila['id_paciente']) . "</td>";
                                    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                                    echo "<td>" . htmlspecialchars($fila['apellido']) . "</td>";
                                    echo "<td>" . htmlspecialchars($fila['dni']) . "</td>";
                                    echo '<td class="actions">';
                                    echo '<div class="action-buttons">';
                                    echo '<a href="ficha_paciente.php?id_paciente=' . urlencode($fila['id_paciente']) . '" class="btn btn-view">Ver más</a>';

                                    if ($id_cargo == 1 || $id_cargo == 2) {
                                        echo '<a href="eliminar_pacientes.php?id=' . urlencode($fila['id_paciente']) . '"
                                                  onclick="return confirm(\'¿Estás seguro de eliminar este paciente? Esta acción no se puede deshacer.\')"
                                                  class="btn btn-delete">Eliminar</a>';
                                    }
                                    echo '</div></td></tr>';
                                }
                            } else {
                                // Esta fila se manejará principalmente por JS si no hay resultados en la carga inicial
                                echo "<tr><td colspan='5' class='no-results'>No se encontraron pacientes</td></tr>";
                            }

                            $stmt->close();

                        } catch (Exception $e) {
                            echo "<tr><td colspan='5' class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php
                try {
                    $countQuery = "SELECT COUNT(*) FROM pacientes" . $whereClause;
                    $countStmt = $conex->prepare($countQuery);

                    if (!$countStmt) {
                        throw new Exception("Error preparando consulta de conteo: " . $conex->error);
                    }

                    if (!empty($params)) {
                        $countStmt->bind_param($types, ...$params);
                    }

                    $countStmt->execute();
                    $countResult = $countStmt->get_result();
                    $cantidadresultadopacientes = $countResult->fetch_row()[0];
                    $countStmt->close();

                    if ($cantidadresultadopacientes > $cantidadregistros) {
                        $total_paginas = ceil($cantidadresultadopacientes / $cantidadregistros);

                        $urlParams = [];
                        if (!empty($filtroBusqueda)) $urlParams[] = "filtroBusqueda=" . urlencode($filtroBusqueda);
                        $urlQuery = !empty($urlParams) ? "&" . implode("&", $urlParams) : "";

                        if ($numpagina > 0) {
                            $pagina_anterior = $numpagina - 1;
                            echo "<a href='index.php?pagina=$pagina_anterior$urlQuery' class='pagination-link'>&lt;&lt; Anterior</a>";
                        }

                        echo "<span class='pagination-info'>Página " . ($numpagina + 1) . " de " . $total_paginas .
                             " (Total: $cantidadresultadopacientes pacientes)</span>";

                        if ($numpagina + 1 < $total_paginas) {
                            $pagina_siguiente = $numpagina + 1;
                            echo "<a href='index.php?pagina=$pagina_siguiente$urlQuery' class='pagination-link'>Siguiente &gt;&gt;</a>";
                        }
                    } else {
                        echo "<span class='pagination-info'>Total: $cantidadresultadopacientes pacientes</span>";
                    }

                } catch (Exception $e) {
                    echo "<span class='error'>Error en paginación: " . htmlspecialchars($e->getMessage()) . "</span>";
                }
            ?>
        </div>
    </div>

    <?php
        $conex->close();
    ?>
    <script src="../js/index.js"></script>
</body>
</html>