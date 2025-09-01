<?php
session_start();

if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password']; // Asegúrate de tratar la contraseña de manera segura (por ejemplo, con hash)
    $id_cargo = $_POST['id_cargo'];

    $query = "UPDATE usuario SET nombre=?, apellido=?, usuario=?, password=?, id_cargo=? WHERE id_usuario=?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("ssssii", $nombre, $apellido, $usuario, $password, $id_cargo, $id_usuario);

    if ($stmt->execute()) {
        header("Location: promotores.php");
    } else {
        echo "Error al actualizar datos: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Solicitud no válida.";
}

$conex->close();
?>
