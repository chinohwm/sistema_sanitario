<?php
session_start();

// 🔒 Verificar sesión
if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../../../login.php");
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
    $localidad_registro = $_POST['localidad_registro'];
    $sede = $_POST['sede'];
    $telefono = $_POST['telefono'];
    $gmail = $_POST['gmail'];

    // 🛡️ Consulta SQL
    $sql = "INSERT INTO auh (nombre, apellido, dni, fecha_nac, domicilio, localidad,localidad_registro , sede, telefono, gmail) 
            VALUES ('$nombre', '$apellido', '$dni', '$fecha_nac', '$domicilio', '$localidad',' $localidad_registro', '$sede', '$telefono', '$gmail')";

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
    <link rel="stylesheet" href="../../css/cargar_auh.css">
    <link rel="icon" href="sistema_sanitario/img/secretariasalud.png">
</head>
<body>
    

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
    
<div class="header">
  
    <h1>Registrar Paciente AUH</h1>
</div>

    <center>
        <form method="POST" action="">
  <div class="form-grid">
    <input type="text" name="nombre" class="input" placeholder="Nombre" required><br>
    <input type="text" name="apellido" class="input" placeholder="Apellido" required><br>

    <input type="number" name="dni" class="input" placeholder="DNI" required><br>
    <label for="">Fecha de Nacimiento:</label>
    <input type="date" name="fecha_nac" class="input" required><br><br>

    <input type="text" name="domicilio" class="input" placeholder="Domicilio" required><br>
    <input type="text" name="sede" class="input" placeholder="Sede" required><br>

    <input type="text" name="telefono" class="input" placeholder="Teléfono" required><br><br>
    <input type="email" name="gmail" class="input" placeholder="Correo electrónico" required><br>

    <div class="form-grid-full">
      <label for="localidad">Localidad:</label><br>
      <select name="localidad" id="localidad" class="input" required style="width:100%;">
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
      </select>
    </div>
          <div class="form-grid-full">
      <label for="localidad_registro">Localidad donde es registrado:</label><br>
      <select name="localidad_registro" id="localidad_registro" class="input" required style="width:100%;">
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
      </select>
    </div>
   <button type="submit" ><span>Registrar Paciente</span></button>

  </div>
</form>

    </center>
</body>
</html>
