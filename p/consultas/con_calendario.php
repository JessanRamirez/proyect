<?php
	include("con_verificarsession.php");
	include("../conexion.php");
	if($_POST['aux']==1){
		$sql="TRUNCATE dias_laborables";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==2){
		$sql="INSERT INTO dias_laborables (dia) VALUES ('".$_POST['dia']."')";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==3){
		$sql="SELECT * FROM calendario WHERE fecha='".$_POST['fecha']."'";
		$resultado=$mysqli->query($sql);
		$rows=$resultado->num_rows;
		if($rows<1){
			$sql2="INSERT INTO calendario (ano, fecha) VALUES ('".$_POST['ano']."', '".$_POST['fecha']."')";
			$resultado2=$mysqli->query($sql2);
		}
	}
	if($_POST['aux']==4){
		$sql="DELETE FROM calendario WHERE id='".$_POST['id']."'";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==5){?>
    	<tr>
            <td><strong>Fecha</strong></td>
            <td width="5%"><strong>-</strong></td>
        </tr>
        <?php
        $sql2="SELECT * FROM calendario WHERE ano='".$_POST['year']."' order by fecha desc";
        $resultado2=$mysqli->query($sql2);
        $rows2=$resultado2->num_rows;
        if($rows2>0){
            while($row2=$resultado2->fetch_assoc()){?>
             <tr>
                <td><?php echo $row2['fecha'];?></td>
                <td><button onClick="eliminarfecha(<?php echo $row2['id'];?>)"><img src="../media/del01.png" title="Borrar día festivo"></button></td>
            </tr>
            <?php	
            }
        }else{
        ?>
        <tr>
            <td colspan="2">No hay días festivos.</td>
        </tr>
        <?php
        }
	}
	if($_POST['aux']==6){
		$sql="INSERT INTO horas_trabajo (horadesde, horahasta) VALUES ('".$_POST['horadesde']."','".$_POST['horahasta']."')";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==7){
		$sql="DELETE FROM horas_trabajo WHERE id='".$_POST['id']."'";
		$resultado=$mysqli->query($sql);
	}
?>
