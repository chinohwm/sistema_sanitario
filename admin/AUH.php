<?php
session_start();

// 🔒 Verificar sesión
if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_cargo = $_SESSION['id_cargo'];

// 🔹 Incluir menú según cargo
if ($id_cargo == 1) {
    include("../layouts/nav_admin.html"); 
} elseif ($id_cargo == 2) {
    include("../layouts/nav_promotor.html"); 
}

include("../db/conexion.php");

// ======================================================
// 🔹 Capturar filtros si se enviaron por GET
// ======================================================
$filtro_localidad = isset($_GET['localidad_registro']) ? $_GET['localidad_registro'] : '';
$filtro_fecha = isset($_GET['fecha_registro']) ? $_GET['fecha_registro'] : '';

// ======================================================
// 🔹 Consulta base
// ======================================================
$sql = "SELECT 
            a.id_auh,
            a.nombre,
            a.apellido,
            a.dni,
            a.fecha_nac,
            a.domicilio,
            l.nombre AS localidad,
            a.localidad_registro,
            a.sede,
            a.telefono,
            a.gmail,
            a.fecha_registro
        FROM auh a
        LEFT JOIN localidades_la_matanza l ON a.localidad = l.id
        WHERE 1=1";

// 🔹 Agregar condiciones dinámicas
if (!empty($filtro_localidad)) {
    $sql .= " AND a.localidad_registro = '" . $conex->real_escape_string($filtro_localidad) . "'";
}
if (!empty($filtro_fecha)) {
    $sql .= " AND DATE(a.fecha_registro) = '" . $conex->real_escape_string($filtro_fecha) . "'";
}

$sql .= " ORDER BY a.id_auh DESC";
$result = $conex->query($sql);

// 🔹 Traer todas las localidades para el filtro
$localidades = $conex->query("SELECT id, nombre FROM localidades_la_matanza ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes AUH</title>
    <link rel="stylesheet" href="../css/auh.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
        }
        th {
            background-color: #e0e0e0;
        }
        h1 {
            text-align: center;
        }
        .filtros {
            width: 90%;
            margin: 20px auto;
            text-align: center;
        }
        .filtros select, .filtros input, .filtros button {
            padding: 6px 10px;
            margin: 5px;
        }
        .contenedor-boton {
            text-align: center;
            margin-top: 10px;
        }
        .btn-nuevo {
            background-color: #f48fb1;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-nuevo:hover {
            background-color: #ec407a;
        }
        .btn-accion {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            color: white;
        }
        .btn-editar {
            background-color: #64b5f6;
        }
        .btn-editar:hover {
            background-color: #42a5f5;
        }
        .btn-borrar {
            background-color: #ef5350;
        }
        .btn-borrar:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<h1>Pacientes registrados en AUH</h1>

<!-- 🔹 Filtros -->
<div class="filtros">
    <form method="GET" action="">
        <label for="localidad_registro">Localidad:</label>
        <select name="localidad_registro" id="localidad_registro">
            <option value="">Todas</option>
            <?php
            if ($localidades && $localidades->num_rows > 0) {
                while ($loc = $localidades->fetch_assoc()) {
                    $selected = ($filtro_localidad == $loc['id']) ? "selected" : "";
                    echo "<option value='{$loc['id']}' $selected>{$loc['nombre']}</option>";
                }
            }
            ?>
        </select>

        <label for="fecha_registro">Fecha de registro:</label>
        <input type="date" name="fecha_registro" id="fecha_registro" value="<?php echo $filtro_fecha; ?>">

        <button type="submit">🔍 Filtrar</button>
        <a href="AUH.php" style="margin-left:10px; text-decoration:none;">❌ Limpiar filtros</a>
    </form>
</div>

<div class="contenedor-boton">
    <a href="cargar/cargar_auh.php" class="btn-nuevo">+ Registrar nuevo paciente</a>
</div>

<!-- 🔹 Tabla -->
<table>
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Fecha Nac.</th>
        <th>Domicilio</th>
        <th>Localidad</th>
        <th>Localidad Registro</th>
        <th>Organización</th>
        <th>Teléfono</th>
        <th>Gmail</th>
        <th>Fecha Registro</th>
        <th>Acciones</th>
    </tr>

  <?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Normalizamos el número (quitamos espacios o símbolos)
        $telefono = preg_replace('/\D/', '', $row['telefono']);
        // Agregamos prefijo de país (Argentina: 54) si no está
        if (substr($telefono, 0, 2) !== "54") {
            $telefono = "54" . $telefono;
        }

        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['apellido']}</td>
                <td>{$row['dni']}</td>
                <td>{$row['fecha_nac']}</td>
                <td>{$row['domicilio']}</td>
                <td>{$row['localidad']}</td>
                <td>{$row['localidad_registro']}</td>
                <td>{$row['sede']}</td>
                <td>
                    <a href='https://wa.me/{$telefono}' target='_blank' 
                       style='display:inline-block;background-color:#25D366;color:white;
                              padding:6px 10px;border-radius:6px;text-decoration:none;
                              font-weight:bold;'>
                        💬 WhatsApp
                    </a>
                </td>
                <td>{$row['gmail']}</td>
                <td>{$row['fecha_registro']}</td>
                <td>
                    <a href='editar/editar_auh.php?id={$row['id_auh']}' class='btn-accion btn-editar'>✏️ Editar</a>
                    <a href='eliminar/eliminar_auh.php?id={$row['id_auh']}' class='btn-accion btn-borrar' 
                       onclick=\"return confirm('¿Seguro que querés eliminar este registro?');\">🗑️ Eliminar</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='12'>No se encontraron registros con esos filtros.</td></tr>";
}
?>


    $conex->close();
    ?>
</table>

</body>
</html>
