<?php 
session_start();

if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

include ("../db/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $celular = $_POST['celular'];
    $genero = $_POST['genero'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $correo_electronico = $_POST['correo_electronico'];
    $localidad_op = $_POST['localidad_op'];
    $localidad = $_POST['localidad'];
    $domicilio = $_POST['domicilio'];
    $obra_social = $_POST['obra_social'];

    $sql = "INSERT INTO pacientes (
        nombre, apellido, dni, celular, genero, fecha_nacimiento, correo_electronico, localidad_op,
        localidad, domicilio, obra_social
    ) VALUES (
        '$nombre', '$apellido', $dni, $celular, '$genero', '$fecha_nacimiento', '$correo_electronico',
        '$localidad_op', '$localidad', '$domicilio', '$obra_social'
    )";

    if (mysqli_query($conex, $sql)) {
        header("Location: ../admin/index.php");
        exit();
    } else {
        die("❌ Error al registrar los datos: " . mysqli_error($conex));
    }
}

$id_cargo = $_SESSION['id_cargo'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Paciente || Secretaría De Salud</title>
    <link rel="stylesheet" href="../css/registrar_paciente1.css">
    <link rel="icon" href="../img/icono.png">
</head>
<body>
    <?php 
        if ($id_cargo == 1) {
            include("../layouts/nav_admin.html"); 
        } elseif ($id_cargo == 2) {
            include("../layouts/nav_promotor.html"); 
        }
    ?>
    <br>
    <center>
    <form action="" method="POST" class="form_paciente">
        <br>
        <h2>Registrar Paciente</h2>
        <label>Nombre</label><br>
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <label>Apellido</label><br>
        <input type="text" name="apellido" placeholder="Apellido" required><br>
        <label>DNI</label><br>
        <input type="number" name="dni" placeholder="DNI" required><br>
        <label>Nro Celular</label><br>
        <input type="number" name="celular" placeholder="Celular"><br>
        <label>Genero</label><br>
        <select name="genero" required>
            <option value="">Género</option>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select><br>
        <label>Fecha de Nacimiento</label><br>
        <input type="date" name="fecha_nacimiento" required><br>
        <label>Correo Electronico</label><br>
        <input type="text" name="correo_electronico" placeholder="Correo Eléctronico" required><br>
        
        <!-- Localidad donde fue atendido -->
        <label>Localidad donde fue atendido</label><br>
        <select name="localidad_op" required>
            <option value="">Seleccione una localidad</option>
            <?php
                $sql_localidades = "SELECT nombre FROM localidades_la_matanza ORDER BY nombre ASC";
                $result_localidades = mysqli_query($conex, $sql_localidades);
                while ($row = mysqli_fetch_assoc($result_localidades)) {
                    echo "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
                }
            ?>
        </select><br>

        <!-- Localidad donde vive -->
        <label>Localidad donde vive</label><br>
        <select name="localidad" required>
            <option value="">Seleccione una localidad</option>
            <?php
                $result_localidades2 = mysqli_query($conex, $sql_localidades);
                while ($row = mysqli_fetch_assoc($result_localidades2)) {
                    echo "<option value='".$row['nombre']."'>".$row['nombre']."</option>";
                }
            ?>
        </select><br>

        <label>Domicilio</label><br>
        <input type="text" name="domicilio" placeholder="Domicilio" required><br>
        <label>Obra Social</label><br>
        <select name="obra_social" required>
            <option value="">Obra Social</option>
            <option value="Si">Si</option>
            <option value="No">No</option>
        </select><br>
       
        <br><br>
        <input type="submit" value="CARGAR">
        <br><br>
    </form>
    </center>
</body>
</html>
