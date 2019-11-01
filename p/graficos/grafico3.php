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
<title>Services Desk - PROSEIN - Estatus de Casos</title>
<link rel="shortcut icon" href="../../media/favicon.ico">
<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/highcharts.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/highcharts.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/modules/exporting.js"></script>
<script>
	$(document).ready(function(e){
	$.backstretch(["../../media/fondo10.jpg"]);
	});
</script>
<script type="text/javascript">
	$(function () {
	$('#container').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: 'Estatus de Casos'
		},
		subtitle: {
			<?php
				$sql0="SELECT descripcion FROM gerencias WHERE id=".$_SESSION['gerencia']."";
				$resultado0=$mysqli->query($sql0);
				$row0=$resultado0->fetch_assoc();
			?>
            text: '<?php echo utf8_decode($row0['descripcion']);?>'
        },
		xAxis: {
			categories: [
			<?php
				$sql="SELECT sub_categorias.descripcion as descripcion FROM tickets INNER JOIN sub_categorias ON sub_categorias.id=tickets.subcategoria WHERE tickets.idgerencia='".$_SESSION['gerencia']."'  AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY tickets.subcategoria";
				$resultado=$mysqli->query($sql);
				while($row=$resultado->fetch_assoc()){?>
					'<?php echo utf8_decode($row['descripcion']);?>',
				<?php
				}
			?>
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Cantidad de tickets'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y:.f}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [
			<?php
				$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
				$resultado2=$mysqli->query($sql2);
		        while($row2=$resultado2->fetch_assoc()){?>
					{name: '<?php echo $row2['descripcion'];?>',
					data: [
						<?php
						$sql3="SELECT tickets.subcategoria as id, sub_categorias.descripcion as descripcion FROM tickets INNER JOIN sub_categorias ON sub_categorias.id=tickets.subcategoria WHERE tickets.idgerencia='".$_SESSION['gerencia']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY tickets.subcategoria";
						$resultado3=$mysqli->query($sql3);
						while($row3=$resultado3->fetch_assoc()){
							$sql4="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND subcategoria='".$row3['id']."' AND departamento='".$row2['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
							$resultado4=$mysqli->query($sql4);
							$row4=$resultado4->fetch_assoc();
							echo $row4['COUNT(*)'].',';
						}
						?>
					]
				},
				<?php	
				}
			?>]
	});
	});
</script>
<div class="graficos">
	<table>
    	<tr>
        	<td><button onClick="window.close();" title="Cerrar Ventana"><img src="../../media/atras2.png"></button></td>
        </tr>
    </table>
	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div><br />
    <table id="datatable" border="1"  width="100%">
        <thead>
            <tr>
                <th>Categoría/SubCategoría</th>
                <?php
				$sql4="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
				$resultado4=$mysqli->query($sql4);
				$x=1;
				while($row4=$resultado4->fetch_assoc()){?>
					<th><?php echo utf8_decode($row4['descripcion']);?></th>
				<?php
					$x++;
				}
				?>
            </tr>
            <?php
				$sql5="SELECT sub_categorias.id as idsub, sub_categorias.descripcion as descripcion, categorias.descripcion as descripcion2 FROM tickets INNER JOIN sub_categorias ON sub_categorias.id=tickets.subcategoria INNER JOIN categorias ON tickets.categoria=categorias.id WHERE tickets.idgerencia='".$_SESSION['gerencia']."'  AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY tickets.subcategoria";
				$resultado5=$mysqli->query($sql5);
				while($row5=$resultado5->fetch_assoc()){?>
                	<tr>
						<td><?php echo utf8_decode($row5['descripcion2'].' / '.$row5['descripcion']);?></td>
                        <?php
						$sql6="SELECT id FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
						$resultado6=$mysqli->query($sql6);
					    while($row6=$resultado6->fetch_assoc()){
							$sql7="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND subcategoria='".$row5['idsub']."' AND departamento='".$row6['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
							$resultado7=$mysqli->query($sql7);
							$row7=$resultado7->fetch_assoc();?>
                            <td><?php echo $row7['COUNT(*)'];?></td>
                         <?php   
						}
						?>
                    </tr>
				<?php
				}
			?>
        </thead>
        <tbody>
        	<tr>
            	<td colspan="<?php echo ($x+1)?>"><button id="exportar" name="exportar" onclick="generar_reporte('<?php echo $_GET['fecha']?>','<?php echo $_GET['fecha2']?>','3')"><img src="../../media/excel.png" title="Exportar" /></button></td>
            </tr>
        </tbody>
    </table><br />
</div>
