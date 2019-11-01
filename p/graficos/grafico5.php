<?php
	session_start();
	if(!isset($_SESSION['us'])){
		header("Location: ../prohibido.php");
	}else{
		include('../conexion.php');
		$sql0="SELECT estado FROM mantenimiento";
		$resultado0=$mysqli->query($sql0);
		$row0=$resultado0->fetch_assoc();
		if($row0['estado']==1){
			if($_SESSION['us']!='1'){
				include('../liberar.php');
				liberar();
				header("Location: ../../index.php");
			}
		}
		if($_SESSION['nivel']>1){
			header("Location: ../prohibido.php");
		}
	}
?>
<link rel="stylesheet" type="text/css" href="../../css/style.css"/>
<title>Services Desk - PROSEIN - Casos más Frecuentes</title>
<link rel="shortcut icon" href="../../media/favicon.ico">
<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/highcharts.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/modules/exporting.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/highcharts-3d.js"></script>

<script>
	$(document).ready(function(e){
	$.backstretch(["../../media/fondo10.jpg"]);
	});
</script>
<script type="text/javascript">
	$(function () {
		$('#container').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: 'Medio de Recepción de casos'
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
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 35,
					dataLabels: {
						enabled: true,
						format: '{point.name}'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Cantidad de Tickets',
				data: [
					<?php
					$sql="SELECT recepcion FROM tickets  WHERE idgerencia='".$_SESSION['gerencia']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY recepcion";
					$resultado=$mysqli->query($sql);
					while($row=$resultado->fetch_assoc()){
						$descripcion=utf8_decode($row['recepcion']);
						$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND recepcion='".utf8_encode($descripcion)."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
						$resultado2=$mysqli->query($sql2);
						$row2=$resultado2->fetch_assoc();
						$cant=$row2['COUNT(*)'];?>
						['<?php echo $descripcion;?>',   <?php echo $cant;?>],
					<?php
					}?>
				]
			}]
		});
	});
</script>
<div class="graficos">
	<table>
    	<tr>
        	<td><button onClick="window.close();" title="Cerrar Ventana"><img src="../../media/atras2.png"></button></td>
        </tr>
    </table>
	<div id="container" style="height: 400px"></div><br />
    <table id="datatable" border="1"  width="100%">
        <thead>
            <tr>
                <th>Método de Recepción.</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
        <?php
		$sql="SELECT recepcion FROM tickets  WHERE idgerencia='".$_SESSION['gerencia']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY recepcion";
		$resultado=$mysqli->query($sql);
		while($row=$resultado->fetch_assoc()){
			$descripcion=utf8_decode($row['recepcion']);
			$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND recepcion='".utf8_encode($descripcion)."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado2=$mysqli->query($sql2);
			$row2=$resultado2->fetch_assoc();
			$cant=$row2['COUNT(*)'];?>
            <tr>
            	<td><?php echo $descripcion;?></td>
                <td><?php echo $cant;?></td>
            </tr>
			<?php
		}?>
   		</tbody>
        <tbody>
        	<tr>
            	<td colspan="2"><button id="exportar" name="exportar" onclick="generar_reporte('<?php echo $_GET['fecha']?>','<?php echo $_GET['fecha2']?>','5')"><img src="../../media/excel.png" title="Exportar" /></button></td>
            </tr>
        </tbody>
    </table>
</div>