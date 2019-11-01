<?php
	include("con_verificarsession.php");
//LISTA DE DEPARTAMENTOS
		/////// CONEXIÓN A LA BASE DE DATOS /////////
		$conexion=new mysqli("localhost","services_desk","Tecno2018*","services_desk"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
		if(mysqli_connect_error()){
			echo 'Conexión Fallida: ', mysqli_connect_error();
			exit();
		}
		//////////////// VALORES INICIALES ///////////////////////
		$tabla="";
		$query="SELECT * FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' ORDER BY id";
		///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
		if(isset($_POST['area'])){
			$q=$conexion->real_escape_string($_POST['area']);
			$query="SELECT * FROM departamentos WHERE 
				id LIKE '%".$q."%' OR
				descripcion LIKE '%".$q."%' AND idgerencia='".$_SESSION['gerencia']."'";
		}
		$buscardpto=$conexion->query($query);
		if ($buscardpto->num_rows > 0){
		?> 
			<form action="" method="GET" id="dptos">
		<?php
				$tabla.= 
					'<table border="1" width="100%">
							<tr>
								<td><strong>ID</strong></td>
								<td><strong>DESCRIPCIÓN</strong></td>
								<td width="5%"></td>
								<td width="5%"></td>
							</tr>';
				$i=0;
				while($filadpto= $buscardpto->fetch_assoc()){
					$i++;
					$tabla.=
						'<tr>
							<td>'.$filadpto['id'].'</td>';
						if($filadpto['id']==1){
							$tabla.='<td><input type="text" readonly id="descripcion'.$i.'" name="descripcion'.$i.'" value="'.utf8_decode($filadpto['descripcion']).'" style="border:0; background-color:transparent; "/></td>
								<td></td>
								<td></td>';
						}else{
							$tabla.='<td><input type="text" readonly id="descripcion'.$i.'" name="descripcion'.$i.'" value="'.utf8_decode($filadpto['descripcion']).'" style="border:0; background-color:transparent; "/></td>
							<td><button id="editar" name="editar" onclick="editar2('.$filadpto['id'].','.$i.')"><img src="../media/edit_rule.png" width="30" height="30" alt="editar" title="editar" /></button></td>
							<td><button id="eliminar" name="eliminar" onclick="eliminar2('.$filadpto['id'].')"><img src="../media/del01.png" width="30" height="30" alt="eliminar" title="eliminar" /></button></td>';
						}
						$tabla.='</tr>';
				}
				$tabla.='</table><br/>';
				$tabla.='<table border="1" width="100%">
					<tr>
						<td><strong>Nuevo Departamento</strong></td>
						<td width="5%">+</td>
					</tr>
					<tr>
						<td><input type="text" id="dptonew" name="dptonew"/></td>
						<td><button id="agregar" name="agregar" onclick="agregar2(dptonew.value)"><img src="../media/add_rule.png" width="30" height="30" alt="agregar" title="agregar" /></button></td>
					</tr>
				</table><br /><br />';
				?>
			</form><?php
		}else{
			$tabla="No se encontraron coincidencias con sus criterios de búsqueda.<br/><br/>";
		}
	echo $tabla;
?>