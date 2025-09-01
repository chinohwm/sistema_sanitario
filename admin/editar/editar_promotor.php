<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/promotores1.css">
    <title>Editar Promotor || Secretaría De Salud</title>
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

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_usuario'])) {
        $id_usuario = $_GET['id_usuario'];
        $query = "SELECT id_usuario, nombre, apellido, usuario, password, id_cargo FROM usuario WHERE id_usuario = ?";
        $stmt = $conex->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $promotor = $result->fetch_assoc();
            $nombre = $promotor['nombre'];
            $apellido = $promotor['apellido'];
            $usuario = $promotor['usuario'];
            $password = $promotor['password'];
            $id_cargo = $promotor['id_cargo'];
        } else {
            echo "Promotor no encontrado.";
        }
    }
    ?>
    <h1>Editar Datos Promotor</h1>
    <form method="POST" action="procesar_edicion_promotor.php">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required value="<?php echo isset($nombre) ? $nombre : ''; ?>"><br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required value="<?php echo isset($apellido) ? $apellido : ''; ?>"><br>
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required value="<?php echo isset($usuario) ? $usuario : ''; ?>"><br>
        <label for="password">Contraseña:</label>
        <input type="text" name="password" id="password" required value="<?php echo isset($password) ? $password : ''; ?>"><br>
        <label for="id_cargo">ID de Cargo:</label>
        <select name="id_cargo" id="id_cargo" required>
            <option value="1" <?php echo ($id_cargo == 1) ? 'selected' : ''; ?>>Administrador</option>
            <option value="2" <?php echo ($id_cargo == 2) ? 'selected' : ''; ?>>Promotor</option>
        </select><br>
        <button type="submit">Guardar Cambios</button>
    </form>
    <?php
    $conex->close();
    ?>
</body>
</html>
