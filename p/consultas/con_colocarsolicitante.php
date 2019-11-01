<?php
	$mysqli=new mysqli("localhost","intra","Prosein2019*","intra"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
	if(mysqli_connect_error()){
		echo 'Conexión Fallida: ', mysqli_connect_error();
		exit();
	}
	if($_POST['aux']==1){
		$nom="";
		if($_POST['opc']==1){
			$sql="SELECT nombre, apellido FROM usuarios WHERE id='".trim($_POST['id'])."'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				$row=$resultado->fetch_assoc();
				$nom=$row['nombre'].' '.$row['apellido'];
			}
		}
		if($_POST['opc']==2){
			$sql="SELECT nombre FROM tiendas WHERE id='".trim($_POST['id'])."'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				$row=$resultado->fetch_assoc();
				$nom=$row['nombre'];
			}
		}
		if($_POST['opc']==3){
			$sql="SELECT nombre FROM franquicias WHERE id='".trim($_POST['id'])."'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				$row=$resultado->fetch_assoc();
				$nom=$row['nombre'];
			}
		}
		echo utf8_decode($nom);
	}
?>