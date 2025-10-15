<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_cargo = $_SESSION['id_cargo'];

// Incluir menú según cargo
if ($id_cargo == 1) {
    include("../layouts/nav_admin.html"); 
} elseif ($id_cargo == 2) {
    include("../layouts/nav_promotor.html"); 
}

include("../db/conexion.php");

// 🔹 Traer datos de pacientes AUH con el nombre de la localidad
$sql = "SELECT 
            a.id_auh,
            a.nombre,
            a.apellido,
            a.dni,
            a.fecha_nac,
            a.domicilio,
            l.nombre AS localidad,
            a.sede,
            a.telefono,
            a.gmail
        FROM auh a
        LEFT JOIN localidades_la_matanza l ON a.localidad = l.id
        ORDER BY a.id_auh DESC";

$result = $conex->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes AUH</title>
    <link rel="stylesheet" href="../css/auh.css">
    <style>
        table {
            width: 90%;
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
     
    </style>
</head>
<body>

<h1>Pacientes registrados en AUH</h1>
<div class="contenedor-boton">
<a href="cargar/cargar_auh.php" class="btn-nuevo">+ Registrar nuevo paciente</a>
</div>
<table>
    <tr>
      
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Fecha Nacimiento</th>
        <th>Domicilio</th>
        <th>Localidad</th>
        <th>Organización</th>
        <th>Teléfono</th>
        <th>Gmail</th>
    </tr>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                 
                    <td>{$row['nombre']}</td>
                    <td>{$row['apellido']}</td>
                    <td>{$row['dni']}</td>
                    <td>{$row['fecha_nac']}</td>
                    <td>{$row['domicilio']}</td>
                    <td>{$row['localidad']}</td>
                    <td>{$row['sede']}</td>
                    <td>{$row['telefono']}</td>
                    <td>{$row['gmail']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No hay pacientes registrados.</td></tr>";
    }

    $conex->close();
    ?>
</table>

</body>
</html>
