<?php
session_start();

// 🔒 Verificar sesión
if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

include ("../../db/conexion.php");

$id_cargo = $_SESSION['id_cargo'];

// 🧾 Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $fecha_nac = $_POST['fecha_nac'];
    $domicilio = $_POST['domicilio'];
    $localidad = $_POST['localidad'];
    $sede = $_POST['sede'];
    $telefono = $_POST['telefono'];
    $gmail = $_POST['gmail'];

    // 🛡️ Consulta SQL
    $sql = "INSERT INTO auh (nombre, apellido, dni, fecha_nac, domicilio, localidad, sede, telefono, gmail) 
            VALUES ('$nombre', '$apellido', '$dni', '$fecha_nac', '$domicilio', '$localidad', '$sede', '$telefono', '$gmail')";

    if ($conex->query($sql) === TRUE) {
        // ✅ Redirigir al listado de AUH
        header("Location: ../auh.php");
        exit();
    } else {
        $error = "Error en el registro: " . $conex->error;
    }

    $conex->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Paciente AUH</title>
</head>
<body>
    <h1>Registrar Paciente AUH</h1>

    <?php
    // 🔗 Incluir menú según cargo
    if ($id_cargo == 1) {
        include("../../layouts/nav_admin.html"); 
    } elseif ($id_cargo == 2) {
        include("../../layouts/nav_promotor.html"); 
    }

    // ⚠️ Mostrar error si hubo
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>

    <center>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" id="nombre" required><br><br>

            <label for="apellido">Apellido:</label><br>
            <input type="text" name="apellido" id="apellido" required><br><br>

            <label for="dni">DNI:</label><br>
            <input type="number" name="dni" id="dni" required><br><br>

            <label for="fecha_nac">Fecha de nacimiento:</label><br>
            <input type="date" name="fecha_nac" id="fecha_nac" required><br><br>

            <label for="domicilio">Domicilio:</label><br>
            <input type="text" name="domicilio" id="domicilio" required><br><br>

            <label for="localidad">Localidad:</label><br>
            <select name="localidad" id="localidad" required>
                <option value="">Seleccionar</option>
                <?php
                $sql_localidades = "SELECT id, nombre FROM localidades_la_matanza ORDER BY nombre ASC";
                $result_localidades = $conex->query($sql_localidades);

                if ($result_localidades && $result_localidades->num_rows > 0) {
                    while ($row = $result_localidades->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }
                ?>
            </select><br><br>

            <label for="sede">Organización (Sede):</label><br>
            <input type="text" name="sede" id="sede" required><br><br>

            <label for="telefono">Teléfono:</label><br>
            <input type="text" name="telefono" id="telefono" required><br><br>

            <label for="gmail">Correo electrónico:</label><br>
            <input type="email" name="gmail" id="gmail" required><br><br>

            <input type="submit" value="Registrar Paciente">
        </form>
    </center>
</body>
</html>
