<?php
	include("con_verificarsession.php");
	include("../conexion.php");
	if($_POST['aux']==1){//Crear nueva gerencia
		$sql="SELECT * FROM gerencias WHERE descripcion='".utf8_encode($_POST['gerencia'])."'";
		$resultado=$mysqli->query($sql);
		$rows=$resultado->num_rows;
		if($rows>0){
			echo "Error, la gerencia ya existe.";
		}else{
			$sql2="INSERT INTO gerencias (descripcion) VALUES('".utf8_encode($_POST['gerencia'])."')";
			$resultado2=$mysqli->query($sql2);
			$sql3="SELECT MAX(id) FROM gerencias";
			$resultado3=$mysqli->query($sql3);
			$row3=$resultado3->fetch_assoc();
			$id=$row3['MAX(id)'];
			$sql4="INSERT INTO departamentos (id, descripcion, idgerencia) VALUES ('1', 'Services Desk', '$id')";
			$resultado4=$mysqli->query($sql4);
			$clave=sha1(123);
			$correo='servicesdesk@prosein.com';
			$sql7="INSERT INTO usuarios_dptos (usuario, dpto, idgerencia) VALUES ('$correo', '1', '$id')";
			$resultado7=$mysqli->query($sql7);	
			$sql8="INSERT INTO categorias (idgerencia, iddpto, descripcion) VALUES ('$id', '1', 'Ticket Devuelto')";
			$resultado8=$mysqli->query($sql8);
			$sql9="SELECT MAX(id) FROM categorias";
			$resultado9=$mysqli->query($sql9);
			$row9=$resultado9->fetch_assoc();
			$id3=$row9['MAX(id)'];
			$sql10="INSERT INTO sub_categorias (idcategoria, descripcion, sla) VALUES ('$id3', 'Ticket Devuelto', '1')";
			$resultado10=$mysqli->query($sql10);
			echo "Gerencia creada";
		}
		
	}
	if($_POST['aux']==2){//Modificar la descripción de la gerencia
		$sql="UPDATE gerencias SET descripcion='".utf8_encode($_POST['descgerencia'])."' WHERE id='".$_POST['idgerencia']."'";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==3){//Eliminar la gerencia
		$sql="DELETE FROM gerencias WHERE id='".$_POST['idgerencia']."'";
		$resultado=$mysqli->query($sql);
		$sql2="DELETE FROM departamentos WHERE idgerencia='".$_POST['idgerencia']."'";
		$resultado2=$mysqli->query($sql2);
		$sql3="DELETE FROM usuarios WHERE idgerencia='".$_POST['idgerencia']."' AND nivel!='0'";
		$resultado3=$mysqli->query($sql3);
		$sql4="DELETE FROM usuarios_dptos WHERE idgerencia='".$_POST['idgerencia']."' AND usuario!='tecnologia@prosein.com'";
		$resultado4=$mysqli->query($sql4);
		$sql5="SELECT id FROM categorias WHERE idgerencia='".$_POST['idgerencia']."'";
		$resultado5=$mysqli->query($sql5);
		while($row5=$resultado5->fetch_assoc()){
			$sql6="DELETE FROM sub_categorias WHERE idcategoria='".$row5['id']."'";
			$resultado6=$mysqli->query($sql6);
		}
		$sql7="DELETE FROM categorias WHERE idgerencia='".$_POST['idgerencia']."'";
		$resultado7=$mysqli->query($sql7);
		$sql8="DELETE FROM tickets WHERE idgerencia='".$_POST['idgerencia']."'";
		$resultado8=$mysqli->query($sql8);
		$sql9="DELETE FROM tickets_detalles WHERE idgerencia='".$_POST['idgerencia']."'";
		$resultado9=$mysqli->query($sql9);
		
	}
	if($_POST['aux']==4){//Asignar gerencia al usuario Administrador
	
		$sql="UPDATE usuarios SET idgerencia='".$_POST['gerencia']."' WHERE correo='tecnologia@prosein.com'";
		$resultado=$mysqli->query($sql);
		$sql2="UPDATE usuarios_dptos SET idgerencia='".$_POST['gerencia']."' WHERE usuario='tecnologia@prosein.com'";
		$resultado2=$mysqli->query($sql2);
		$_SESSION['gerencia']=$_POST['gerencia'];
	}
?>