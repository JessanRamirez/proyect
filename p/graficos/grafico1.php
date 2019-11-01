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
            text: 'Resumen de Casos'
        },
		subtitle: {
			<?php
				$sql0="SELECT descripcion FROM gerencias WHERE id=".$_SESSION['gerencia']."";
				$resultado0=$mysqli->query($sql0);
				$row0=$resultado0->fetch_assoc();
			?>
            text: '<?php echo utf8_decode($row0['descripcion']);?>'
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
                <th>Activos</th>
                <th>Cerrados</th>
                <th>En Manos del Cliente</th>
                <th>En Espera por Terceros</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
        $resultado=$mysqli->query($sql);
        while($row=$resultado->fetch_assoc()){?>
           <tr>
                <th><?php echo $row['descripcion'];?></th>
               <?php
                $sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado!='Cerrado' AND estado!='En Manos del Cliente' AND estado!='Falsa Alarma' AND estado!='Anulado' AND estado!='En espera por terceros' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                $resultado2=$mysqli->query($sql2);
                $row2=$resultado2->fetch_assoc();
                $sql3="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado='Cerrado' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                $resultado3=$mysqli->query($sql3);
                $row3=$resultado3->fetch_assoc();
                $sql4="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado='En espera por terceros' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                $resultado4=$mysqli->query($sql4);
                $row4=$resultado4->fetch_assoc();
				$sql5="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado='En Manos del Cliente' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
				$resultado5=$mysqli->query($sql5);
                $row5=$resultado5->fetch_assoc();?>
                <td><?php echo $row2['COUNT(*)'];?></td>
                <td><?php echo $row3['COUNT(*)'];?></td>
                <td><?php echo $row5['COUNT(*)'];?></td>
                <td><?php echo $row4['COUNT(*)'];?></td>
            </tr>
        <?php    
        }
        
        ?>
   		</tbody>
        <tbody>
        	<tr>
            	<td colspan="5"><button id="exportar" name="exportar" onclick="generar_reporte('<?php echo $_GET['fecha']?>','<?php echo $_GET['fecha2']?>','1')"><img src="../../media/excel.png" title="Exportar" /></button></td>
            </tr>
        </tbody>
    </table>
    <table id="datatable2" border="1"  width="100%" style="display:none;" >
        <thead>
            <tr>
                <th></th>
                <?php 
                $sql5="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
                $resultado5=$mysqli->query($sql5);
                while($row5=$resultado5->fetch_assoc()){?>
                    <th><?php echo $row5['descripcion'];?></th>
                <?php
                }?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Activos</th>
                <?php
                $sql6="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
                $resultado6=$mysqli->query($sql6);
                while($row6=$resultado6->fetch_assoc()){
                    $sql7="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row6['id']."' AND estado!='Cerrado' AND estado!='Falsa Alarma'  AND estado!='En Manos del Cliente' AND estado!='Anulado' AND estado!='En Espera por Terceros' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                    $resultado7=$mysqli->query($sql7);
                    $row7=$resultado7->fetch_assoc();
                    ?>
                    <td><?php echo $row7['COUNT(*)'];?></td>
                <?php
                }?>
            </tr>
            <tr>
                <th>Cerrados</th>
                <?php
                $sql8="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
                $resultado8=$mysqli->query($sql8);
                while($row8=$resultado8->fetch_assoc()){
                    $sql9="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row8['id']."' AND estado='Cerrado' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                    $resultado9=$mysqli->query($sql9);
                    $row9=$resultado9->fetch_assoc();
                    ?>
                    <td><?php echo $row9['COUNT(*)'];?></td>
                <?php
                }?>
            </tr>
            <tr>
                <th>En Manos del Cliente</th>
                <?php
                $sql12="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
                $resultado12=$mysqli->query($sql12);
                while($row12=$resultado12->fetch_assoc()){
                    $sql13="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row12['id']."' AND estado='En Manos del Cliente' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                    $resultado13=$mysqli->query($sql13);
                    $row13=$resultado13->fetch_assoc();
                    ?>
                    <td><?php echo $row13['COUNT(*)'];?></td>
                <?php
                }?>
            </tr>
            <tr>
                <th>En Espera por Terceros</th>
                <?php
                $sql10="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
                $resultado10=$mysqli->query($sql10);
                while($row10=$resultado10->fetch_assoc()){
                    $sql11="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row10['id']."' AND estado='En Espera por terceros' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                    $resultado11=$mysqli->query($sql11);
                    $row11=$resultado11->fetch_assoc();
                    ?>
                    <td><?php echo $row11['COUNT(*)'];?></td>
                <?php
                }?>
            </tr>
        </tbody>        
    </table>
</div>