<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/editar_paciente.css">
    <title>Actualizar Paciente || Secretaría Salud</title>
    <link rel="icon" href="../../img/icono.png">
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
    $id = $_GET['id'];
    $consulta = "SELECT * FROM pacientes WHERE id_paciente = $id";
    $resultado = $conex->query($consulta);
    
    if (mysqli_num_rows($resultado) == 1) {
        $row = mysqli_fetch_array($resultado);
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $dni = $row['dni'];
        $celular = $row['celular'];
        $genero = $row['genero'];
        $fecha_nacimiento = $row['fecha_nacimiento'];
        $correo_electronico = $row['correo_electronico'];
        $localidad = $row['localidad'];
        $domicilio = $row['domicilio'];
        $obra_social = $row['obra_social'];
        
    }
}

if(isset($_POST['actualizar'])) {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $genero = $_POST['genero'];
    $celular = $_POST['celular'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $correo_electronico = $_POST['correo_electronico'];
    $localidad = $_POST['localidad'];
    $domicilio = $_POST['domicilio'];
    $obra_social = $_POST['obra_social'];
   
    $consulta = "UPDATE pacientes SET nombre = '$nombre', apellido = '$apellido', dni = '$dni', genero = '$genero',
    celular = '$celular', fecha_nacimiento = '$fecha_nacimiento', correo_electronico = '$correo_electronico',
    localidad = '$localidad', domicilio = '$domicilio', obra_social = '$obra_social' WHERE id_paciente = $id";

    if ($conex->query($consulta) === TRUE) {
        header("Location: ../../admin/ficha_paciente.php?id_paciente=$id");
    } else {
        echo "Error al actualizar el registro: " . $conex->error;
    }
}
?>
<div class="form">
<center>
    <form action="editar_paciente.php?id=<?php echo $id; ?>" method="POST" class="form_paciente">
        <br>
        <h2>Actualizar Paciente</h2>
        <label>Nombre</label><br>
        <input type="text" name="nombre" required placeholder="Nombre:" autofocus value="<?php echo $nombre; ?>"><br>
        <label>Apellido</label><br>
        <input type="text" name="apellido" placeholder="Apellido" required value="<?php echo $apellido; ?>"><br>
        <label>DNI</label><br>
        <input type="number" name="dni" placeholder="DNI" required value="<?php echo $dni; ?>"><br>
        <label>Nro Celular</label><br>
        <input type="number" name="celular" placeholder="Celular" value="<?php echo $celular; ?>"><br>
        <label>Genero</label><br>
        <select name="genero" required>
            <option value="">Género</option>
            <option value="Masculino" <?php if ($genero === "Masculino") echo "selected"; ?>>Masculino</option>
            <option value="Femenino" <?php if ($genero === "Femenino") echo "selected"; ?>>Femenino</option>
            <option value="Otro" <?php if ($genero === "Otro") echo "selected"; ?>>Otro</option>
        </select><br>
        <label>Fecha de Nacimiento</label><br>
        <input type="date" name="fecha_nacimiento" required value="<?php echo $fecha_nacimiento; ?>"><br>
        <label>Correo Electronico</label><br>
        <input type="text" name="correo_electronico" placeholder="Correo Eléctronico" required value="<?php echo $correo_electronico; ?>"><br>
        <label>Localidad</label><br>
        <input type="text" name="localidad" placeholder="Localidad" required value="<?php echo $localidad; ?>"><br>
        <label>Domicilio</label><br>
        <input type="text" name="domicilio" placeholder="Domicilio" required value="<?php echo $domicilio; ?>"><br>
        <label>Obra Social</label><br>
        <select name="obra_social" required>
            <option value="">Obra Social</option>
            <option value="Si" <?php if ($obra_social === 'Si') echo 'selected'; ?>>Si</option>
            <option value="No" <?php if ($obra_social === 'No') echo 'selected'; ?>>No</option>
        </select><br>
       
        </select><br><br>
        <input type="submit" name="actualizar" value="ACTUALIZAR">
    </form>
    </center>
</div>
</body>
</html>

