<?php
	include("../conexion.php");
	if($_POST['aux']==1){//Activar Intranet
		$sql="UPDATE mantenimiento SET estado='0'";
		$resultado=$mysqli->query($sql);
		if($resultado){
			echo '1';
		}else{
			echo '2';
		}
	}
	if($_POST['aux']==2){//Desactivar Intranet
		$sql="UPDATE mantenimiento SET estado='1'";
		$resultado=$mysqli->query($sql);
		if($resultado){
			echo '1';
		}else{
			echo '2';
		}
	}
?>
