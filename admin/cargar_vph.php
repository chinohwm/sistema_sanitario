<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Datos VPH</title>
    <link rel="icon" href="../img/icono.png"> 
    <link rel="stylesheet" href="../css/editar.css">
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

        if (isset($_GET['id'])) {
            $id_paciente = $_GET['id'];

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $estado = $_POST['estado'];
                $fecha = $_POST['fecha'];
                $observacion = $_POST['observacion'];

                $query = "INSERT INTO vph (id_paciente, estado, fecha, observacion) VALUES ('$id_paciente', '$estado', '$fecha', '$observacion')";

                if ($conex->query($query)) {
                    header("Location: ficha_paciente.php?id_paciente=$id_paciente");
                    exit();
                } else {
                    echo "Error al cargar los datos de VPH: " . $conex->error;
                }
            }

    ?>
    <center>
        <br><br>
            <h1>Cargar Datos VPH</h1>
            <form method='POST' action='<?php echo $_SERVER['PHP_SELF'] . "?id=$id_paciente"; ?>'>
                <input type='hidden' name='id_paciente' value='<?php echo $id_paciente; ?>'>
                <label for='estado'>Estado:</label>
                <select name='estado'>
                    <option value='Positivo'>Positivo</option>
                    <option value='Negativo'>Negativo</option>
                </select><br>
                <label for='fecha'>Fecha:</label>
                <input type='date' name='fecha' id='fecha' required><br>
                <label for='observacion'>Observación:</label><br>
                <textarea name='observacion' id='observacion' required></textarea><br>
                <button type='submit'>Cargar Datos</button>
            </form>
    </center>
    <?php
            $conex->close();
        } else {
            echo "No se ha especificado un ID de paciente.";
        }
    ?>
</body>
</html>
