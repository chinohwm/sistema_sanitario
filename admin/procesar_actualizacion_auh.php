<?php
include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id_auh']);
    $nombre = $conex->real_escape_string($_POST['nombre']);
    $apellido = $conex->real_escape_string($_POST['apellido']);
    $dni = $conex->real_escape_string($_POST['dni']);
    $fecha_nac = $conex->real_escape_string($_POST['fecha_nac']);
    $domicilio = $conex->real_escape_string($_POST['domicilio']);
    $localidad = $conex->real_escape_string($_POST['localidad']);
    $localidad_registro = $conex->real_escape_string($_POST['localidad_registro']);
    $sede = $conex->real_escape_string($_POST['sede']);
    $telefono = $conex->real_escape_string($_POST['telefono']);
    $gmail = $conex->real_escape_string($_POST['gmail']);

    $sql = "UPDATE auh SET 
                nombre = '$nombre',
                apellido = '$apellido',
                dni = '$dni',
                fecha_nac = '$fecha_nac',
                domicilio = '$domicilio',
                localidad = '$localidad',
                localidad_registro = '$localidad_registro',
                sede = '$sede',
                telefono = '$telefono',
                gmail = '$gmail'
            WHERE id_auh = $id";

    if ($conex->query($sql)) {
        echo "<script>
            alert('✅ Datos actualizados correctamente.');
            window.location.href='AUH.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al actualizar: " . addslashes($conex->error) . "');
            window.history.back();
        </script>";
    }
} else {
    header("Location: AUH.php");
}
?>
