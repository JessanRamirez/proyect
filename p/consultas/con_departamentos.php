<?php
	include("con_verificarsession.php");
	include("../conexion.php");
	if($_POST['aux']==1){//Agregar un departamento
		$sql0="SELECT * FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' AND descripcion='".utf8_encode($_POST['dpto'])."'";
		$resultado0=$mysqli->query($sql0);
		$rows0=$resultado0->num_rows;
		if($rows0>0){
			echo "El departamento ya existe";
		}else{
			$sql="SELECT max(id) FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' ";
			$resultado=$mysqli->query($sql);
			$row=$resultado->fetch_assoc();
			$id=$row['max(id)']+1;
			$sql2="INSERT INTO departamentos (id, descripcion, idgerencia) VALUES ('$id', '".utf8_encode($_POST['dpto'])."', '".$_SESSION['gerencia']."')";
			$resultado2=$mysqli->query($sql2);
			echo "Departamento Creado";
		}
	}
	if($_POST['aux']==2){//Modificar descripcion de un departamento
		$sql="UPDATE departamentos SET descripcion='".utf8_encode($_POST['descdpto'])."' WHERE id='".$_POST['id_dpto']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==3){//Eliminar un departamento
		$sql="DELETE FROM departamentos where id='".$_POST['dpto']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
	}
?>