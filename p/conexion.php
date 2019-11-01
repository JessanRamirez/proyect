<?php
	$mysqli=new mysqli("localhost","services_desk","Tecno2018*","services_desk"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
	if(mysqli_connect_error()){
		echo 'Conexión Fallida: ', mysqli_connect_error();
		exit();
	}
	$mysqli2=new mysqli("localhost","intra","Prosein2019*","intra"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
	if(mysqli_connect_error()){
		echo 'Conexión Fallida: ', mysqli_connect_error();
		exit();
	}	
	/*$mysqli2= new mysqli("10.0.0.11","root","cortafuego","prosein"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
	if(mysqli_connect_error()){
		echo 'Conexión Fallida: ', mysqli_connect_error();
		exit();
	}*/
?>

