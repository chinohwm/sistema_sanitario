<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Datos Mamografia</title>
    <link rel="icon" href="../../img/icono.png"> 
    <link rel="stylesheet" href="../../css/cargar_mam1.css">
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
        include("../../layouts/nav_admin.html"); 
    } elseif ($id_cargo == 2) {
        include("../../layouts/nav_promotor.html"); 
    }
            include ("../../db/conexion.php");

        if (isset($_GET['id'])) {
            $id_paciente = $_GET['id'];

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $turno = $_POST['turno'];
                $observacion = $_POST['observacion'];

                $query = "INSERT INTO mamografia (id_paciente, turno, observacion) VALUES ('$id_paciente', '$turno', '$observacion')";
                
                if ($conex->query($query)) {
                    header("Location: ../ficha_paciente.php?id_paciente=$id_paciente");
                    exit();
                } else {
                    echo "Error al cargar los datos de Mamografia: " . $conex->error;
                }
            }

    ?>
        <div class="form-container">
            <h1>Cargar Datos Mamografia</h1>
            <form method='POST' action='<?php echo $_SERVER['PHP_SELF'] . "?id=$id_paciente"; ?>'>
                <input type='hidden' name='id_paciente' value='<?php echo $id_paciente; ?>'>
                
                <label for='turno'>Turno:</label>
                <select name='turno' id='turno' required>
                    <option value='Si'>Si</option>
                    <option value='No'>No</option>
                </select><br>

                <label for='observacion'>Observación:</label><br>
                <textarea name='observacion' id='observacion' placeholder="Ingrese sus observaciones aquí..." required></textarea><br>

                <button type='submit'>Cargar Datos</button>
            </form>
        </div>
    <?php
            $conex->close();
        } else {
            echo "No se ha especificado un ID de paciente.";
        }
    ?>
</body>
</html>
