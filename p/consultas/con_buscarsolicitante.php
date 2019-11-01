<?php
	error_reporting(0);
	include("../conexion.php");
	if($_POST['aux']==1){
		if(isset($_POST['sol'])){
			$sql="SELECT id, nombre, apellido FROM usuarios WHERE nombre like '%".$_POST['sol']."%' OR apellido like '%".$_POST['sol']."%'";
			$resultado=$mysqli2->query($sql);
			$resu=array();
			$rows=$resultado->num_rows;
			$i=0;
			if($rows>0){
				while($row=$resultado->fetch_assoc()){
					$resu[$i]=utf8_decode($row['nombre'].' '.$row['apellido']);
					$i++;
				}
				$sql="SELECT id, nombre FROM tiendas WHERE nombre like '%".$_POST['sol']."%'";
				$resultado=$mysqli2->query($sql);
				$rows=$resultado->num_rows;
				if($rows>0){
					while($row=$resultado->fetch_assoc()){
						$resu[$i]=utf8_decode($row['nombre']);
						$i++;
					}
				}else{
					$sql="SELECT id, nombre FROM franquicias WHERE nombre like '%".$_POST['sol']."%'";
					$resultado=$mysqli2->query($sql);
					$rows=$resultado->num_rows;
					if($rows>0){
						while($row=$resultado->fetch_assoc()){
							$resu[$i]=utf8_decode($row['nombre']);
							$i++;
						}
					}
				}
			}else{
				$sql="SELECT id, nombre FROM tiendas WHERE nombre like '%".$_POST['sol']."%'";
				$resultado=$mysqli2->query($sql);
				$rows=$resultado->num_rows;
				if($rows>0){
					while($row=$resultado->fetch_assoc()){
						$resu[$i]=utf8_decode($row['nombre']);
						$i++;
					}
					$sql="SELECT id, nombre FROM franquicias WHERE nombre like '%".$_POST['sol']."%'";
					$resultado=$mysqli2->query($sql);
					$rows=$resultado->num_rows;
					if($rows>0){
						while($row=$resultado->fetch_assoc()){
							$resu[$i]=utf8_decode($row['nombre']);
							$i++;
						}
					}
				}else{
					$sql="SELECT id, nombre FROM franquicias WHERE nombre like '%".$_POST['sol']."%'";
					$resultado=$mysqli2->query($sql);
					$rows=$resultado->num_rows;
					if($rows>0){
						while($row=$resultado->fetch_assoc()){
							$resu[$i]=utf8_decode($row['nombre']);
							$i++;
						}
					}
				}
			}
			echo json_encode($resu);
		}
	}
	if($_POST['aux']==2){
		if(isset($_POST['sol'])){
			$aux=explode(" ",$_POST['sol']);
			$nombre=$aux[0];
			$sql="SELECT nombre, apellido, correo FROM usuarios WHERE nombre LIKE '%$nombre%'";
			$resultado=$mysqli2->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				while($row=$resultado->fetch_assoc()){
					if (utf8_decode(($row['nombre'].' '.$row['apellido']))==$_POST['sol']){
						echo $row['correo'];
						break;
					}
				}
			}else{
				$sql="SELECT nombre, correo FROM tiendas WHERE nombre LIKE '%$nombre%'";
				$resultado=$mysqli2->query($sql);
				$rows=$resultado->num_rows;
				if($rows>0){
					while($row=$resultado->fetch_assoc()){
						if (utf8_decode($row['nombre'])==$_POST['sol']){
							echo $row['correo'];
							break;
						}
					}
				}else{
					$sql="SELECT nombre, correo_personal FROM franquicias WHERE nombre LIKE '%$nombre%'";
					$resultado=$mysqli2->query($sql);
					$rows=$resultado->num_rows;
					if($rows>0){
						while($row=$resultado->fetch_assoc()){
							if (utf8_decode($row['nombre'])==$_POST['sol']){
								echo $row['correo_personal'];
								break;
							}
						}
					}
				}
			}
			
		}
	}
	if($_POST['aux']==3){
		if(isset($_POST['sol'])){
			$aux=explode(" ",$_POST['sol']);
			$nombre=$aux[0];
			$sql="SELECT nombre, apellido, id FROM usuarios WHERE nombre LIKE '%$nombre%'";
			$resultado=$mysqli2->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				while($row=$resultado->fetch_assoc()){
					if (utf8_decode(($row['nombre'].' '.$row['apellido']))==$_POST['sol']){
						echo $row['id'];
						break;
					}
				}
			}else{
				$sql="SELECT nombre, id FROM tiendas WHERE nombre LIKE '%$nombre%'";
				$resultado=$mysqli2->query($sql);
				$rows=$resultado->num_rows;
				if($rows>0){
					while($row=$resultado->fetch_assoc()){
						if (utf8_decode($row['nombre'])==$_POST['sol']){
							echo $row['id'];
							break;
						}
					}
				}else{
					$sql="SELECT nombre, correo, id FROM franquicias WHERE nombre LIKE '%$nombre%'";
					$resultado=$mysqli2->query($sql);
					$rows=$resultado->num_rows;
					if($rows>0){
						while($row=$resultado->fetch_assoc()){
							if (utf8_decode($row['nombre'])==$_POST['sol']){
								echo $row['id'];
								break;
							}
						}
					}
				}
			}
		}
	}
	if($_POST['aux']==4){
		if(isset($_POST['sol'])){
			$aux=explode(" ",$_POST['sol']);
			$nombre=$aux[0];
			$sql="SELECT nombre, apellido, id FROM usuarios WHERE nombre LIKE '%$nombre%'";
			$resultado=$mysqli2->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				while($row=$resultado->fetch_assoc()){
					if (utf8_decode(($row['nombre'].' '.$row['apellido']))==$_POST['sol']){
						echo 1;
						break;
					}
				}
			}else{
				$sql="SELECT nombre, id FROM tiendas WHERE nombre LIKE '%$nombre%'";
				$resultado=$mysqli2->query($sql);
				$rows=$resultado->num_rows;
				if($rows>0){
					while($row=$resultado->fetch_assoc()){
						if (utf8_decode($row['nombre'])==$_POST['sol']){
							echo 2;
							break;
						}
					}
				}else{
					$sql="SELECT nombre, correo, id FROM franquicias WHERE nombre LIKE '%$nombre%'";
					$resultado=$mysqli2->query($sql);
					$rows=$resultado->num_rows;
					if($rows>0){
						while($row=$resultado->fetch_assoc()){
							if (utf8_decode($row['nombre'])==$_POST['sol']){
								echo 3;
								break;
							}
						}
					}
				}
			}
		}
	}
?>	