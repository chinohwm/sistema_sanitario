<?php
include("../../db/conexion.php");
$id = $_GET['id'];
$conex->query("DELETE FROM auh WHERE id_auh = '$id'");
header("Location: ../admin/AUH.php");
?>
