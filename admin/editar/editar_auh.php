<?php
include("../../db/conexion.php");

// Verificar si se recibe un ID válido
if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = intval($_GET['id']);

// Obtener los datos actuales del registro
$sql = "SELECT * FROM auh WHERE id_auh = $id";
$result = $conex->query($sql);

if ($result->num_rows == 0) {
    die("Registro no encontrado.");
}

$datos = $result->fetch_assoc();

// Traer todas las localidades
$localidades = $conex->query("SELECT id, nombre FROM localidades_la_matanza ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Paciente AUH</title>
<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: linear-gradient(135deg, #fce4ec, #e3f2fd);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-container {
        width: 600px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        padding: 30px 40px;
        animation: fadeIn 0.8s ease;
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #444;
    }
    label {
        font-weight: 600;
        color: #555;
        display: block;
        margin-top: 12px;
        margin-bottom: 4px;
    }
    input, select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        background-color: #fafafa;
        transition: border-color 0.2s;
    }
    input:focus, select:focus {
        border-color: #f48fb1;
        outline: none;
    }
    .botones {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 25px;
    }
    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-guardar {
        background-color: #f48fb1;
        color: white;
    }
    .btn-guardar:hover {
        background-color: #ec407a;
    }
    .btn-volver {
        background-color: #64b5f6;
        color: white;
    }
    .btn-volver:hover {
        background-color: #42a5f5;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>

<div class="form-container">
    <h2>✏️ Editar paciente AUH</h2>
    <form action="procesar_actualizacion_auh.php" method="POST">
        <input type="hidden" name="id_auh" value="<?php echo $datos['id_auh']; ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?php echo $datos['apellido']; ?>" required>

        <label>DNI:</label>
        <input type="number" name="dni" value="<?php echo $datos['dni']; ?>" required>

        <label>Fecha de nacimiento:</label>
        <input type="date" name="fecha_nac" value="<?php echo $datos['fecha_nac']; ?>" required>

        <label>Domicilio:</label>
        <input type="text" name="domicilio" value="<?php echo $datos['domicilio']; ?>" required>

        <label>Localidad:</label>
        <select name="localidad" required>
            <option value="">Seleccionar...</option>
            <?php
            mysqli_data_seek($localidades, 0);
            while ($loc = $localidades->fetch_assoc()) {
                $selected = ($loc['id'] == $datos['localidad']) ? "selected" : "";
                echo "<option value='{$loc['id']}' $selected>{$loc['nombre']}</option>";
            }
            ?>
        </select>

        <label>Localidad de registro:</label>
        <select name="localidad_registro" required>
            <option value="">Seleccionar...</option>
            <?php
            mysqli_data_seek($localidades, 0);
            while ($loc = $localidades->fetch_assoc()) {
                $selected = ($loc['id'] == $datos['localidad_registro']) ? "selected" : "";
                echo "<option value='{$loc['id']}' $selected>{$loc['nombre']}</option>";
            }
            ?>
        </select>

        <label>Organización / Sede:</label>
        <input type="text" name="sede" value="<?php echo $datos['sede']; ?>">

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo $datos['telefono']; ?>">

        <label>Correo electrónico:</label>
        <input type="email" name="gmail" value="<?php echo $datos['gmail']; ?>">

        <div class="botones">
            <button type="submit" class="btn btn-guardar">💾 Guardar cambios</button>
            <a href="../AUH.php" class="btn btn-volver">⬅️ Volver</a>
        </div>
    </form>
</div>

</body>
</html>
