<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Promotor || Secretaría De Salud</title>
    <link rel="stylesheet" href="../css/registrar_promotor1.css">
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
        include ("../db/conexion.php");
    ?>
    <br>
    <center>
    <form action="registrar_promotor.php" method="POST" class="form_promotor">
        <br>
        <h2>Registrar Promotor</h2>
        <input type="text" name="nombre" placeholder="Nombre" required><br><br>
        <input type="text" name="apellido" placeholder="Apellido" required><br><br>
        <input type="text" name="usuario" placeholder="Usuario" required><br><br>
        <input type="text" name="password" placeholder="Contraseña" required><br><br>
        <select name="id_cargo">
        <option value="">Selecciona el cargo</option>
        <?php
            $consulta = "SELECT id_cargo, cargo FROM cargo";
            $resultado = mysqli_query($conex, $consulta);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                $id_cargo = $fila['id_cargo'];
                $cargo = $fila['cargo'];
                echo "<option value='$id_cargo'>$cargo</option>";
                }
        ?>
        </select>
        <input type="submit" value="CARGAR">
    </form>
            </center>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $id_cargo = $_POST['id_cargo'];
        
        $sql = "INSERT INTO usuario (nombre, apellido, usuario, password, id_cargo)
        VALUES ('$nombre', '$apellido', '$usuario', '$password', $id_cargo)";

        if (mysqli_query($conex, $sql)) {
            header ("location: promotores.php");
            } else {
            echo "Error al registrar los datos: " . mysqli_error($conex);
                }
        }
    ?>
</body>
</html>