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
				text: 'Tickets Más Frecuentes'
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
					$sql="SELECT id, categoria, subcategoria, departamento FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY subcategoria";
					$resultado=$mysqli->query($sql);
					$i=0;
					while($row=$resultado->fetch_assoc()){
						$sql2="SELECT * FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' AND id='".$row['departamento']."'";
						$resultado2=$mysqli->query($sql2);
						$row2=$resultado2->fetch_assoc();
						$sql3="SELECT * FROM categorias WHERE idgerencia='".$_SESSION['gerencia']."' AND id='".$row['categoria']."'";
						$resultado3=$mysqli->query($sql3);
						$row3=$resultado3->fetch_assoc();
						$sql4="SELECT * FROM sub_categorias WHERE id='".$row['subcategoria']."'";
						$resultado4=$mysqli->query($sql4);
						$row4=$resultado4->fetch_assoc();
						$sql5="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND subcategoria='".$row['subcategoria']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
						$resultado5=$mysqli->query($sql5);
						$row5=$resultado5->fetch_assoc();
						$tid[$i]=$row['id'];
						$tdes[$i]=$row4['descripcion'];
						$tdes2[$i]=$row3['descripcion'];
						$tdes3[$i]=$row2['descripcion'];
						$tnum[$i]=$row5['COUNT(*)'];
						$i++;
					}
					function burbuja($tnum,$tid,$tdes, $tdes2, $tdes3){
						for($i=1;$i<count($tnum);$i++)
						{
							for($j=0;$j<count($tnum)-$i;$j++)
							{
								if($tnum[$j]<$tnum[$j+1])
								{
									$k=$tnum[$j+1];
									$l=$tid[$j+1];
									$p=$tdes[$j+1];
									$s=$tdes2[$j+1];
									$o=$tdes3[$j+1];
									
									$tnum[$j+1]=$tnum[$j];
									$tid[$j+1]=$tid[$j];
									$tdes[$j+1]=$tdes[$j];
									$tdes2[$j+1]=$tdes2[$j];
									$tdes3[$j+1]=$tdes3[$j];
									
									$tnum[$j]=$k;
									$tid[$j]=$l;
									$tdes[$j]=$p;
									$tdes2[$j]=$s;
									$tdes3[$j]=$o;
								}
							}
						}
					 
						return array($tnum, $tid, $tdes, $tdes2, $tdes3);
					}
					$arrayB=burbuja($tnum, $tid, $tdes, $tdes2, $tdes3);
					$arrayC=$arrayB[0];
					$arrayD=$arrayB[1];
					$arrayE=$arrayB[2];
					$arrayX=$arrayB[3];
					$arrayF=$arrayB[4];
					if(count($arrayC)>10){
						$num=10;
					}else{
						$num=count($arrayC);
					}
					for($i=0;$i<$num;$i++){?>
						['<?php echo utf8_decode($arrayX[$i].' - '.$arrayE[$i]);?>',   <?php echo $arrayC[$i];?>],
					<?php
					}
				?>
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
                <th>Pos.</th>
                <th>Dpto.</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
        <?php
			for($i=0;$i<$num;$i++){?>
        	<tr>
            	<td><?php echo $i+1;?></td>
                <td><?php echo utf8_decode($arrayF[$i]);?></td>
                <td><?php echo utf8_decode($arrayX[$i]);?></td>
                <td><?php echo utf8_decode($arrayE[$i]);?></td>
                <td><?php echo $arrayC[$i];?></td>
            </tr>
            <?php
			}?>
   		</tbody>
        <tbody>
        	<tr>
            	<td colspan="5"><button id="exportar" name="exportar" onclick="generar_reporte('<?php echo $_GET['fecha']?>','<?php echo $_GET['fecha2']?>','2')"><img src="../../media/excel.png" title="Exportar" /></button></td>
            </tr>
        </tbody>
    </table>
</div>