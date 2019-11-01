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
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Services Desk - PROSEIN - Principal</title>
<link rel="stylesheet" type="text/css" href="../css/style.css"/>
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Cantidad de Tickets Activos'
        },
        subtitle: {
			<?php
				$sql0="SELECT descripcion FROM gerencias WHERE id=".$_SESSION['gerencia']."";
				$resultado0=$mysqli->query($sql0);
				$row0=$resultado0->fetch_assoc();
			?>
            text: '<?php echo utf8_decode($row0['descripcion']);?>'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: 'Cantidad',
            data: [
				<?php
					if($_SESSION['nivel']<2){
						$sql="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
						$resultado=$mysqli->query($sql);
						while($row=$resultado->fetch_assoc()){
							$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado!='Cerrado' AND estado!='Anulado' AND estado!='Falsa Alarma'";
							$resultado2=$mysqli->query($sql2);
							$row2=$resultado2->fetch_assoc();
							?>
							['<?php echo $row['descripcion'];?>',   <?php echo $row2['COUNT(*)'];?>],
						<?php	
						}
					}else{
						if($_SESSION['nivel']==2){
							$sql="SELECT dpto FROM usuarios_dptos WHERE idgerencia='".$_SESSION['gerencia']."' and usuario='".$_SESSION['correo']."'";
							$resultado=$mysqli->query($sql);
							while($row=$resultado->fetch_assoc()){
								$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['dpto']."' AND estado!='Cerrado' AND estado!='Anulado' AND estado!='Falsa Alarma'";
								$resultado2=$mysqli->query($sql2);
								$row2=$resultado2->fetch_assoc();
									$sql3="SELECT descripcion FROM departamentos WHERE id='".$row['dpto']."'";
									$resultado3=$mysqli->query($sql3);
									$row3=$resultado3->fetch_assoc();
								?>
								['<?php echo $row3['descripcion'];?>',   <?php echo $row2['COUNT(*)'];?>],
							<?php	
							}						
						}else{
							$sql="SELECT dpto FROM usuarios_dptos WHERE idgerencia='".$_SESSION['gerencia']."' and usuario='".$_SESSION['correo']."'";
							$resultado=$mysqli->query($sql);
							while($row=$resultado->fetch_assoc()){
								$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['dpto']."' AND estado!='Cerrado' AND estado!='Anulado' AND estado!='Falsa Alarma' and asignacion='".$_SESSION['nom_us']."'";
								$resultado2=$mysqli->query($sql2);
								$row2=$resultado2->fetch_assoc();
								$sql3="SELECT descripcion FROM departamentos WHERE id='".$row['dpto']."'";
								$resultado3=$mysqli->query($sql3);
								$row3=$resultado3->fetch_assoc();
								?>
								['<?php echo utf8_decode($row3['descripcion']);?>',   <?php echo $row2['COUNT(*)'];?>],
								<?php	
							}
						}
					}
				?>
            ]
        }]
    });
});
		</script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/highcharts.js"></script>
<script type="text/javascript" src="../Highcharts-4.1.5/js/highcharts-3d.js"></script>
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
	    <div class="menu2">
        	<input type="checkbox" id="menu-bar" />
            <label for="menu-bar" id="icon-menu" ><img src="../media/menuico.png" width="30" height="30" /></label>
        </div>
        <div class="menu">
        	<h2>MENÚ PRINCIPAL</h2>
            <?php
				$sql4="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
				$resultado4=$mysqli->query($sql4);
				$row4=$resultado4->fetch_assoc();
			?>
            <p><strong><?php echo utf8_decode($row4['descripcion']);?></strong></p>
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
        </div>
        <div class="contenido">
        	<div id="container"></div>
        </div>
    </div>
</body>
</html>