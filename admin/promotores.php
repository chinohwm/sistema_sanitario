<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/promotores1.css">
    <title>Promotores || Secretaría De Salud</title>
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
    <h1>Tabla De Promotores</h1>
    <center>
    <a href="registrar_promotor.php", class='nuevo'>REGISTRAR NUEVO</a>
    </center><br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>APELLIDO</th>
            <th>USUARIO</th>
            <th>CONTRASEÑA</th>
            <th>ACCIONES</th>
        </tr>
        <?php
        $query = "SELECT id_usuario, nombre, apellido, usuario, password FROM usuario";
        $resultado = $conex->query($query);

        if (!$resultado) {
            die("Error en la consulta: " . $conex->error);
        }

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['id_usuario'] . "</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['apellido'] . "</td>";
            echo "<td>" . $fila['usuario'] . "</td>";
            echo "<td>" . $fila['password'] . "</td>";
            echo "<td><a href='editar_promotor.php?id_usuario=" . $fila['id_usuario'] ."' class='editar'>Editar</a><br>
            <a href='eliminar_promotor.php?id_usuario=" . $fila['id_usuario'] ."'class='eliminar'>Eliminar</a></td>";
            echo "</tr>";
        }
        $conex->close();
        ?>
    </table>
</body>
</html>