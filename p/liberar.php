<?php
	function liberar(){
		session_start();
		unset($_SESSION['us']);
		unset($_SESSION['nivel']);
		unset($_SESSION['gerencia']);
		unset($_SESSION['correo']);
		unset($_SESSION['nom_us']);
	}
?>