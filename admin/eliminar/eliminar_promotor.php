<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];
    include("../../db/conexion.php");
    $query = "DELETE FROM usuario WHERE id_usuario = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    if ($stmt->execute()) {
        header("Location: ../promotores.php");
        exit();
    } else {
        echo "Error al eliminar el usuario: " . $conex->error;
    }
}
?>
