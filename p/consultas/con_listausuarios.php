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
		$query="SELECT * FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' ORDER BY id";
		///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
		if(isset($_POST['usuario'])){
			$q=$conexion->real_escape_string($_POST['usuario']);
			$query="SELECT * FROM usuarios WHERE 
				nombre LIKE '%".$q."%' OR
				apellido LIKE '%".$q."%' OR
				correo LIKE '%".$q."%' OR
				nivel LIKE '%".$q."%' WHERE idgerencia='".$_SESSION['gerencia']."'";
		}
		$buscardpto=$conexion->query($query);
		if ($buscardpto->num_rows > 0){
		?> 
			<form action="" method="GET" id="usuario">
		<?php
				$tabla.= 
					'<table border="1" width="100%">
							<tr>
								<td><strong>NOMBRE</strong></td>
								<td><strong>APELLIDO</strong></td>
								<td><strong>CORREO</strong></td>
								<td width="5%"></td>
								<td width="5%"></td>
							</tr>';
				$i=0;
				while($filadpto= $buscardpto->fetch_assoc()){
					$i++;
					if($filadpto['id']!=1){
						$tabla.=
							'<tr>
								<td>'.utf8_decode($filadpto['nombre']).'</td>
								<td>'.utf8_decode($filadpto['apellido']).'</td>
								<td>'.$filadpto['correo'].'</td>
								<td><button id="editar" name="editar" onclick="editar3('.$filadpto['id'].','.$i.')"><img src="../media/edit_rule.png" width="30" height="30" alt="editar" title="editar" /></button></td>';
								if(($filadpto['nombre'].' '.$filadpto['apellido'])=='Services Desk'){
									$tabla.='<td></td>';
								}else{
									$tabla.='<td><button id="eliminar" name="eliminar" onclick="eliminar3('.$filadpto['id'].')"><img src="../media/del01.png" width="30" height="30" alt="eliminar" title="eliminar" /></button></td>';
								}
							$tabla.='</tr>';
					}
				}
				$tabla.='</table><br/>';
				$tabla.='<table border="1" width="100%" id="editarusuario" name="editarusuario">
				</table>';
				$tabla.='<table border="1" width="100%">
					<tr>
						<td colspan="2"><strong>Nuevo Usuario</strong></td>
					</tr>
					<tr>
						<td width="15%"><label>Nombre: </label></td>
						<td><input type="text" id="nombreuser" name="nombreuser"  onkeypress="return validaletra(event)" /></td>
					</tr>
					<tr>
						<td><label>Apellido: </label></td>
						<td><input type="text" id="apellidouser" name="apellidouser"  onkeypress="return validaletra(event)"/></td>
					</tr>
					<tr>
						<td><label>Correo: </label></td>
						<td><input type="email" id="correouser" name="correouser" /></td>
					</tr>
					<tr>
						<td><label>Departamento: </label></td>';
						$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
						$resultado2=$conexion->query($sql2);
					$tabla.='<td>';
					$i=0;
					while($row2=$resultado2->fetch_assoc()){
						$i++;
						$tabla.='<input type="checkbox" name="dpto'.$i.'" id="dpto'.$i.'" value="'.$row2['id'].'">'."<label>".utf8_decode($row2['descripcion'])."</label>".'<br/>';	
					}
						$tabla.='</td><input type="hidden" id="i" name="i" value="'.$i.'"';
					$tabla.='</tr>
					<tr>
						<td><label>Nivel: </label></td>';
						$sql3="SELECT nivel, descripcion FROM niveles WHERE nivel>0";
						$resultado3=$conexion->query($sql3);
					$tabla.='<td>
						<select id="nivel" name="nivel" required >
							<option value="">-Seleccione-</option>';
					while($row3=$resultado3->fetch_assoc()){
						$tabla.='<option id="nivel" name="nivel" value="'.$row3["nivel"].'">'.utf8_decode($row3["descripcion"]).'</option>';	
					}
						$tabla.='</select></td>';
					$tabla.='</tr>
					<tr>
						<td><label>Activo: </label></td>
						<td>
							<select id="activo" name="activo">
								<option value="0">Sí</option>
								<option value="1">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label>Clave: </label></td>
						<td><input type="password" id="claveuser" name="claveuser" /></td>
					</tr>
					<tr>
						<td><label>Repetir Clave: </label></td>
						<td><input type="password" id="claveuser2" name="claveuser2"  /></td>
					</tr>
					<tr>
						<td colspan="2"><button id="crear" name="crear" onclick="crearuser()"><img src="../media/add_rule.png" width="30" height="30" alt="Crear" title="Crear Usuario" /></button></td>
					</tr>
				</table><br /><br />';
				?>
			</form><?php
		}else{
			$tabla="No se encontraron coincidencias con sus criterios de búsqueda.<br/><br/>";
		}
	echo $tabla;
?>