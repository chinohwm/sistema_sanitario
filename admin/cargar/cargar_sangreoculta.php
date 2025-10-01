<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Datos Sangre Oculta</title>
    <link rel="icon" href="../img/icono.png"> 
    <link rel="stylesheet" href="../../css/cargar_sangreoculta.css">
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
           
            $fecha = $_POST['fecha'];
            $observacion = $_POST['observacion'];
            $localidad = $_POST['localidad'];
            $sede = $_POST['sede'];
            $derivacion = $_POST['derivacion'];
             // 🔥 Estado siempre será 0 o 1, si viene vacío lo ponemos en 0 (caso cerrado)
            $estado = (isset($_POST['estado']) && $_POST['estado'] === "1") ? 1 : 0;

            $query = "INSERT INTO sangre_oculta (id_paciente, estado, fecha, observacion, localidad, sede, derivacion ) 
                      VALUES ('$id_paciente', '$estado', '$fecha', '$observacion', '$localidad', '$sede','$derivacion')";

            if ($conex->query($query)) {
                header("Location: ../ficha_paciente.php?id_paciente=$id_paciente");
                exit();
            } else {
                echo "Error al cargar los datos de Sangre Oculta: " . $conex->error;
            }
        }
?>
    
    <div class="form-container">
        <h1>Cargar Datos Sangre Oculta</h1>
        <form method='POST' action='<?php echo $_SERVER['PHP_SELF'] . "?id=$id_paciente"; ?>'>
            <input type='hidden' name='id_paciente' value='<?php echo $id_paciente; ?>'>
            
            <label for='estado'>Estado:</label>
            <select name='estado'  required>
                <option value=''>Seleccione un estado</option>
                <option value='1'>Seguimiento</option>
                <option value='0'>Caso cerrado</option>
            </select>
            
            <label for='fecha'>Fecha:</label>
            <input type='date' name='fecha' id='fecha' required>
            <label for='derivacion'>Derivación:</label>
            <select name='derivacion' id='derivacion' required>
            <option value=''>Seleccione una opción</option>
            <option value='1'>Sí</option>
            <option value='0'>No</option>
            </select>

            
            <label for='observacion'>Observación:</label>
            <textarea name='observacion' id='observacion' placeholder="Ingrese sus observaciones aquí..." required></textarea>

            <label for='localidad'>Localidad:</label>
            <select name='localidad' id='localidad' required>
                <option value=''>Seleccione una localidad</option>
                <?php while($loc = $localidades->fetch_assoc()) { ?>
                    <option value='<?php echo $loc['id']; ?>'><?php echo $loc['nombre']; ?></option>
                <?php } ?>
            </select>

            <label for='sede'>Sede:</label>
            <input type='text' name='sede' id='sede' required>

            
            
            <button type='submit'>Cargar Datos</button>
        </form>
    </div>
    
<?php
        $conex->close();
    } else {
        echo "<div class='error-message'>No se ha especificado un ID de paciente.</div>";
    }
?>
</body>
</html>
