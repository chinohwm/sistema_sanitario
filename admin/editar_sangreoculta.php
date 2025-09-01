<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sangre Oculta</title>
    <link rel="icon" href="../img/icono.png"> 
    <link rel="stylesheet" href="../css/editar_sangreoculta.css">
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

                $query = "UPDATE sangre_oculta SET estado='$estado', fecha='$fecha', observacion='$observacion' WHERE id_paciente=$id_paciente";

                if ($conex->query($query)) {
                    header("Location: ficha_paciente.php?id_paciente=$id_paciente");
                    exit();
                } else {
                    echo "Error al actualizar los datos de Sangre Oculta: " . $conex->error;
                }
            }

            $query_vph = "SELECT estado, fecha, observacion FROM sangre_oculta WHERE id_paciente = $id_paciente";
            $resultado_vph = $conex->query($query_vph);

            if (!$resultado_vph) {
                die("Error en la consulta: " . $conex->error);
            }

            if ($fila_vph = $resultado_vph->fetch_assoc()) {
    ?> 
            <div class="form-container">
                <h1>Editar Datos Sangre Oculta</h1>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?id=$id_paciente"; ?>">

                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado">
                        <option value="Positivo" <?php if ($fila_vph['estado'] == 'Positivo') echo 'selected'; ?>>Positivo</option>
                        <option value="Negativo" <?php if ($fila_vph['estado'] == 'Negativo') echo 'selected'; ?>>Negativo</option>
                    </select>

                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" value="<?php echo $fila_vph['fecha']; ?>" required>

                    <label for="observacion">Observación:</label>
                    <textarea name="observacion" id="observacion" placeholder="Ingrese sus observaciones aquí..." required><?php echo $fila_vph['observacion']; ?></textarea>

                    <button type="submit">Guardar Cambios</button>
                </form>
               </div>
    <?php
            } else {
                echo "No se encontraron datos de Sangre Oculta para el paciente con ID $id_paciente.";
            }

            $conex->close();
        } else {
            echo "No se ha especificado un ID de paciente.";
        }
    ?>
</body>
</html>
