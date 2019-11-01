<?php
	session_start();
	if(!isset($_SESSION['us'])){
		header("Location: prohibido.php");
	}else{
		include("conexion.php");
		$sql0="SELECT estado FROM mantenimiento";
		$resultado0=$mysqli->query($sql0);
		$row0=$resultado0->fetch_assoc();
		if($row0['estado']==1){
			if($_SESSION['us']!='1'){
				include('liberar.php');
				liberar();
				header("Location: ../index.php");
			}
		}
		if($_SESSION['nivel']<=1){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Services Desk - PROSEIN - Administracion</title>
<link rel="stylesheet" type="text/css" href="../css/style.css"/>
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="../js/funciones.js"></script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/highcharts.js"></script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/highcharts-3d.js"></script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/modules/exporting.js"></script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/modules/data.js"></script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/modules/exporting.js"></script>
<link rel="shortcut icon" href="../media/favicon.ico">
</head>
<body>
	<script>
		$(document).ready(function(e){
		$.backstretch(["../media/fondo10.jpg"]);
		});
	</script>
    <div class="principal">
        <div class="menu">
        	<h2>REPORTES Y ESTADÍSTICAS</h2>
            <?php
				$sql4="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
				$resultado4=$mysqli->query($sql4);
				$row4=$resultado4->fetch_assoc();
			?>
            <p><strong><?php echo utf8_decode($row4['descripcion']);?></strong></p>
            <ul>
            	<li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/grafico.png"/></button><label for="zonaadminpost" onclick="grafico(1)"><strong> Resumen de casos</strong></label> </li>
                <li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/grafico.png"/></button><label for="zonaadminpost" onclick="grafico(6)"><strong> Resumen por participantes</strong></label> </li>
                <li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/frecuente.png"/></button><label for="zonaadminpost" onclick="grafico(2)"><strong> Más frecuentes</strong></label> </li>
                <li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/estadistica.png"/></button><label for="zonaadminpost" onclick="grafico(3)"><strong> Estatus de casos</strong></label> </li>
                <li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/comparar.png"/></button><label for="zonaadminpost" onclick="grafico(4)"><strong> Comparativa</strong></label> </li>
                <li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/recepcion.png"/></button><label for="zonaadminpost" onclick="grafico(5)"><strong> Medio de recepción</strong></label> </li>
                <li><a href="principal.php"><img src="../media/atras.png"/><label><strong> Atras</strong></label></a></li>
                <li><a href="salir.php"><img src="../media/salir.png"/><label><strong>   Salir</strong></label></a></li>
            </ul>
        </div>
        <div class="contenido">
        	<div id="grafico" style="display:none;">
            	
            </div>
        </div>
    </div>
</body>
</html>
<?php
		}else{
				header("location: prohibido.php");
		}
	}
?>