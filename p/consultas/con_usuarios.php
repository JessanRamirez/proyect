<?php
	include("con_verificarsession.php");
	include("../conexion.php");
	if($_POST['aux']==1){//Agregar un usuario
		$sql="SELECT * FROM usuarios WHERE correo='".$_POST['correo']."'";
		$resultado=$mysqli->query($sql);
		$rows=$resultado->num_rows;
		if($rows>0){
			echo "El usuario ya existe";
		}else{
			$sql2="INSERT INTO usuarios (nombre, apellido, correo, clave, nivel, idgerencia, activo) VALUES ('".mysqli_real_escape_string($mysqli,utf8_encode($_POST['nombre']))."', '".mysqli_real_escape_string($mysqli,utf8_encode($_POST['apellido']))."', '".$_POST['correo']."', '".sha1($_POST['clave'])."', '".$_POST['nivel']."', '".$_SESSION['gerencia']."', '".$_POST['activo']."')";
			$resultado2=$mysqli->query($sql2);
			echo "0";
		}
	}
	if($_POST['aux']==0){//Agregar un usuario
		$sql="INSERT INTO usuarios_dptos (usuario, dpto, idgerencia) VALUES ('".$_POST['correo']."', '".$_POST['dpto']."', '".$_SESSION['gerencia']."')";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==2){
		$sql="SELECT * FROM usuarios WHERE id='".$_POST['iduser']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();		
		$tabla='';
		echo '<tr>
				<td colspan="2"><strong>Modificar Usuario</strong></td>
			</tr>
			<tr>
				<td width="15%"><label>Nombre: </label></td>';
				if(($row['nombre'].' '.$row['apellido'])=='Services Desk'){
					echo '<td><input disabled type="text" id="nombreuser" name="nombreuser"value="'.utf8_decode($row['nombre']).'" onkeypress="return validaletra(event)" /></td>';
				}else{
					echo '<td><input type="text" id="nombreuser" name="nombreuser"value="'.utf8_decode($row['nombre']).'" onkeypress="return validaletra(event)" /></td>';
				}
			echo '</tr>
			<tr>
				<td><label>Apellido: </label></td>';
				if(($row['nombre'].' '.$row['apellido'])=='Services Desk'){
					echo '<td><input  disabled type="text" id="apellidouser" name="apellidouser"value="'.utf8_decode($row['apellido']).'" onkeypress="return validaletra(event)" /></td>';
				}else{
					echo '<td><input type="text" id="apellidouser" name="apellidouser"value="'.utf8_decode($row['apellido']).'" onkeypress="return validaletra(event)" /></td>';
				}
			echo '</tr>
			<tr>
				<td><label>Correo: </label></td>
				<td><input type="email" id="correouser" name="correouser" value="'.$row['correo'].'"/></td>
			</tr>
			<tr>
				<td><label>Departamento: </label></td>';
					$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
					$resultado2=$mysqli->query($sql2);
				echo '<td>';
				$i=0;
				while($row2=$resultado2->fetch_assoc()){
					$i++;
					$sql21="SELECT usuario, dpto FROM usuarios_dptos WHERE dpto='".$row2['id']."' AND usuario='".$row['correo']."'";
					$resultado21=$mysqli->query($sql21);
					$rows21=$resultado21->num_rows;
					if($rows21>0){
						if(($row['nombre'].' '.$row['apellido'])=='Services Desk'){
							echo '<input disabled type="checkbox" name="dpto2'.$i.'" id="dpto2'.$i.'" checked="checked" value="'.$row2['id'].'">'."<label>".utf8_decode($row2['descripcion'])."</label>".'<br/>';	
						}else{
							echo '<input type="checkbox" name="dpto2'.$i.'" id="dpto2'.$i.'" checked="checked" value="'.$row2['id'].'">'."<label>".$row2['descripcion']."</label>".'<br/>';	
						}
					}else{
						if(($row['nombre'].' '.$row['apellido'])=='Services Desk'){
							echo '<input disabled type="checkbox" name="dpto2'.$i.'" id="dpto2'.$i.'" value="'.$row2['id'].'">'."<label>".utf8_decode($row2['descripcion'])."</label>".'<br/>';	
						}else{
							echo '<input type="checkbox" name="dpto2'.$i.'" id="dpto2'.$i.'" value="'.$row2['id'].'">'."<label>".utf8_decode($row2['descripcion'])."</label>".'<br/>';	
						}
					}
				}
					echo '<input type="hidden" id="i2" name="i2" value="'.$i.'"/></td>';
			echo '</tr>
			<tr>
				<td><label>Nivel: </label></td>';
				if(($row['nombre'].' '.$row['apellido'])=='Services Desk'){
					$sql3="SELECT descripcion FROM niveles WHERE nivel='".$row["nivel"]."'";
					$resultado3=$mysqli->query($sql3);
					$row3=$resultado3->fetch_assoc();
					echo '<td><input  disabled type="text" id="nivel2" name="nivel2" value="'.utf8_decode($row3["descripcion"]).'" /><input  readonly type="hidden" id="nivel" name="nivel" value="'.$row["nivel"].'" /></td>';
				}else{
					$sql3="SELECT nivel, descripcion FROM niveles WHERE nivel>0";
					$resultado3=$mysqli->query($sql3);
					echo '<td>
						<select id="nivel" name="nivel" required style="width:99.7%">';
					while($row3=$resultado3->fetch_assoc()){
						echo '<option id="nivel" name="nivel" value="'.$row3["nivel"].'"';
						if($row['nivel']==utf8_encode($row3["nivel"])){
							echo 'selected';
						}
						echo ' >'.utf8_encode($row3["descripcion"]).'</option>';	
					}
				}
					echo '</select></td><
				</tr>
				<tr>
					<td><label>Activo: </label></td>';
					if(($row['nombre'].' '.$row['apellido'])=='Services Desk'){
						echo'<td><input  disabled type="text" id="activo2" name="activo2" value="Sí" /><input  readonly type="hidden" id="activo" name="activo" value="0" /></td>';
					}else{	
						echo'<td>
							<select id="activo" name="activo">';
								echo '<option value="0" ';
								if($row['activo']=='0'){
									echo 'selected';
								}
								echo '>Sí</option>';
								echo '<option value="1" ';
								if($row['activo']=='1'){
									echo 'selected';
								}
								echo '>No</option>
							</select>
						</td>';
					}
			echo'</tr>
			<tr>
				<td><label>Clave: </label></td>
				<td><input type="password" id="claveuser" name="claveuser"/></td>
			</tr>
			<tr>
				<td><label>Repetir Clave: </label></td>
				<td><input type="password" id="claveuser2" name="claveuser2"/></td>
			</tr>
			<tr>
				<td colspan="2"><button id="actualizar" name="actualizar" onclick="actualizar3('.$row['id'].')"><img src="../media/actualizar.png" width="30" height="30" alt="Crear" title="Actualizar Usuario" /></button>
			</tr>
		';
	}
	if($_POST['aux']==3){//Eliminar un usuario
		$sql="SELECT correo FROM usuarios WHERE id='".$_POST['usuario']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$sql2="DELETE FROM usuarios WHERE id='".$_POST['usuario']."'";
		$resultado2=$mysqli->query($sql2);
		$sql3="DELETE FROM usuarios_dptos WHERE usuario='".$row['correo']."'";
		$resultado3=$mysqli->query($sql3);
	}
	if($_POST['aux']==4){
		$sql="SELECT correo FROM usuarios WHERE id='".$_POST['iduser']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$correo=$row['correo'];
		if($_POST['clave']!=''){
			$sql2="UPDATE usuarios SET nombre='".mysqli_real_escape_string($mysqli,utf8_encode($_POST['nombre']))."', apellido='".mysqli_real_escape_string($mysqli,utf8_encode($_POST['apellido']))."', correo='".$_POST['correo']."', clave='".sha1($_POST['clave'])."', nivel='".$_POST['nivel']."', activo='".$_POST['activo']."' WHERE id='".$_POST['iduser']."'";
		}else{
			$sql2="UPDATE usuarios SET nombre='".mysqli_real_escape_string($mysqli,utf8_encode($_POST['nombre']))."', apellido='".mysqli_real_escape_string($mysqli,utf8_encode($_POST['apellido']))."', correo='".$_POST['correo']."', nivel='".$_POST['nivel']."', activo='".$_POST['activo']."' WHERE id='".$_POST['iduser']."'";
		}
		$resultado2=$mysqli->query($sql2);
		if($correo!=$_POST['apellido']){
			$sql3="DELETE FROM usuarios_dptos WHERE usuario='".$correo."'";
		}else{
			$sql3="DELETE FROM usuarios_dptos WHERE usuario='".$_POST['correo']."'";
		}
		$resultado3=$mysqli->query($sql3);
	}
	if($_POST['aux']==5){
		$sql="INSERT INTO usuarios_dptos (usuario, dpto, idgerencia) VALUES ('".$_POST['correo']."', '".$_POST['dpto']."', '".$_SESSION['gerencia']."')";
		$resultado=$mysqli->query($sql);
	}
?>