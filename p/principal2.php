<?php
	session_start();
	if(!isset($_SESSION['us'])){
		header("Location: prohibido.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Services Desk - PROSEIN - Principal</title>
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
<link rel="shortcut icon" href="../media/favicon.ico">
<link rel="stylesheet" type="text/css" href="../fontello-e5c3186a/css/fontello.css"/>
<link rel="stylesheet" type="text/css" href="../css/style2.css"/>
</head>
<body>
<script>
		$(document).ready(function(e){
		$.backstretch(["../media/fondo7.jpg"]);
		});
	</script>
	<header>	
    	<div class="contenedor">
            <input type="checkbox" id="menu-bar" />
            <label for="menu-bar"></label>
            <nav class="menu">
            	<h2 style="color:#FFF">MENÚ PRINCIPAL</h2>
            	<ul>
					<?php
                        if($_SESSION['nivel']<=1){
                    ?>
                    <li><a href="administracion.php"><img src="../media/configuraciones.png"/><label><strong> Zona Administrativa</strong></label></a></li>
                    <?php
                        }
                    ?>
                    <li><a href="tickets.php"><img src="../media/ticket.svg"/> <label><strong>  Bandeja de Tickets</strong></label></a></li>
                    <?php
                        if($_SESSION['nivel']<=1){
                    ?>
                        <li><a href="reportes.php"><img src="../media/estadistica.png"/> <label><strong>  Reportes y Estadisticas</strong></label></a></li>
                    <?php
                        }
                    ?>
                    <li><a href="salir.php"><img src="../media/salir.png"/><label><strong>   Salir</strong></label></a></li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>