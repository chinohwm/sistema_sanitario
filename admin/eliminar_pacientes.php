<?php
include("../db/conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Elimina datos de la tabla 'glucemia'
    $consultaGlucemia = "DELETE FROM glucemia WHERE id_paciente = $id";
    $resultadoGlucemia = $conex->query($consultaGlucemia);

    // Elimina datos de la tabla 'mamografia'
    $consultaMamografia = "DELETE FROM mamografia WHERE id_paciente = $id";
    $resultadoMamografia = $conex->query($consultaMamografia);

    // Elimina datos de la tabla 'sangre_oculta'
    $consultaSangreOculta = "DELETE FROM sangre_oculta WHERE id_paciente = $id";
    $resultadoSangreOculta = $conex->query($consultaSangreOculta);

    // Elimina datos de la tabla 'vih'
    $consultaVIH = "DELETE FROM vih WHERE id_paciente = $id";
    $resultadoVIH = $conex->query($consultaVIH);

    // Elimina datos de la tabla 'sifilis'
    $consultaSifilis = "DELETE FROM sifilis WHERE id_paciente = $id";
    $resultadoSifilis = $conex->query($consultaSifilis);

    // Elimina datos de la tabla 'presion'
    $consultaPresion = "DELETE FROM presion WHERE id_paciente = $id";
    $resultadoPresion = $conex->query($consultaPresion);

    // Elimina datos de la tabla 'pacientes' al final
    $consultaPacientes = "DELETE FROM pacientes WHERE id_paciente = $id";
    $resultadoPacientes = $conex->query($consultaPacientes);

    if ($resultadoPacientes && $resultadoGlucemia && $resultadoMamografia && $resultadoSangreOculta && $resultadoVIH && $resultadoSifilis && $resultadoPresion) {
        // Redirige de nuevo a la página 'index.php' si todas las eliminaciones se realizaron con éxito
        header("Location: index.php");
        exit();
    } else {
        echo "Error al eliminar los registros relacionados: " . $conex->error;
    }
}
?>
