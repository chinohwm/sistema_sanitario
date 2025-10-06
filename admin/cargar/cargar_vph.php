<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Datos VPH</title>
    <link rel="icon" href="../img/icono.png"> 
    <link rel="stylesheet" href="../../css/editar.css">
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
            
              // Traer localidades
            $localidades = $conex->query("SELECT id, nombre FROM localidades_la_matanza");

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                 
                $derivacion = $_POST['derivacion'];
                $observacion = $_POST['observacion'];
                $estado = (isset($_POST['estado']) && $_POST['estado'] === "1") ? 1 : 0;
                $localidad = $_POST['localidad'];
                $sede = $_POST['sede'];
                $fecha = date("Y-m-d"); // Fecha actual de la PC


               $query = "INSERT INTO vph (id_paciente, estado, fecha, observacion, derivacion, localidad, sede) 
                VALUES ('$id_paciente', '$estado', '$fecha', '$observacion', '$derivacion', '$localidad', '$sede')";


                if ($conex->query($query)) {
                    header("Location: ../ficha_paciente.php?id_paciente=$id_paciente");
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

                 <label for='localidad'>Localidad:</label>
                    <select name='localidad' id='localidad' required>
                    <option value=''>Seleccione una localidad</option>
                    <?php while($loc = $localidades->fetch_assoc()) { ?>
                        <option value='<?php echo $loc['id']; ?>'><?php echo $loc['nombre']; ?></option>
                    <?php } ?>
                    </select><br>
                    
                    <label for='sede'>Sede:</label>
                    <input type='text' name='sede' id='sede' required><br>

                    <label for='observacion'>Observación:</label><br>
                    <textarea name='observacion' id='observacion' required></textarea><br>

                    <label for='estado'>Vph:</label>
                    <select name='estado' required>
                    <option value='1'>Seguimiento</option>
                    <option value='0'>Caso cerrado</option>
                    </select><br>

                    <label for='derivacion'>Derivación:</label>
                    <select name='derivacion'>
                    <option value='Si'>Si</option>
                    <option value='No'>No</option>
                    </select><br>   
                
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
