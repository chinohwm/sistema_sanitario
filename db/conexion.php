<?php
	$conex = new mysqli("localhost","root","","secretaria_salud");
	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}
?>