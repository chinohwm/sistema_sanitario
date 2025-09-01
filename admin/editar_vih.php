<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar VIH</title>
    <link rel="icon" href="../img/icono.png"> 
    <link rel="stylesheet" href="../css/editar.css">
</head>
<body>
    <center>
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

            $query_vih = "SELECT vih, observacion, derivacion FROM vih WHERE id_paciente = $id_paciente";
            $resultado_vih = $conex->query($query_vih);

            if (!$resultado_vih) {
                die("Error en la consulta: " . $conex->error);
            }

            if ($fila_vih = $resultado_vih->fetch_assoc()) {
    ?>
                <h1>Editar Datos VIH</h1>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?id=$id_paciente"; ?>">
                    <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">
                    <label for="vih">VIH:</label>
                    <select name="vih" id="vih">
                        <option value="Positivo" <?php if ($fila_vih['vih'] == 'Positivo') echo 'selected'; ?>>Positivo</option>
                        <option value="Negativo" <?php if ($fila_vih['vih'] == 'Negativo') echo 'selected'; ?>>Negativo</option>
                    </select><br>
                    <label for="observacion">Observación:</label>
                    <textarea name="observacion" id="observacion"><?php echo $fila_vih['observacion']; ?></textarea><br>
                    <label for="derivacion">Derivación:</label>
                    <select name="derivacion" id="derivacion">
                        <option value="Si" <?php if ($fila_vih['derivacion'] == 'Si') echo 'selected'; ?>>Si</option>
                        <option value="No" <?php if ($fila_vih['derivacion'] == 'No') echo 'selected'; ?>>No</option>
                    </select><br>
                    <button type="submit">Guardar Cambios</button>
                </form>
    <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $vih = $_POST['vih'];
                    $observacion = $_POST['observacion'];
                    $derivacion = $_POST['derivacion'];

                    $query = "UPDATE vih SET vih='$vih', observacion='$observacion', derivacion='$derivacion' WHERE id_paciente=$id_paciente";

                    if ($conex->query($query)) {
                        header("Location: ficha_paciente.php?id_paciente=$id_paciente");
                        exit();
                    } else {
                        echo "Error al actualizar los datos de VIH: " . $conex->error;
                    }
                }

                $conex->close();
            } else {
                echo "No se encontraron datos de VIH para el paciente con ID $id_paciente.";
            }
        } else {
            echo "No se ha especificado un ID de paciente.";
        }
    ?>
    </center>
</body>
</html>
