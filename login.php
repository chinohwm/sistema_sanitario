
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['user'];
    $password = $_POST['pass'];
    
    if (empty($usuario) || empty($password)) {
        echo "Por favor, ingrese tanto el usuario como la contraseña.";
    } else {
        $conexion = new mysqli('localhost', 'root', '', 'secretaria_salud');

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $consulta = "SELECT * FROM usuario WHERE usuario=? AND password=?";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("ss", $usuario, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        if ($fila) {
            $_SESSION['id_cargo'] = $fila['id_cargo'];
            $_SESSION['usuario'] = $fila['usuario'];

            if ($fila['id_cargo'] == 2) {
                header("Location: admin/index.php");
                exit();
            } elseif ($fila['id_cargo'] == 1) {
                header("Location: admin/index.php");
                exit();
            } else {
                header("Location: login.php");
                exit();
            }
        } 

        $stmt->close();
        $conexion->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inicio Sesion || Secretaría De Salud</title>
    <link rel="icon" href="img/icono.png">
    <link rel="stylesheet" href="css/login1.css">
</head>
<body>
    <center>
    <h2>INICIO SESION</h2>
    <form method="POST" action="login.php">
    <div class="avatar-container">
    <img src="img/usuario.png" alt="Foto de Usuario">
    </div>

        <label for="user">Usuario:</label><br>
        <input type="text" id="user" name="user" required><br>
        <label for="pass">Contraseña:</label><br>
        <input type="password" id="pass" name="pass" required><br><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
    </center>
</body>
</html>
