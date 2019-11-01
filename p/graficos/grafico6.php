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
<title>Services Desk - PROSEIN - Resumen de Casos</title>
<link rel="shortcut icon" href="../../media/favicon.ico">
<script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/highcharts.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/highcharts-3d.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/modules/exporting.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/modules/data.js"></script>
<script type="text/javascript" src="../../Highcharts-4.1.5/js/modules/exporting.js"></script>
<script>
		$(document).ready(function(e){
		$.backstretch(["../../media/fondo10.jpg"]);
		});
	</script>
<script>
		$(document).ready(function(e){
		$.backstretch(["../../media/fondo10.jpg"]);
		});
	</script>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        data: {
            table: 'datatable2'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Resumen por participantes'
        },
		subtitle: {
			<?php
				$sql="SELECT descripcion FROM gerencias WHERE id=".$_SESSION['gerencia']."";
				$resultado=$mysqli->query($sql);
				$row=$resultado->fetch_assoc();
			?>
            text: '<?php echo utf8_decode($row['descripcion']);?>'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Tickets'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
});
		</script>
<div class="graficos">
	<table>
    	<tr>
        	<td><button onClick="window.close();" title="Cerrar Ventana"><img src="../../media/atras2.png"></button></td>
        </tr>
    </table>
    <div id="container" style=" height: 400px; "></div><br />
    <table id="datatable" border="1"  width="100%">
        <thead>
            <tr>
                <th></th>
                <?php 
				$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
				$resultado2=$mysqli->query($sql2);
				$i=0;
				while($row2=$resultado2->fetch_assoc()){
					$i++;?>
					<th><?php echo $row2['descripcion'];?></th>
				<?php
				}?>
            </tr>
        </thead>
        <tbody>
        	<?php
				$sql3="SELECT id, nombre, apellido FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND activo='0' AND id>1 AND nombre!='Services'";
				$resultado3=$mysqli->query($sql3);
				while($row3=$resultado3->fetch_assoc()){?>
					<tr>
                    	<td><?php echo utf8_decode($row3['nombre'].' '.$row3['apellido']);?></td>
                        <?php
						$sql4="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
						$resultado4=$mysqli->query($sql4);
						while($row4=$resultado4->fetch_assoc()){
							$sql5="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND estado='Cerrado' AND departamento='".$row4['id']."' AND idasignacion='".$row3['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
							$resultado5=$mysqli->query($sql5);
							$row5=$resultado5->fetch_assoc();
							$sql6="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND estado='Cerrado' AND departamento='".$row4['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
							$resultado6=$mysqli->query($sql6);
							$row6=$resultado6->fetch_assoc();
							if($row5['COUNT(*)']!=0){
								$porc=($row5['COUNT(*)']*100)/$row6['COUNT(*)'];?>
                                <td><?php echo $row5['COUNT(*)'].' ('.number_format($porc,2).'%)';?></td>
                            <?php    
							}else{?>
	                            <td><?php echo $row5['COUNT(*)'];?></td>
							<?php	
							}
						}?>
                    </tr>
				<?php
				}?>
                <tr>
                	<td colspan="<?php echo $i+1;?>"><button id="exportar" name="exportar" onclick="generar_reporte('<?php echo $_GET['fecha'];?>','<?php echo $_GET['fecha2'];?>','6')"><img width="25px" height="25px" src="../../media/Excel.png"  /></button></td>
                </tr>
   		</tbody>
    </table>
    <table id="datatable2" border="1"  width="100%" style="display:none;" >
   		<thead>
            <tr>
                <th></th>
                <?php 
				$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
				$resultado2=$mysqli->query($sql2);
				while($row2=$resultado2->fetch_assoc()){?>
					<th><?php echo $row2['descripcion'];?></th>
				<?php
				}?>
            </tr>
        </thead>
        <tbody>
        	<?php
				$sql3="SELECT id, nombre, apellido FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND activo='0' AND id>1 AND nombre!='Services'";
				$resultado3=$mysqli->query($sql3);
				while($row3=$resultado3->fetch_assoc()){?>
					<tr>
                    	<td><?php echo utf8_decode($row3['nombre'].' '.$row3['apellido']);?></td>
                        <?php
						$sql4="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
						$resultado4=$mysqli->query($sql4);
						while($row4=$resultado4->fetch_assoc()){
							$sql5="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND estado='Cerrado' AND departamento='".$row4['id']."' AND idasignacion='".$row3['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
							$resultado5=$mysqli->query($sql5);
							$row5=$resultado5->fetch_assoc();?>
							<td><?php echo $row5['COUNT(*)'];?></td>
                        <?php
						}?>
                    </tr>
				<?php
				}?>
        	<tr>
            </tr>
   		</tbody>
    </table>
</div>