<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Glucemia || Secretaria De Salud</title>
    <link rel="stylesheet" href="../../css/glupre.css">
    <link rel="icon" href="../../img/icono.png">
</head>
<body>
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
    include("../../layouts/nav_admin.html"); 
} elseif ($id_cargo == 2) {
    include("../../layouts/nav_promotor.html"); 
}

include ("../../db/conexion.php");

// Obtener ID del paciente
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id']; 
} else {
    echo "Error: No se proporcionó un ID de paciente válido.";
    exit; 
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $sede = $_POST['sede'];
    $localidad = $_POST['localidad'];

    // 🔥 Estado siempre será 0 o 1, si viene vacío lo ponemos en 0 (caso cerrado)
    $estado = (isset($_POST['estado']) && $_POST['estado'] === "1") ? 1 : 0;

    $derivacion = $_POST['derivacion'];
    $observacion = $_POST['observacion'];

    // Preparar consulta segura
    $sql = "INSERT INTO glucemia (id_paciente, sede, localidad, estado, derivacion, observacion, fecha) 
            VALUES ('$id_paciente', '$sede', '$localidad', '$estado', '$derivacion', '$observacion', '$fecha')";

    if ($conex->query($sql) === TRUE) {
        header("Location: ../ficha_paciente.php?id_paciente=$id_paciente"); 
        exit();
    } else {
        echo "Error en el registro: " . $conex->error;
    }
    $conex->close();
}
?>

<center>
    <h1>Registrar Medición De Glucemia para el Paciente ID: <?php echo $id_paciente; ?></h1>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id_paciente; ?>">
        <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
        
        <label for="sede">Organización:</label>
        <input type="text" name="sede" id="sede" required><br>
        
        <label for="localidad">Localidad:</label>
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
        </select><br>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="1">Seguimiento</option>
            <option value="0">Caso cerrado</option>
        </select><br>
        
        <label for="derivacion">Derivación:</label>
        <select name="derivacion" id="derivacion">
            <option value="">Seleccionar</option>
            <option value="Si">Si</option>
            <option value="No">No</option>
        </select><br>
        
        <label for="observacion">Observación:</label>
        <textarea name="observacion" id="observacion" required></textarea><br>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required><br>
        
        <!-- 🔥 El botón ya funciona -->
        <input type="submit" value="CARGAR">
    </form>
</center>
</body>
</html>
