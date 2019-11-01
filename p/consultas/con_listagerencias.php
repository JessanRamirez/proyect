<?php
	include("con_verificarsession.php");
//LISTA DE USUARIOS
		/////// CONEXIÓN A LA BASE DE DATOS /////////
		$conexion=new mysqli("localhost","services_desk","Tecno2018*","services_desk"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
		if(mysqli_connect_error()){
			echo 'Conexión Fallida: ', mysqli_connect_error();
			exit();
		}
		//////////////// VALORES INICIALES ///////////////////////
		$tabla="";
		//$query="SELECT * FROM alumnos ORDER BY id_alumno";
		$query="SELECT * FROM gerencias ORDER BY id";
		///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
		if(isset($_POST['gerencia'])){
			$q=$conexion->real_escape_string($_POST['gerencia']);
			$query="SELECT * FROM gerencias WHERE 
				id LIKE '%".$q."%' OR
				descripcion LIKE '%".$q."%'";
		}
		$buscargerencia=$conexion->query($query);
		if ($buscargerencia->num_rows > 0){
		?> 
			<form action="" method="GET" id="gerencias">
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
				while($filagerencia= $buscargerencia->fetch_assoc()){
					$i++;
					$tabla.=
						'<tr>
							<td>'.$filagerencia['id'].'</td>
							<td><input type="text" readonly id="descripcion'.$i.'" name="descripcion'.$i.'" value="'.utf8_decode($filagerencia['descripcion']).'" style="border:0; background-color:transparent; "/></td>
							<td><button id="editar" name="editar" onclick="editar('.$filagerencia['id'].','.$i.')"><img src="../media/edit_rule.png" width="30" height="30" alt="editar" title="editar" /></button></td>
							<td><button id="eliminar" name="eliminar" onclick="eliminar('.$filagerencia['id'].')"><img src="../media/del01.png" width="30" height="30" alt="eliminar" title="eliminar" /></button></td>
						</tr>';
				}
				$tabla.='</table><br/>';
				$tabla.='<table border="1" width="100%">
					<tr>
						<td><strong>Nueva Gerencia</strong></td>
						<td width="5%">+</td>
					</tr>
					<tr>
						<td><input type="text" id="gerencianew" name="gerencianew"/></td>
						<td><button id="agregar" name="agregar" onclick="agregar(gerencianew.value)"><img src="../media/add_rule.png" width="30" height="30" alt="agregar" title="agregar" /></button></td>
					</tr>
				</table><br /><br />';
				$tabla.='<table border="1" width="100%">
					<tr>';
				$sql2="SELECT idgerencia FROM usuarios WHERE id='1'";
				$resultado2=$conexion->query($sql2);
				$row2=$resultado2->fetch_assoc();	
				$sql3="SELECT descripcion FROM gerencias WHERE id='".$row2['idgerencia']."'";
				$resultado3=$conexion->query($sql3);
				$row3=$resultado3->fetch_assoc();
				$tabla.='<td><strong>Gerencia Asignada: '.utf8_decode($row3['descripcion']).' </strong></td>
						<td width="5%"></td>
					</tr>
					<tr>
						<td>
							<select id="gerencia" name="gerencia">';
							
							$sql4="SELECT * FROM gerencias";
							$resultado4=$conexion->query($sql4);
							while($row4=$resultado4->fetch_assoc()){
$tabla.='<option value="'.$row4["id"].'">'.utf8_decode($row4["descripcion"]).'</option>';	
							}
					$tabla.='</select></td>
						<td><button onclick="cambiar(gerencia.value)"><img src="../media/actualizar.png"/></button></td>
					</tr>
				</table>';
				?>
			</form><?php
		}else{
			$tabla="No se encontraron coincidencias con sus criterios de búsqueda.<br/><br/>";
			$tabla.='<table border="1" width="100%">
					<tr>
						<td><strong>Nueva Gerencia</strong></td>
						<td width="5%">+</td>
					</tr>
					<tr>
						<td><input type="text" id="gerencianew" name="gerencianew"/></td>
						<td><button id="agregar" name="agregar" onclick="agregar(gerencianew.value)"><img src="../media/add_rule.png" width="30" height="30" alt="agregar" title="agregar" /></button></td>
					</tr>
				</table><br /><br />';
		}
	echo $tabla;
?>