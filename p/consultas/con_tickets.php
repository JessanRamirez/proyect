<?php
	include("con_verificarsession.php");
	date_default_timezone_set('America/Caracas');
	function invertirfecha($fecha){
		$fecha2=$fecha[8].$fecha[9].'/'.$fecha[5].$fecha[6].'/'.$fecha[0].$fecha[1].$fecha[2].$fecha[3].' '.$fecha['11'].$fecha['12'].$fecha['13'].$fecha['14'].$fecha['15'];
		return $fecha2;
	}
	function invertirfecha2($fecha){
		$fecha2=$fecha[6].$fecha[7].$fecha[8].$fecha[9].'-'.$fecha[3].$fecha[4].'-'.$fecha[0].$fecha[1].' '.$fecha['11'].$fecha['12'].$fecha['13'].$fecha['14'].$fecha['15'];
		return $fecha2;
	}
	function reemplazar($texto){
		$palabra=$texto;
		$palabra=str_replace('ÃƒÂ¡','á',$palabra);
		$palabra=str_replace('ÃƒÂ­','í',$palabra);
		$palabra=str_replace('ÃƒÂ©','é',$palabra);
		$palabra=str_replace('ÃƒÂ³','ó',$palabra);
		$palabra=str_replace('ÃƒÂº','ú',$palabra);
		//$palabra=str_replace('ÃƒÂ','Á',$palabra);
		$palabra=str_replace('ÃƒÂ‰','É',$palabra);
		$palabra=str_replace('ÃƒÂ','Í',$palabra);
		$palabra=str_replace('ÃƒÂ“','Ó',$palabra);
		$palabra=str_replace('ÃƒÂš','Ú',$palabra);
		$palabra=str_replace('ÃƒÂ±','ñ',$palabra);
		$palabra=str_replace('ÃƒÂ‘','Ñ',$palabra);
		return $palabra;
	}
	include("../conexion.php");
	include("../../phpmailer/class.phpmailer.php");
	if($_POST['aux']==1){
		$sql="SELECT * FROM usuarios_dptos WHERE dpto='".$_POST['dpto']."' AND idgerencia='".$_SESSION['gerencia']."' AND usuario!='tecnologia@prosein.com' ";
		$resultado=$mysqli->query($sql);
		$tabla="";
		$rows=$resultado->num_rows;
		if($rows>0){
			$tabla.='<option value="">-Seleccionar-</option>
					<option value="Sin Asignar">-Sin Asignar-</option>';
			while($row=$resultado->fetch_assoc()){
				$sql2="SELECT id, nombre, apellido FROM usuarios WHERE correo='".$row['usuario']."' AND activo='0'";
				$resultado2=$mysqli->query($sql2);
				$rows2=$resultado2->num_rows;
				if($rows2>0){
					$row2=$resultado2->fetch_assoc();
					$tabla.='<option value="'.$row2['id'].'">'.utf8_decode($row2['nombre'].' '.$row2['apellido']).'</option>';
				}
			}
		}
		echo $tabla;
	}
	if($_POST['aux']==2){	
		$sql="SELECT * FROM categorias WHERE iddpto='".$_POST['dpto']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
		$tabla="";
		$tabla.="<option>-Seleccione-</option>";
		while($row=$resultado->fetch_assoc()){
			$tabla.='<option value="'.$row['id'].'">'.utf8_decode($row['descripcion']).'</option>';
		}
		echo $tabla;
	}
	if($_POST['aux']==3){
		$sql="SELECT * FROM sub_categorias WHERE idcategoria='".$_POST['cate']."'";
		$resultado=$mysqli->query($sql);
		$tabla="";
		$tabla.="<option>-Seleccione-</option>";
		while($row=$resultado->fetch_assoc()){
			$tabla.='<option value="'.$row['id'].'">'.utf8_decode($row['descripcion']).'</option>';
		}
		echo $tabla;
	}
	if($_POST['aux']==4){//Creación de ticket
		$fecha=$_POST['fechaestimada'];
		$fechacreacion=invertirfecha($_POST['fechacreacion']);
		$hora = date("H", strtotime($fecha));
		$fecha2=$fecha[6].$fecha[7].$fecha[8].$fecha[9].$fecha[5].$fecha[3].$fecha[4].$fecha[2].$fecha[0].$fecha[1].' '.$fecha[11].$fecha[12].$fecha[13].$fecha[14].$fecha[15];
		$sql0="SELECT nombre, apellido, correo FROM usuarios WHERE id='".$_POST['asignar']."'";
		$resultado0=$mysqli->query($sql0);
		$row0=$resultado0->fetch_assoc();
		$asignado=utf8_decode($row0['nombre'].' '.$row0['apellido']);
		$sql="SELECT max(id) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$id=$row['max(id)']+1;
		switch($_POST['estado']){
			case 'Pendiente por Asignar':
				$estado=0;
				break;
			case 'Asignado':
				$estado=1;
				break;
			case 'En Proceso':
				$estado=2;
				break;
			case 'En Manos del Cliente':
				$estado=3;
				break;
			case 'En Espera por Terceros':
				$estado=4;
				break;
			case 'Anulado':
				$estado=5;
				break;
			case 'Falsa Alarma':
				$estado=6;
				break;
			case 'Cerrado':
				$estado=7;
				break;
			default:
				$estado=0;
				break;				
		}
		$sql2="INSERT INTO tickets (id, idgerencia, clasificacion, recepcion, estado, prioridad, oficina, opc, idsol, solicitante, correo, titulo, departamento, categoria, subcategoria, idasignacion, asignacion, fechacreacion, fechaestimada, fechacierre, creadopor) VALUES ('$id', '".$_SESSION['gerencia']."', '".$_POST['clasificacion']."', '".utf8_encode($_POST['recepcion'])."', '".$_POST['estado']."', '".$_POST['prioridad']."', '".$_POST['oficina']."', '".trim($_POST['opc'])."', '".trim($_POST['idsol'])."', '".utf8_encode($_POST['solicitante'])."', '".$_POST['correo']."', '".utf8_encode($_POST['titulo'])."', '".$_POST['departamento']."', '".$_POST['categoria']."', '".$_POST['subcategoria']."', '".$_POST['asignar']."', '".$asignado."', '".$_POST['fechacreacion']."', '".$fecha2."', '".$_POST['fechacierre']."', '".$_SESSION['correo']."')";
		$resultado2=$mysqli->query($sql2);
		$sql3="SELECT max(id) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."'";
		$resultado3=$mysqli->query($sql3);
		$row3=$resultado3->fetch_assoc();
		$sql4="INSERT INTO tickets_detalles (idticket, idgerencia, estado, prioridad, clasificacion, asignado, departamento, categoria, subcategoria, comentarios, fecha) VALUES ('".$row3['max(id)']."', '".$_SESSION['gerencia']."', '".$_POST['estado']."', '".$_POST['prioridad']."', '".$_POST['clasificacion']."', '".utf8_encode($asignado)."', '".$_POST['departamento']."', '".$_POST['categoria']."', '".$_POST['subcategoria']."', '".utf8_encode($_POST['comentario'])."', '".$_POST['fechacreacion']."')";
		$resultado4=$mysqli->query($sql4);
		$sql5="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
		$resultado5=$mysqli->query($sql5);
		$row5=$resultado5->fetch_assoc();
		$fechacreacion=invertirfecha($_POST['fechacreacion']);
		$fechaestimada=invertirfecha($fecha2);
		$sql8="SELECT * FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND nivel='1' AND activo='0'";
		$resultado8=$mysqli->query($sql8);
		while($row8=$resultado8->fetch_assoc()){
			$nombre=$row8['nombre'].' '.$row8['apellido'];
			$sql81="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '".$row3['max(id)']."', '$nombre', '".$row8['correo']."', '1', '0','$estado')";
			$resultado81=$mysqli->query($sql81);	
		}
		$sql81="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '".$row3['max(id)']."', 'Admin Admin', 'tecnologia@prosein.com', '1', '0','$estado')";
		$resultado81=$mysqli->query($sql81);
		$sql81="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '".$row3['max(id)']."', 'Services Desk', 'servicesdesk@prosein.com', '1', '0','$estado')";
		$resultado81=$mysqli->query($sql81);	
		if($_POST['asignar']!='Sin Asignar'){
			$sql9="SELECT correo FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row3['max(id)']."' AND correo='".$row0['correo']."'";
			$resultado9=$mysqli->query($sql9);
			$rows9=$resultado9->num_rows;
			if($rows9>0){
			}else{
				$sql10="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '".$row3['max(id)']."', '".utf8_encode($asignado)."', '".$row0['correo']."', '1', '0','$estado')";
				$resultado10=$mysqli->query($sql10);
			}
		}else{
			$sql10="SELECT usuario FROM usuarios_dptos WHERE dpto='".$_POST['departamento']."' AND idgerencia='".$_SESSION['gerencia']."'";
			$resultado10=$mysqli->query($sql10);
			while($row10=$resultado10->fetch_assoc()){
				$sql11="SELECT nombre, apellido, correo FROM usuarios WHERE nivel='2' AND idgerencia='".$_SESSION['gerencia']."' AND activo='0' AND correo='".$row10['usuario']."'";
				$resultado11=$mysqli->query($sql11);
				$rows11=$resultado11->num_rows;
				if($rows11>0){
					$row11=$resultado11->fecth_assoc();
					$nombre=$row11['nombre'].' '.$row11['apellido'];
					$sql12="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '".$row3['max(id)']."', '".utf8_encode($nombre)."', '".$row11['correo']."', '1', '0','$estado')";
					$resultado12=$mysqli->query($sql12);
				}
			}
		}
		if($_POST['estado']=='Cerrado'){
			$descripcion=$row5['descripcion'];
			$descripcion=reemplazar($descripcion);
			$asignado=utf8_decode($asignado);
			$cuerpo='
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				</head>
				<body>';
				if($hora<12){
					$cuerpo='<p style="color:#F00">Buen día, En relación a su solicitud se ha Cerrado temporalmente el ticket N°: '.$row3['max(id)'].'.<br/>
						El mismo poseerá dicho estatus por 3 días hábiles para su verificación o certificación, de existir inconformidad notificarnos para reabrir el ticket, de no poseerse en este tiempo observación alguna pasará a cierre definitivo.
</p>';
				}else{
					$cuerpo='<p style="color:#F00">Buenas tardes, En relación a su solicitud se ha Cerrado temporalmente el ticket N°: '.$row3['max(id)'].'.<br/>
						El mismo poseerá dicho estatus por 3 días hábiles para su verificación o certificación, de existir inconformidad notificarnos para reabrir el ticket, de no poseerse en este tiempo observación alguna pasará a cierre definitivo.
</p>';
				}
	
					$cuerpo.='
					</p>
					<p style="color:#00F">
					Gracias por comunicarse a Services Desk,  el único punto de contacto para el manejo de solicitudes.
					</p>
					<p><strong>DETALLES DEL TICKET</strong></p>
					<p><strong>Solicitante: </strong>'.utf8_decode($_POST['solicitante']).'</p>
					<p><strong>Correo solicitante: </strong>'.$_POST['correo'].'</p>
					<p><strong>Título: </strong>'.utf8_decode($_POST['titulo']).'</p>
					<p><strong>Detalles: </strong>'.utf8_decode($_POST['comentario']).'</p>
					<p><strong>Fecha de creación:  </strong>'.$fechacreacion.'</p>
					<p><strong>Fecha estimada de cierre:  </strong>'.$fechaestimada.'</p>
					<p><strong>Estado:  </strong>'.$_POST['estado'].'</p>';
					if($_POST['asignar']!='Sin Asignar'){
						$cuerpo.='
						<p><strong>Atendido por: </strong>'.$asignado.'</p>
						<p><strong>Correo: </strong>'.$row0['correo'].'</p>';
					}
					$cuerpo.='<br />
					<p><img src="http://prsbousa.eastus2.cloudapp.azure.com/prosein/firma.png" width="464" height="214" /></p>
				</body>
			</html>';
		}else{
			$descripcion=$row5['descripcion'];
			$descripcion=reemplazar($descripcion);
			$asignado=utf8_decode($asignado);
			$cuerpo='
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				</head>
				<body>';
				if($hora<12){
					$cuerpo.='<p>Buen día, <br/>';
				}else{
					$cuerpo.='<p>Buenas tardes, <br/>';
				}
	
					$cuerpo.='En relación a su solicitud se ha generado el ticket N°:'.$row3['max(id)'].', Él mismo será atendido por  personal de la '.$descripcion.'.
					</p>
					<p style="color:#00F">
					Gracias por comunicarse a Services Desk,  el único punto de contacto para el manejo de solicitudes.
					</p>
					<p><strong>DETALLES DEL TICKET</strong></p>
					<p><strong>Solicitante: </strong>'.utf8_decode($_POST['solicitante']).'</p>
					<p><strong>Correo solicitante: </strong>'.$_POST['correo'].'</p>
					<p><strong>Título: </strong>'.utf8_decode($_POST['titulo']).'</p>
					<p><strong>Detalles: </strong>'.utf8_decode($_POST['comentario']).'</p>
					<p><strong>Fecha de creación:  </strong>'.$fechacreacion.'</p>
					<p><strong>Fecha estimada de cierre:  </strong>'.$fechaestimada.'</p>
					<p><strong>Estado:  </strong>'.$_POST['estado'].'</p>';					
					if($_POST['asignar']!='Sin Asignar'){
						$cuerpo.='
						<p><strong>Atendido por: </strong>'.$asignado.'</p>
						<p><strong>Correo: </strong>'.$row0['correo'].'</p>';
					}
					$cuerpo.='<br />
					<p><img src="http://prsbousa.eastus2.cloudapp.azure.com/prosein/firma.png" width="464" height="214" /></p>
				</body>
			</html>';
		}
		$titulo=utf8_encode($_POST['titulo']);
		$cuerpo=utf8_encode($cuerpo);
		// PHPMAILER
		$mail = new PHPMailer();
		$mail->IsSMTP(); 					// set mailer to use  SMTP
		$mail->Host = "smtp.office365.com"; 			// specify main and backup server
		$mail->Port	  = "587";             			// set the SMTP server port
		$mail->SMTPSecure = "tls";				// set the encryption. - mbilotti.
		$mail->SMTPAuth = "true";				// turn on SMTP authentication
		$mail->CharSet = 'UTF-8';
		$mail->Username = "servicesdesk@prosein.com";
		$mail->Password = "Prosein_2019*";
		$mail->From = "servicesdesk@prosein.com";
		$mail->FromName = "Sistema de Tickets De ".utf8_decode($row5['descripcion'])."";
		$mail->AddAddress ("".$_POST['correo']."", "".$_POST['solicitante']."");                 		 // name is optional
		$mail->addCC("servicesdesk@prosein.com", "Services Desk");
		if($_POST['asignar']!='Sin Asignar'){
			$mail->addCC($row0['correo'],$asignado);
		}else{
			$sql7="SELECT usuarios_dptos.usuario as correo, usuarios.nombre as nombre, usuarios.apellido as apellido FROM usuarios_dptos INNER JOIN usuarios ON usuarios.correo=usuarios_dptos.usuario WHERE usuarios_dptos.idgerencia='".$_SESSION['gerencia']."' AND usuarios_dptos.dpto='".$_POST['departamento']."' AND usuarios.nivel>1";
			$resultado7=$mysqli->query($sql7);
			$rows7=$resultado7->num_rows;
			if($rows7>0){
				while($row7=$resultado7->fetch_assoc()){
					$mail->addCC($row7['correo'],$row7['nombre'].' '.$row7['apellido']);
				}
			}
		}
		$mail->WordWrap = 50; // set word wrap to 50 characters
		$mail->IsHTML(true); // set email format to HTML
		$mail->Subject = "Ticket ".$row3['max(id)']." De la ".utf8_decode($row5['descripcion'])." - ".utf8_decode($titulo)."";
		$mail->Body    = $cuerpo;
		/*if(!$mail->Send()) {
			echo("Fall&oacute; env&iacute;o mail confirmaci&oacute;n");
			echo "Error: " . $mail->ErrorInfo;
		}else{
			echo "Enviado";
		}*/
	}
	if($_POST['aux']==5){//Bandeja
		if(isset($_SESSION['nivel']) and isset($_SESSION['gerencia'])){
			switch ($_SESSION['nivel']){
				case 0:
					$sql="SELECT * FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' order by id desc";
					$resultado=$mysqli->query($sql);
					$tabla="<div id='alertas' style='display:none;'></div>";
					$tabla.='<p style="text-align:center"><strong>Bandeja de Tickets</strong></p>';
					$tabla.='<table width="100%" border="1" id="filtro1">
							<tr>
								<td colspan="2"><button id="mostraravanzado" onclick="mostraravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ver filtro"/></></td>
							</tr>
						</table>
					<table id="carga" style="display:none;"><tr><td><img src="../media/ajax-loader.gif" "/></td></tr></table>';
					$tabla.='<table width="100%" border="1" id="filtro2" style="display:none;">
					<tr>
						<td colspan="2"><button id="ocultaravanzado" onclick="ocultaravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ocultar filtro"/></></td>
					</tr>
					<tr>
						<td width="20%">Titulo:</td>
						<td><input type="text" id="titulo2" onkeypress="return validaletra(event)" placeholder="Cambio de clave.."/></td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td>
							<select id="estado2">
								<option value="">-Seleccione-</option>
								<option value="Pendiente por Asignar">Pendiente por Asignar</option>
								<option value="Asignado">Asignado</option>
								<option value="En Proceso">En Proceso</option>
								<option value="Cerrado">Cerrado</option>
								<option value="En Manos del Cliente">En Manos del Cliente</option>
								<option value="En Espera por Terceros">En Espera por Terceros</option>
								<option value="Anulado">Anulado</option>
								<option value="Falsa Alarma">Falsa Alarma</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Solicitante:</td>
						<td><input type="text" id="solicitante3" onkeypress="return validaletra(event)" placeholder="Tecnologia"/></td>
					</tr>
					<tr>
						<td>Departamento:</td>
						<td>
							<select id="departamento2" onchange="cambiodepartamento2(this.value)">
							<option value="">-Seleccione-</option>';
							$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' ORDER BY descripcion ASC";
							$resultado2=$mysqli->query($sql2);
							while($row2=$resultado2->fetch_assoc()){
								$tabla.='<option value="'. $row2['id'].'" >'.utf8_decode($row2['descripcion']).'</option>';
							}
							$tabla.='</select>
						</td>
					</tr>
					<tr>
						<td>Asignado:</td>
						<td>
							<select id="asignado2" required>
								<option></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Fecha Desde:</td>
						<td><input type="date" id="fechadesde" style="width:100%"/></td>
					</tr>
					<tr>
						<td>Fecha Hasta:</td>
						<td><input type="date" id="fechahasta" style="width:100%"/></td>
					</tr>
					<tr>
						<td colspan="2"><button id="filtrar" onclick="filtrar(0)"><img src="../media/actualizar.png" width="16" height="16" title="Aplicar Filtro" /></button></td>
					</tr>
					';	
					$tabla.='<table width="100%" border="1" id="tickets">
							<tr>
								<td width="3%"></td>
								<td><strong>ID</strong></td>
								<td><strong>Título</strong></td>
								<td><strong>Solicitante</strong></td>
								<td><strong>Estado</strong></td>
								<td width="5%"></td>
								<td width="5%"></td>
								<td width="5%"></td>
							</tr>';
					$rows=$resultado->num_rows;
					if($rows>0){		
						while($row=$resultado->fetch_assoc()){
							$tabla.='<tr>';
							$sql3="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row['id']."' AND correo='".$_SESSION['correo']."'";
							$resultado3=$mysqli->query($sql3);
							$row3=$resultado3->fetch_assoc();
							switch($row3['estado']){
								case '':
									$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
									break;
								case 0:
									if(($row3['aviso']==1)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
									}else{
										if(($row3['aviso']==2)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
										}
									}
									break;
								case 1:
									if(($row3['aviso']==1)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
									}else{
										if(($row3['aviso']==2)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
										}
									}
									break;
								case 2:
									if(($row3['aviso']==1)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
									}else{
										if(($row3['aviso']==2)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
										}
									}
									break;
								case 3:
									if(($row3['aviso']==1)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
									}else{
										if(($row3['aviso']==2)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
										}
									}
									break;
								case 4:
									if(($row3['aviso']==1)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
									}else{
										if(($row3['aviso']==2)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
										}
									}
									break;
								case 5:
									$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
									break;
								case 6:
									$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
									break;
								case 7:
									$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
									break;	
								case 8:
									if(($row3['aviso']==1)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
									}else{
										$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
									}
									break;
								case 9:
									if(($row3['aviso']==2)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
									}else{
										$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
									}
									break;
								case 10:
									if(($row3['aviso']==3)&&($row3['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row['id'].')"><img src="../media/alerta3.png"  /></button></td>';
									}else{
										$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
									}
									break;	
								case 11:
									$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
									break;					
							}
							$titulo=reemplazar($row['titulo']);
							$solicitante=reemplazar($row['solicitante']);
							$tabla.='
									<td>'.$row['id'].'</td>
									<td>'.$titulo.'</td>
									<td>'.$solicitante.'</td>
									<td>'.$row['estado'].'</td>
									<td><button id="info" name="info" onclick="info('.$row['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
									<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
									<td><button id="hoja" name="hoja" onclick="hoja_servicio('.$row['id'].')"><img src="../media/pdf.png" width="30" height="30" alt="visualizar" title="Generar Hoja de Servicio" /></button></td>
								</tr>';
						}
					}else{
						$tabla.='<tr>
								<td colspan="4">No hay tickets creados.</td>
							</tr>';
					}
					$tabla.='</table>';
					echo utf8_encode($tabla);
					break;
				case 1:
					$sql="SELECT * FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' order by id desc";
					$resultado=$mysqli->query($sql);
					$tabla="<div id='alertas' style='display:none;'></div>";
					$tabla.='<p style="text-align:center"><strong>Bandeja de Tickets</strong></p>';
					$tabla.='<table width="100%" border="1" id="filtro1">
							<tr>
								<td colspan="2"><button id="mostraravanzado" onclick="mostraravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ver filtro"/></></td>
							</tr>
						</table>
					<table id="carga" style="display:none;"><tr><td><img src="../media/ajax-loader.gif" "/></td></tr></table>';
					$tabla.='<table width="100%" border="1" id="filtro2" style="display:none;">
					<tr>
						<td colspan="2"><button id="ocultaravanzado" onclick="ocultaravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ocultar filtro"/></></td>
					</tr>
					<tr>
						<td width="20%">Título:</td>
						<td><input type="text" id="titulo2" onkeypress="return validaletra(event)" placeholder="Cambio de clave.."/></td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td>
							<select id="estado2">
								<option value="">-Seleccione-</option>
								<option value="Pendiente por Asignar">Pendiente por Asignar</option>
								<option value="Asignado">Asignado</option>
								<option value="En Proceso">En Proceso</option>
								<option value="Cerrado">Cerrado</option>
								<option value="En Manos del Cliente">En Manos del Cliente</option>
								<option value="En Espera por Terceros">En Espera por Terceros</option>
								<option value="Anulado">Anulado</option>
								<option value="Falsa Alarma">Falsa Alarma</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Solicitante:</td>
						<td><input type="text" id="solicitante3" onkeypress="return validaletra(event)" placeholder="Tecnologia"/></td>
					</tr>
					<tr>
						<td>Departamento:</td>
						<td>
							<select id="departamento2" onchange="cambiodepartamento2(this.value)">
							<option value="">-Seleccione-</option>';
							$sql2="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' ORDER BY descripcion ASC";
							$resultado2=$mysqli->query($sql2);
							while($row2=$resultado2->fetch_assoc()){
								$tabla.='<option value="'. $row2['id'].'" >'.utf8_decode($row2['descripcion']).'</option>';
							}
							$tabla.='</select>
						</td>
					</tr>
					<tr>
						<td>Asignado:</td>
						<td>
							<select id="asignado2" required>
								<option></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Fecha Desde:</td>
						<td><input type="date" id="fechadesde" style="width:100%"/></td>
					</tr>
					<tr>
						<td>Fecha Hasta:</td>
						<td><input type="date" id="fechahasta" style="width:100%"/></td>
					</tr>
					<tr>
						<td colspan="2"><button id="filtrar" onclick="filtrar(0)"><img src="../media/actualizar.png" width="16" height="16" title="Aplicar Filtro" /></button></td>
					</tr>
					';	
					$tabla.='<table width="100%" border="1" id="tickets">
							<tr>
								<td width="3%"></td>
								<td><strong>ID</strong></td>
								<td><strong>Título</strong></td>
								<td><strong>Solicitante</strong></td>
								<td><strong>Estado</strong></td>
								<td width="5%"></td>
								<td width="5%"></td>
								<td width="5%"></td>
							</tr>';
					$rows=$resultado->num_rows;
					if($rows>0){		
						while($row=$resultado->fetch_assoc()){
							$tabla.='<tr>';
							$sql3="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row['id']."' AND correo='".$_SESSION['correo']."'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
								$row3=$resultado3->fetch_assoc();
								switch($row3['estado']){
									case '':
										$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
										break;
									case 0:
										if(($row3['aviso']==1)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row3['aviso']==2)&&($row3['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
											}
										}
										break;
									case 1:
										if(($row3['aviso']==1)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row3['aviso']==2)&&($row3['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
											}
										}
										break;
									case 2:
										if(($row3['aviso']==1)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row3['aviso']==2)&&($row3['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
											}
										}
										break;
									case 3:
										if(($row3['aviso']==1)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row3['aviso']==2)&&($row3['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
											}
										}
										break;
									case 4:
										if(($row3['aviso']==1)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row3['aviso']==2)&&($row3['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
											}
										}
										break;
									case 5:
										$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
										break;
									case 6:
										$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
										break;
									case 7:
										$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
										break;	
									case 8:
										if(($row3['aviso']==1)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
										}
										break;
									case 9:
										if(($row3['aviso']==2)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
										}
										break;
									case 10:
										if(($row3['aviso']==3)&&($row3['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row['id'].')"><img src="../media/alerta3.png"  /></button></td>';
										}else{
											$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
										}
										break;	
									case 11:
										$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
										break;					
								}
							}else{
								switch($row['estado']){
									case 'Pendiente por Asignar':
										$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
										break;
									case 'Asignado':
										$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
										break;
									case 'En Proceso':
										$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
										break;
									case 'En Espera por Terceros':
										$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
										break;
									case 'En Manos Del Cliente':
										$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
										break;
									case 'Cerrado':
										$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
										break;
									case 'Falsa Alarma':
										$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
										break;
									case 'Anulado':
										$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
										break;
								}
							}
							$titulo=reemplazar($row['titulo']);
							$solicitante=reemplazar($row['solicitante']);
							$tabla.='
									<td>'.$row['id'].'</td>
									<td>'.$titulo.'</td>
									<td>'.$solicitante.'</td>
									<td>'.$row['estado'].'</td>
									<td><button id="info" name="info" onclick="info('.$row['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
									<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
									<td><button id="hoja" name="hoja" onclick="hoja_servicio('.$row['id'].')"><img src="../media/pdf.png" width="30" height="30" alt="visualizar" title="Generar Hoja de Servicio" /></button></td>
								</tr>';
						}
					}else{
						$tabla.='<tr>
								<td colspan="4">No hay tickets creados.</td>
							</tr>';
					}
					$tabla.='</table>';
					echo utf8_encode($tabla);
					break;
				case 2:
					$sql="SELECT * FROM usuarios_dptos WHERE usuario='".$_SESSION['correo']."'";
					$resultado=$mysqli->query($sql);
					$tabla="";
					$tabla.='<p style="text-align:center"><strong>Bandeja de Tickets</strong></p>';
					$tabla.='<table width="100%" border="1" id="filtro1">
							<tr>
								<td colspan="2"><button id="mostraravanzado" onclick="mostraravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ver filtro"/></></td>
							</tr>
						</table>
					<table id="carga" style="display:none;"><tr><td><img src="../media/ajax-loader.gif" "/></td></tr></table>';
					$tabla.='<table width="100%" border="1" id="filtro2" style="display:none;">
					<tr>
						<td colspan="2"><button id="ocultaravanzado" onclick="ocultaravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ocultar filtro"/></></td>
					</tr>
					<tr>
						<td width="20%">Titulo:</td>
						<td><input type="text" id="titulo2" onkeypress="return validaletra(event)" placeholder="Cambio de clave.."/></td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td>
							<select id="estado2">
								<option value="">-Seleccione-</option>
								<option value="Pendiente por Asignar">Pendiente por Asignar</option>
								<option value="Asignado">Asignado</option>
								<option value="En Proceso">En Proceso</option>
								<option value="Cerrado">Cerrado</option>
								<option value="En Manos del Cliente">En Manos del Cliente</option>
								<option value="En Espera por Terceros">En Espera por Terceros</option>
								<option value="Anulado">Anulado</option>
								<option value="Falsa Alarma">Falsa Alarma</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Solicitante:</td>
						<td><input type="text" id="solicitante3" onkeypress="return validaletra(event)" placeholder="Tecnologia"/></td>
					</tr>
					<tr>
						<td>Asignado:</td>
						<td>
							<select id="asignado2">
								<option value="">-Seleccione-</option>';
							$i=0;
							while($row=$resultado->fetch_assoc()){
								$sql2="SELECT * FROM usuarios_dptos WHERE dpto='".$row['dpto']."'";
								$resultado2=$mysqli->query($sql2);
								while($row2=$resultado2->fetch_assoc()){
									$aux=1;
									if($i==0){
										$usuarios[$i]=$row2['usuario'];
										$sql3="SELECT nombre, apellido FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$row2['usuario']."'";
										$resultado3=$mysqli->query($sql3);
										$row3=$resultado3->fetch_assoc();
										$usuarios2[$i]=utf8_decode($row3['nombre'].' '.$row3['apellido']);
										$i++;
									}else{
										for($x=0;$x<$i;$x++){
											if($usuarios[$x]==$row2['usuario']){
												$aux=0;
											}
										}
										if($aux==1){
											$usuarios[$i]=$row2['usuario'];
											$sql3="SELECT nombre, apellido FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$row2['usuario']."'";
											$resultado3=$mysqli->query($sql3);
											$row3=$resultado3->fetch_assoc();
											$usuarios2[$i]=utf8_decode($row3['nombre'].' '.$row3['apellido']);
											$i++;
										}
									}
								}
							}
							for($x=0;$x<$i;$x++){
								$tabla.='<option value="'.$usuarios[$x].'">'.$usuarios2[$x].'</option>';	
							}
							$tabla.='</select>
						</td>
					</tr>
					<tr>
						<td>Fecha Desde:</td>
						<td><input type="date" id="fechadesde" style="width:100%"/></td>
					</tr>
					<tr>
						<td>Fecha Hasta:</td>
						<td><input type="date" id="fechahasta" style="width:100%"/></td>
					</tr>
					<tr>
						<td colspan="2"><button id="filtrar" onclick="filtrar(2)"><img src="../media/actualizar.png" width="16" height="16" title="Aplicar Filtro" /></button></td>
					</tr>
					';	
					$resultado=$mysqli->query($sql);
					$i=0;
					while($row=$resultado->fetch_assoc()){
						$i++;
						$sql2="SELECT descripcion FROM departamentos WHERE id='".$row['dpto']."'";
						$resultado2=$mysqli->query($sql2);
						$row2=$resultado2->fetch_assoc();
						$tabla.='
						<table width="100%" border="1" id="tickets'.$i.'">
							<tr>
								<td colspan="8"><p style="text-align:center"><strong>'.utf8_decode($row2['descripcion']).'</strong></p></td>
							</tr>
							<tr>
								<td width="3%"></td>
								<td><strong>ID</strong></td>
								<td><strong>Título</strong></td>
								<td><strong>Solicitante</strong></td>
								<td><strong>Estado</strong></td>
								<td width="5%"></td>
								<td width="5%"></td>
								<td width="5%"></td>
							</tr>';
						$sql3="SELECT * FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['dpto']."' order by id desc";
						$resultado3=$mysqli->query($sql3);
						$rows3=$resultado3->num_rows;
						if($rows3>0){
							while($row3=$resultado3->fetch_assoc()){
								$tabla.='<tr>';
								$sql4="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row3['id']."' AND correo='".$_SESSION['correo']."'";
								$resultado4=$mysqli->query($sql4);
								$rows4=$resultado4->num_rows;
								if($rows4>0){
									$row4=$resultado4->fetch_assoc();
									switch($row4['estado']){
										case 0:
											if(($row4['aviso']==1)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
											}else{
												if(($row4['aviso']==2)&&($row4['valor']==1)){
													$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
												}else{
													$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
												}
											}
											break;
										case 1:
											if(($row4['aviso']==1)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
											}else{
												if(($row4['aviso']==2)&&($row4['valor']==1)){
													$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
												}else{
													$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
												}
											}
											break;
										case 2:
											if(($row4['aviso']==1)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
											}else{
												if(($row4['aviso']==2)&&($row4['valor']==1)){
													$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
												}else{
													$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
												}
											}
											break;
										case 3:
											if(($row4['aviso']==1)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
											}else{
												if(($row4['aviso']==2)&&($row4['valor']==1)){
													$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
												}else{
													$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
												}
											}
											break;
										case 4:
											if(($row4['aviso']==1)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
											}else{
												if(($row4['aviso']==2)&&($row4['valor']==1)){
													$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
												}else{
													$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
												}
											}
											break;
										case 5:
											$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
											break;
										case 6:
											$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
											break;
										case 7:
											$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
											break;	
										case 8:
											if(($row4['aviso']==1)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
											}else{
												$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
											}
											break;
										case 9:
											if(($row4['aviso']==2)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
											}
											break;
										case 10:
											if(($row4['aviso']==3)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row3['id'].')"><img src="../media/alerta3.png"  /></button></td>';
											}else{
												$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
											}
											break;	
										case 11:
											$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
											break;	
										default:
											$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
											break;				
									}
								}else{
									switch($row3['estado']){
										case 'Pendiente por Asignar':
											$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
											break;
										case 'Asignado':
											$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
											break;
										case 'En Proceso':
											$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
											break;
										case 'En Espera por Terceros':
											$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
											break;
										case 'En Manos Del Cliente':
											$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
											break;
										case 'Cerrado':
											$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
											break;
										case 'Falsa Alarma':
											$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
											break;
										case 'Anulado':
											$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
											break;
									}
								}
							$titulo=reemplazar($row3['titulo']);
							$solicitante=reemplazar($row3['solicitante']);
							$tabla.='<td>'.$row3['id'].'</td>
									<td>'.$titulo.'</td>
									<td>'.$solicitante.'</td>
									<td>'.$row3['estado'].'</td>
									<td><button id="info" name="info" onclick="info('.$row3['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
									<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row3['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
									<td><button id="hoja" name="hoja" onclick="hoja_servicio('.$row['id'].')"><img src="../media/pdf.png" width="30" height="30" alt="visualizar" title="Generar Hoja de Servicio" /></button></td>
								</tr>';
							}
						}else{
							$tabla.='<tr>
								<td colspan="8">No hay tickets creados.</td>
							</tr>';
						}
					}
					$tabla.='</table><input type="hidden" id="cantdptos" value="'.$i.'">';
					echo utf8_encode($tabla);
					break;
				case 3:
					$sql="SELECT * FROM usuarios_dptos WHERE usuario='".$_SESSION['correo']."'";
					$resultado=$mysqli->query($sql);
					$rows=$resultado->num_rows;
					$tabla="";
					$tabla.='<p style="text-align:center"><strong>Bandeja de Tickets</strong></p>';
					$tabla.='<table width="100%" border="1" id="filtro1">
							<tr>
								<td colspan="2"><button id="mostraravanzado" onclick="mostraravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ver filtro"/></></td>
							</tr>
						</table>
					<table id="carga" style="display:none;"><tr><td><img src="../media/ajax-loader.gif" "/></td></tr></table>';
					$tabla.='<table width="100%" border="1" id="filtro2" style="display:none;">
						<tr>
							<td colspan="2"><button id="ocultaravanzado" onclick="ocultaravanzado()"><img src="../media/lupa.png" width="16" height="16" title="Ocultar filtro"/></></td>
						</tr>
						<tr>
							<td width="20%">Titulo:</td>
							<td><input type="text" id="titulo2" onkeypress="return validaletra(event)" placeholder="Cambio de clave.."/></td>
						</tr>
						<tr>
							<td>Estado:</td>
							<td>
								<select id="estado2">
									<option value="">-Seleccione-</option>
									<option value="Pendiente por Asignar">Pendiente por Asignar</option>
									<option value="Asignado">Asignado</option>
									<option value="En Proceso">En Proceso</option>
									<option value="Cerrado">Cerrado</option>
									<option value="En Manos del Cliente">En Manos del Cliente</option>
									<option value="En Espera por Terceros">En Espera por Terceros</option>
									<option value="Anulado">Anulado</option>
									<option value="Falsa Alarma">Falsa Alarma</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Solicitante:</td>
							<td><input type="text" id="solicitante3" onkeypress="return validaletra(event)" placeholder="Tecnologia"/></td>
						</tr>
						<tr>
							<td>Fecha Desde:</td>
							<td><input type="date" id="fechadesde" style="width:100%"/></td>
						</tr>
						<tr>
							<td>Fecha Hasta:</td>
							<td><input type="date" id="fechahasta" style="width:100%"/></td>
						</tr>
						<tr>
							<td colspan="2"><button id="filtrar" onclick="filtrar(3)"><img src="../media/actualizar.png" width="16" height="16" title="Aplicar Filtro" /></button></td>
						</tr>';	
					$tabla.='</table>';
					while($row=$resultado->fetch_assoc()){
						$sql2="SELECT descripcion FROM departamentos WHERE id='".$row['dpto']."'";
						$resultado2=$mysqli->query($sql2);
						$row2=$resultado2->fetch_assoc();
						$tabla.='<table width="100%" border="1" id="tickets">
							<tr>
								<td colspan="8"><p style="text-align:center"><strong>'.utf8_decode($row2['descripcion']).'</strong></p></td>
							</tr>
							<tr>
								<td width="3%"></td>
								<td><strong>ID</strong></td>
								<td><strong>Titulo</strong></td>
								<td><strong>Solicitante</strong></td>
								<td><strong>Estado</strong></td>
								<td width="5%"></td>
								<td width="5%"></td>
								<td width="5%"></td>
							</tr>';
						$sql3="SELECT * FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['dpto']."' AND asignacion='".$_SESSION['nom_us']."' order by id desc";
						$resultado3=$mysqli->query($sql3);
						$rows3=$resultado3->num_rows;
						if($rows3>0){
							while($row3=$resultado3->fetch_assoc()){
								$sql4="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row3['id']."' AND correo='".$_SESSION['correo']."'";
								$resultado4=$mysqli->query($sql4);
								$row4=$resultado4->fetch_assoc();
								switch($row4['estado']){
									case '':
										$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button>';
										break;
									case 0:
										if(($row4['aviso']==1)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row4['aviso']==2)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
											}
										}
										break;
									case 1:
										if(($row4['aviso']==1)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row4['aviso']==2)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
											}
										}
										break;
									case 2:
										if(($row4['aviso']==1)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row4['aviso']==2)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
											}
										}
										break;
									case 3:
										if(($row4['aviso']==1)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row4['aviso']==2)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
											}
										}
										break;
									case 4:
										if(($row4['aviso']==1)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											if(($row4['aviso']==2)&&($row4['valor']==1)){
												$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
											}else{
												$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
											}
										}
										break;
									case 5:
										$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
										break;
									case 6:
										$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
										break;
									case 7:
										$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
										break;	
									case 8:
										if(($row4['aviso']==1)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
										}else{
											$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
										}
										break;
									case 9:
										if(($row4['aviso']==2)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
										}else{
											$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
										}
										break;
									case 10:
										if(($row4['aviso']==3)&&($row4['valor']==1)){
											$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row3['id'].')"><img src="../media/alerta3.png"  /></button></td>';
										}else{
											$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
										}
										break;	
									case 11:
										$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
										break;					
								}
								$titulo=reemplazar($row3['titulo']);
								$solicitante=reemplazar($row3['solicitante']);
								$tabla.='<td>'.$row3['id'].'</td>
										<td>'.$titulo.'</td>
										<td>'.$solicitante.'</td>
										<td>'.$row3['estado'].'</td>
										<td><button id="info" name="info" onclick="info('.$row3['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
										<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row3['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
										<td><button id="hoja" name="hoja" onclick="hoja_servicio('.$row['id'].')"><img src="../media/pdf.png" width="30" height="30" alt="visualizar" title="Generar Hoja de Servicio" /></button></td>
									</tr>';
							}
						}else{
							$tabla.='<tr>
								<td colspan="8">No hay tickets creados.</td>
							</tr>';
						}	
					}
					$tabla.='</table>';
					echo utf8_encode($tabla);
					break;
			}
		}
	}
	if($_POST['aux']==6){//Detalles
		$tabla='';
		$tabla.='<p style="text-align:center"><strong>Detalles del Ticket: '.$_POST['id'].'</strong></p>';
		$tabla.='<table width="100%" border="0">
					<tr>
						<td width="10%"><button id="bandeja" name="bandeja" onclick="bandeja()"><img src="../media/atras.png" width="30" height="30" alt="bandeja" title="Bandeja de Tickets" /></button></td>
						<td></td>
					</tr>
				</table>';
		$sql="SELECT * FROM tickets WHERE id='".$_POST['id']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$sql2="SELECT * FROM tickets_detalles WHERE idticket='".$_POST['id']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado2=$mysqli->query($sql2);
		$i=0;
		$tabla.='<table width="100%" border="1">';
		$fecha1=invertirfecha($row['fechacreacion']);
		$fecha2=invertirfecha($row['fechaestimada']);
		$solicitante=reemplazar($row['solicitante']);
		$titulo=reemplazar($row['titulo']);
			$tabla.='<tr>
						<td width="25%"><strong>Solicitante</strong></td>
						<td>'.$solicitante.'</td>
					</tr>
					<tr>
						<td><strong>Título</strong></td>
						<td>'.$titulo.'</td>
					</tr>
					<tr>
						<td><strong>Fecha de creación</strong></td>
						<td>'.$fecha1.'</td>
					</tr>
					<tr>
						<td><strong>Fecha estimada</strong></td>
						<td>'.$fecha2.'</td>
					</tr>';
		$tabla.='</table><br />';
		while($row2=$resultado2->fetch_assoc()){
			$sql3="SELECT descripcion FROM departamentos WHERE id='".$row2['departamento']."' AND idgerencia='".$_SESSION['gerencia']."'";
			$resultado3=$mysqli->query($sql3);
			$row3=$resultado3->fetch_assoc();
			$sql4="SELECT id, descripcion FROM categorias WHERE iddpto='".$row2['departamento']."' AND idgerencia='".$_SESSION['gerencia']."' and id='".$row2['categoria']."'";
			$resultado4=$mysqli->query($sql4);
			$row4=$resultado4->fetch_assoc();
			$sql5="SELECT descripcion FROM sub_categorias WHERE idcategoria='".$row4['id']."' and id='".$row2['subcategoria']."'";
			$resultado5=$mysqli->query($sql5);
			$row5=$resultado5->fetch_assoc();
			$fecha3=invertirfecha($row2['fecha']);
			$asignado=reemplazar($row2['asignado']);
			$comentarios=reemplazar($row2['comentarios']);
			$categoria=reemplazar($row4['descripcion']);
			$subcategoria=reemplazar($row5['descripcion']);
			$tabla.='<table width="100%" border="1">';
			$i++;
				$tabla.='<tr>
							<td colspan="2"><strong>Actualización</strong> '.$i.'</td>
						</tr>';
				$tabla.='<tr>
							<td width="25%"><strong>Estado</strong></td>
							<td>'.$row2['estado'].'</td>
						</tr>
						<tr>
							<td><strong>Prioridad</strong></td>
							<td>'.$row2['prioridad'].'</td>
						</tr>
						<tr>
							<td><strong>Clasificación</strong></td>
							<td>'.$row2['clasificacion'].'</td>
						</tr>
						<tr>
							<td><strong>Departamento</strong></td>
							<td>'.$row3['descripcion'].'</td>
						</tr>
						<tr>
							<td><strong>Asignado a</strong></td>
							<td>'.$asignado.'</td>
						</tr>
						<tr>
							<td><strong>Fecha actualización</strong></td>
							<td>'.$fecha3.'</td>
						</tr>';
						if($row2['estado']=='Cerrado'){
						$tabla.='
						<tr>
							<td><strong>Fecha de cierre</strong></td>
							<td>'.$fecha3.'</td>
						</tr>';
						}
						$tabla.='<tr>
							<td><strong>Categoría</strong></td>
							<td>'.$categoria.'</td>
						</tr>
						<tr>
							<td><strong>Sub Categoría</strong></td>
							<td>'.$subcategoria.'</td>
						</tr>
						<tr>
							<td><strong>Comentarios</strong></td>
							<td>'.$comentarios.'</td>
						</tr>
						';
			$tabla.='</table><br />';
		}
		$tabla.='
		<table id="carga2" style="display:none">
				<tr><td width="16px"><img src="../media/ajax-loader.gif" "/><td></tr>
		</table>
				<p style="text-align:center"><strong>Actualizar</strong></p>
				<table width="100%" border="1">';
				if($_SESSION['nivel']<2){
					$tabla.='
					<tr>
						<td><strong>Estado: </strong></td>
						<td>
							<select id="estado3">
								<option value="">-Seleccione-</option>
								<option value="Pendiente por Asignar" ';
								if($row['estado']=='Pendiente por Asignar'){
									$tabla.='selected';
								}
								$tabla.='>Pendiente por Asignar</option>
								<option value="Asignado" ';
								if($row['estado']=='Asignado'){
									$tabla.='selected';
								}
								$tabla.='>Asignado</option>
								<option value="En Proceso" ';
								if($row['estado']=='En Proceso'){
									$tabla.='selected';
								}
								$tabla.='>En Proceso</option>
								<option value="Cerrado" ';
								if($row['estado']=='Cerrado'){
									$tabla.='selected';
								}
								$tabla.='>Cerrado</option>
								<option value="En Manos del Cliente" ';
								if($row['estado']=='En Manos del Cliente'){
									$tabla.='selected';
								}
								$tabla.='>En Manos del Cliente</option>
								<option value="En Espera por Terceros" ';
								if($row['estado']=='En Espera por Terceros'){
									$tabla.='selected';
								}
								$tabla.='>En Espera por Terceros</option>
								<option value="Anulado" ';
								if($row['estado']=='Anulado'){
									$tabla.='selected';
								}
								$tabla.='>Anulado</option>
								<option value="Falsa Alarma" ';
								if($row['estado']=='Falsa Alarma'){
									$tabla.='selected';
								}
								$tabla.='>Falsa Alarma</option>
							</select>
						</td>
					</tr>';
				}
					$tabla.='<tr>
						<td width="25%"><strong>Devolver a Service Desk</strong></td>
						<td><input type="checkbox" id="devolver" name="devolver"/></td>
					</tr>
					<tr>
						<td width="25%"><strong>Solicitud de cierre</strong></td>
						<td><input type="checkbox" id="solicitud" name="solicitud" onclick="marcar()"/></td>
					</tr>';
		if($_SESSION['nivel']<2){
			$tabla.='<tr>
						<td><strong>Departamento:</strong></td>
						<td><select id="departamento3" name="departamento3" required onchange="cambiodepartamento3(this.value)">';
						$sql0="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' ORDER BY descripcion ASC";
						$resultado0=$mysqli->query($sql0);
						while($row0=$resultado0->fetch_assoc()){
							if($row['departamento']==$row0['id']){
                        		$tabla.='<option value="'.$row0['id'].'" selected="selected">'.utf8_decode($row0['descripcion']).' </option>';
							}else{
								$tabla.='<option value="'.$row0['id'].'">'.utf8_decode($row0['descripcion']).'</option>';
							}
						}   
			$tabla.='</select></td>
					</tr>';	
		}
		if($_SESSION['nivel']<3){
			$tabla.='<tr>
						<td><strong>Asignado a:</strong></td>
						<td><select id="asignado3" name="asignado3">';
			$sql6="SELECT * FROM usuarios_dptos WHERE dpto='".$row['departamento']."' AND idgerencia='".$_SESSION['gerencia']."'";
			$resultado6=$mysqli->query($sql6);
			$tabla.='<option value="Sin Asignar">-Sin Asignar-</option>';
			while($row6=$resultado6->fetch_assoc()){
				$sql7="SELECT id, nombre, apellido FROM usuarios WHERE correo='".$row6['usuario']."' AND activo='0' AND nivel>0";
				$resultado7=$mysqli->query($sql7);
				$row7=$resultado7->fetch_assoc();
				$rows7=$resultado7->num_rows;
				if($rows7>0){
					$nombre=reemplazar($row7['nombre'].' '.$row7['apellido']);
					if($nombre==$asignado){
						$tabla.='<option selected="selected" value="'.$row7['id'].'">'.$nombre.'</option>';
					}else{
						$tabla.='<option value="'.$row7['id'].'">'.$nombre.'</option>';
					}
				}
			}		
			$tabla.='</select></td></tr>';
			$tabla.='<tr>
						<td><strong>Categoría:</strong></td>
						<td><select id="categoria3" onchange="subcategoria2(this.value)">';
			$sql8="SELECT * FROM categorias WHERE iddpto='".$row['departamento']."' AND idgerencia='".$_SESSION['gerencia']."'";
			$resultado8=$mysqli->query($sql8);
			while($row8=$resultado8->fetch_assoc()){
				$categoria2=reemplazar($row8['descripcion']);
				if($row['categoria']==$row8['id']){
					$tabla.='<option value="'.$row8['id'].'" selected="selected">'.$categoria2.'</option>';
				}else{
					$tabla.='<option value="'.$row8['id'].'">'.$categoria2.'</option>';
				}
			}		
			$tabla.='</select></td></tr>';
			$tabla.='<tr>
						<td><strong>Sub Categoría:</strong></td>
						<td><select id="subcategoria3">';
			$sql9="SELECT * FROM sub_categorias WHERE idcategoria='".$row['categoria']."'";
			$resultado9=$mysqli->query($sql9);
			while($row9=$resultado9->fetch_assoc()){
				$subcategoria2=reemplazar($row9['descripcion']);
				if($row['subcategoria']==$row9['id']){
					$tabla.='<option selected="selected" value="'.$row9['id'].'">'.$subcategoria2.'</option>';
				}else{
					$tabla.='<option value="'.$row9['id'].'">'.$subcategoria2.'</option>';
				}
			}		
			$tabla.='</select></td></tr>';
		}
			$tabla.='<tr>
						<td><strong>Comentarios:</strong></td>
						<td><textarea id="comentario3" placeholder="Escriba un comentario"></textarea></td>
					</tr>
					<tr>
						<td colspan="2"><button id="actualizar" name="actualizar" onclick="actualizar6('.$_POST['id'].','.$_SESSION['nivel'].')"><img src="../media/actualizar.png" title="Actualizar"/></button></td>
					</tr>
				</table>';
		echo utf8_encode($tabla);
	}
	if($_POST['aux']==7){//Actualizar
		$sql="SELECT * FROM tickets WHERE id='".$_POST['id']."' AND idgerencia='24'";
		$hora=getdate();
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$idticket=$row['id'];
		$idgerencia=$_SESSION['gerencia'];
		$clasificacion=$row['clasificacion'];
		$estado=$row['estado'];
		$prioridad=$row['prioridad'];
		$oficina=$row['oficina'];
		$opc=$row['opc'];
		$idsol=$row['idsol'];
		$solicitante=$row['solicitante'];
		$correo=$row['correo'];//correo del solicitante
		$titulo=$row['titulo'];
		$departamento=$row['departamento'];
		$categoria=$row['categoria'];
		$categoria2=$_POST['categoria'];
		$subcategoria=$row['subcategoria'];
		$subcategoria2=$_POST['subcategoria'];
		$idasignacion=$row['idasignacion'];
		$asignacion=$row['asignacion'];
		$fechacreacion=$row['fechacreacion'];
		$fechaestimada=$row['fechaestimada'];
		$fechacierre=$row['fechacierre'];;
		$fechacierre2='';
		$cambio=0;
		if($_POST['nivel']<3){//Si el nivel del usuario es menor a 3 puede cambiar la asignación del ticket
			$sql1="SELECT id, nombre, apellido, correo FROM usuarios WHERE id='".$_POST['asignado']."'";
			$resultado1=$mysqli->query($sql1);
			$row1=$resultado1->fetch_assoc();
			$idasignacion=$row1['id'];
			$asignacion2=utf8_decode($row1['nombre'].' '.$row1['apellido']);
			if($asignacion!=$asignacion2){//Si son diferentes entonces se cambio la asignación del ticket
				if($asignacion!='Service Desk'){//Para no borrar el registro de services desk que debe ser permanente
					$sql2="DELETE FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket' AND nombre='".utf8_encode($asignacion)."' ";
					$resultado2=$mysqli->query($sql2);
					$sql3="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor) VALUES ('".$_SESSION['gerencia']."', '$idticket', '".$asignacion2."', '".$row1['correo']."', '1', '0')";
					$resultado3=$mysqli->query($sql3);
				}
			}
		}
		$fecha = date('Y-m-d H:i');
		$nuevafecha=$fecha;
		if($estado!=$_POST['estado']){//Si el estado es diferente se produjo alguna modificación y se debe calcular la nueva fecha estimada de cierre
			$cambio=1;
			switch($_POST['estado']){
				case 'Pendiente por Asignar':
					$estado2=0;
					$aux=0;
					$i=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql4="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado4=$mysqli->query($sql4);
						$rows4=$resultado4->num_rows;
						if($i==10){
							$aux=1;
						}
						if($rows4>0){
							$sql5="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado5=$mysqli->query($sql5);
							$rows5=$resultado5->num_rows;
							if($rows5>0){
							}else{
								$aux=1;
							}
						}
					}
					break;
				case 'Asignado':
					$estado2=1;
					//$sql4="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcategoria']."'";
					$sql4="SELECT sla FROM sub_categorias WHERE id='63'";
					$resultado4=$mysqli->query($sql4);
					$row4=$resultado4->fetch_assoc();
					$i=0;
					for($j=0;$j<=$row4['sla'];$j++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha));
							$sql5="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado5=$mysqli->query($sql5);
							$rows5=$resultado5->num_rows;
							if($rows5>0){
								$sql6="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
								$resultado6=$mysqli->query($sql6);
								$rows6=$resultado6->num_rows;
								if($rows6>0){
								}else{
									$aux=1;
								}
							}
						}
					}
					break;
				case 'En Proceso':
					$estado2=2;	
					//$sql4="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcategoria']."'";
					$sql4="SELECT sla FROM sub_categorias WHERE id='63'";
					$resultado4=$mysqli->query($sql4);
					$row4=$resultado4->fetch_assoc();
					$i=0;
					for($j=0;$j<=$row4['sla'];$j++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha	));
							$sql5="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado5=$mysqli->query($sql5);
							$rows5=$resultado5->num_rows;
							if($rows5>0){
								$sql6="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
								$resultado6=$mysqli->query($sql6);
								$rows6=$resultado6->num_rows;
								if($rows6>0){
								}else{
									$aux=1;
								}
							}
						}
					}
					break;
				case 'En Manos del Cliente':
					$estado2=3;
					$fecha = date('Y-m-d');
					$i=0;
					for($j=0;$j<=7;$j++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha	));
							$sql4="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado4=$mysqli->query($sql4);
							$rows4=$resultado4->num_rows;
							if($rows4>0){
								$sql5="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
								$resultado5=$mysqli->query($sql5);
								$rows5=$resultado5->num_rows;
								if($rows5>0){
								}else{
									$aux=1;
								}
							}
						}
					}
					break;
				case 'En Espera por Terceros':
					$estado2=4;
					$i=0;
					for($j=0;$j<=15;$j++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha	));
							$sql4="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado4=$mysqli->query($sql4);
							$rows4=$resultado4->num_rows;
							if($rows4>0){
								$sql5="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
								$resultado5=$mysqli->query($sql5);
								$rows5=$resultado5->num_rows;
								if($rows5>0){
								}else{
									$aux=1;
								}
							}
						}
					}
					break;
				case 'Anulado':
					$estado2=5;
					$sql4="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcate']."'";
					$resultado4=$mysqli->query($sql4);
					$row4=$resultado4->fetch_assoc();
					$i=0;
					for($j=0;$j<=$row4['sla'];$j++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha	));
							$sql5="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado5=$mysqli->query($sql5);
							$rows5=$resultado5->num_rows;
							if($rows5>0){
								$sql6="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
								$resultado6=$mysqli->query($sql6);
								$rows6=$resultado6->num_rows;
								if($rows6>0){
								}else{
									$aux=1;
								}
							}
						}
					}
					break;
				case 'Falsa Alarma':
					$estado2=6;
					//$sql4="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcategoria']."'";
					$sql4="SELECT sla FROM sub_categorias WHERE id='63'";
					$resultado4=$mysqli->query($sql4);
					$row4=$resultado4->fetch_assoc();
					$i=0;
					for($j=0;$j<=$row4['sla'];$j++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha	));
							$sql5="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado5=$mysqli->query($sql5);
							$rows5=$resultado5->num_rows;
							if($rows5>0){
								$sql6="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
								$resultado6=$mysqli->query($sql6);
								$rows6=$resultado6->num_rows;
								if($rows6>0){
								}else{
									$aux=1;
								}
							}
						}
					}
					break;
				case 'Cerrado':
					$estado2=7;
					$cambio=0;
					break;
			}
			if(($estado=='Cerrado')&&($_POST['estado']!='Cerrado')){
				$sql41="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', 'Admin Admin', 'tecnologia@prosein.com', '1', '0','$estado2')";
				$resultado41=$mysqli->query($sql41);
				$sql42="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', 'Services Desk', 'servicesdesk@prosein.com', '1', '0','$estado2')";
				$resultado42=$mysqli->query($sql42);
			}
			
		}else{
			$estado2=1;
			if($subcategoria!=$subcategoria2){//si cambio la subcategoria se debe  modificar el SLA del ticket
				$cambio=1;
				$sql7="SELECT sla FROM sub_categorias WHERE id='".$subcategoria."'";
				$resultado7=$mysqli->query($sql7);
				$row7=$resultado7->fetch_assoc();
				$aux=0;
				$i=0;
				while($aux==0){
					$i++;
					if($i==1){
						$nuevafecha = strtotime ( '+'.$row7['sla'].' day' , strtotime ($fecha)) ;
						$nuevafecha =date('d-m-Y H:i',$nuevafecha);
					}else{
						$nuevafecha = strtotime ( '+'.$row7['sla'].' day' , strtotime ($nuevafecha)) ;
						$nuevafecha =date('d-m-Y H:i',$nuevafecha);
					}
					$day=date("D",strtotime($nuevafecha	));
					$sql8="SELECT * FROM dias_laborables WHERE dia='$day'";
					$resultado8=$mysqli->query($sql8);
					$rows8=$resultado8->num_rows;
					if($i==10){
						$aux=1;
					}
					if($rows8>0){
						$sql9="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
						$resultado9=$mysqli->query($sql9);
						$rows9=$resultado9->num_rows;
						if($rows9>0){
						}else{
							$aux=1;
						}
					}
				}
				$aux=1;
			}
		}
		$comentarios=$_POST['comentario'];
		$fecha=date('Y-m-d H:i');//la hora a registrar debe incluir horas y minutos
		if($_POST['nivel']<2){////Si el nivel es menor a 2 puedo modificar el departamento, el estado y el asignado
			$estado=$_POST['estado'];
			$departamento=$_POST['dpto'];
			$asignacion=$_POST['asignado'];
		}
		if($_POST['nivel']==2){//Si el nivel es 2 solo puedo cambiar la asignación
			$asignacion=$_POST['asignado'];
			if(($asignacion!='Sin Asignar')and($estado!='En Proceso')){
				$estado='Asignado';
			}
		}	
		$sql10="SELECT nombre, apellido, correo FROM usuarios WHERE id='$asignacion'";
		$resultado10=$mysqli->query($sql10);
		$row10=$resultado10->fetch_assoc();
		$asignacion2=utf8_decode($row10['nombre'].' '.$row10['apellido']);
		$correo2=$row10['correo'];
		if($_POST['devolver']=='si'){
			$sql11="SELECT id FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' AND descripcion='Services Desk'";
			$resultado11=$mysqli->query($sql11);
			$row11=$resultado11->fetch_assoc();
			$departamento=$row11['id'];
			$sql12="SELECT usuario FROM usuarios_dptos WHERE dpto='".$row11['id']."' and idgerencia='".$_SESSION['gerencia']."'";
			$resultado12=$mysqli->query($sql12);
			while($row12=$resultado12->fetch_assoc()){
				$sql13="SELECT id, nombre, apellido, nivel, correo FROM usuarios WHERE correo='".$row12['usuario']."' AND idgerencia='".$_SESSION['gerencia']."' AND activo='0'";
				$resultado13=$mysqli->query($sql13);
				$row13=$resultado13->fetch_assoc();
				if($row13['nivel']==1){
					$idasignacion=$row13['id'];
					$asignacion2=utf8_decode($row13['nombre'].' '.$row13['apellido']);
					$correo2=$row13['correo'];
				}
			}
			$sql14="SELECT id FROM categorias WHERE idgerencia='".$_SESSION['gerencia']."' AND iddpto='".$row11['id']."' AND descripcion='Ticket Devuelto'";
			$resultado14=$mysqli->query($sql14);
			$row14=$resultado14->fetch_assoc();
			$categoria=$row14['id'];
			$sql15="SELECT id FROM sub_categorias WHERE idcategoria='".$row14['id']."'";
			$resultado15=$mysqli->query($sql15);
			$row15=$resultado15->fetch_assoc();
			$subcategoria=$row15['id'];
		}
		if($_POST['solicitud']=='si'){
			$comentarios='[SOLICITUD DE CIERRE] '.$_POST['comentario'];
		}
		$sql16="INSERT INTO tickets_detalles (idticket, idgerencia, estado, prioridad, clasificacion, asignado, departamento, categoria, subcategoria, comentarios, fecha) VALUES ('$idticket', '".$_SESSION['gerencia']."', '".$estado."', '".$prioridad."', '".$clasificacion."', '".utf8_encode($asignacion2)."', '".$departamento."', '".$categoria."', '".$subcategoria."', '".utf8_encode($comentarios)."', '".$fecha."')";
		$resultado16=$mysqli->query($sql16);
		if($asignacion2=='Sin Asignar'){
			$estado='Sin Asignar';
		}
		if($cambio==1){//Si se realizo algún cambio se modifica el sla y por ende la fecha estimada de cierre, por lo que se invierte para poder mostrarla.
			$nuevafecha2=invertirfecha2($nuevafecha);					
		}else{
			$nuevafecha2=$nuevafecha;
		}
		if($estado=='Cerrado'){//Si el estado es cerrado, la fecha de cierre es la actual
			$fechacierre=date('Y-m-d H:i');
		}else{
			$fechacierre="0000-00-00";
		}	
		$sql17="UPDATE tickets SET clasificacion='$clasificacion', estado='$estado', prioridad='$prioridad', categoria='$categoria', subcategoria='$subcategoria', idasignacion='$idasignacion', asignacion='".utf8_encode($asignacion2)."', departamento='$departamento', fechaestimada='$nuevafecha2', estado='$estado', fechacierre='$fechacierre' WHERE idgerencia='".$_SESSION['gerencia']."' AND id='$idticket'";
		$resultado17=$mysqli->query($sql17);
		//Proceso de actualizar en tickets_seguimiento
		$sql18="SELECT * FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket'";
		$resultado18=$mysqli->query($sql18);
		$rows18=$resultado18->num_rows;
		$bandera=0;
		if($rows18>0){//Si consigue, significa que el sistema de alerta ya está en funcionamiento.
			if($_POST['estado']!='Sin Asignar'){//Cuando tiene asignado el usuario
				while($row18=$resultado18->fetch_assoc()){
					if($row18['correo']==$correo2){//Si consigo registro entonces lo actualizo
						$bandera=1;
					}
				}
				if(($estado=='Cerrado')or($estado=='Anulado')or($estado=='Falsa Alarma')){//Si es alguno de estos se borra
					$sql19="DELETE FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket'";
					$resultado19=$mysqli->query($sql19);
				}else{
					if($bandera==1){//Si encontro coincidencia actualizo la información
						$resultado18=$mysqli->query($sql18);
						$row18=$resultado18->fetch_assoc();
						if($estado2!=$row18['estado']){//Si el estado cambio, actualizo el mismo
							if($row18['estado']!='11'){
								$sql20="UPDATE tickets_seguimiento SET estado='$estado2' WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket'";
								$resultado20=$mysqli->query($sql20);
							}
						}
					}else{//Si no encontro coincidencia entonces debo borrar al usuario que tenia asignado el ticket y agregar el nuevo
						$sql21="SELECT * FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket' and correo!='tecnologia@prosein.com' and correo!='servicesdesk@prosein.com.ve'";
						$resultado21=$mysqli->query($sql21);
						$rows21=$resultado21->num_rows;
						if($rows21>0){//Si consigue al usuario
							$row21=$resultado21->fetch_assoc();
							$sql22="DELETE FROM  tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket' and correo='".$row21['correo']."'";
							$resultado22=$mysqli->query($sql22);
							$sql23="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', '".utf8_encode($asignacion2)."', '$correo2', '1', '0','$estado2')";
							$resultado23=$mysqli->query($sql23);
						}else{//Solo agrego al usuario nuevo
							$sql24="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', '".utf8_encode($asignacion2)."', '$correo2', '1', '0','$estado2')";
							$resultado24=$mysqli->query($sql24);
						}
					}
				}
			}else{//Cuando no tengo un usuario asignado, debo buscar al jefe de departamento
				$sql25="SELECT * FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket' and correo!='tecnologia@prosein.com' and correo!='servicedesk@prosein.com'";
				$resultado25=$mysqli->query($sql25);
				$rows25=$resultado25->num_rows;
				if($rows25>0){//Significa que tengo algun usuario
					$row25=$resultado25->fetch_assoc();
					$sql26="SELECT departamento FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND id='$idticket'";
					$resultado26=$mysqli->query($sql26);
					$row26=$resultado26->fetch_assoc();
					$sql27="SELECT usuarios_dptos.usuario as usuario FROM usuarios_dptos INNER JOIN usuarios ON usuarios_dptos.usuario=usuarios.correo where usuarios_dptos.idgerencia='".$_SESSION['gerencia']."' and usuarios_dptos.dpto='".$row26['departamento']."' and usuarios.nivel<3";
					$resultado27=$mysqli->query($sql27);
					$rows27=$resultado27->num_rows;
					if($rows27>0){//Verifico que el usuario sea el jefe de departamento
						$row27=$resultado27->fetch_assoc();
						if($row27['usuario']!=$row25['correo']){//Si no es el jefe de departamento, lo elimino y agrego al nuevo
							$sql28="DELETE FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='$idticket' and correo='".$row25['correo']."'";
							$resultado28=$mysqli->query($sql28);
							$sql29="SELECT nombre, apellido FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$row27['usuario']."'";
							$resultado29=$mysqli->query($sql29);
							$row29=$resultado29->fetch_assoc();
							$nombre=$row29['nombre'].' '.$row29['apellido'];
							$sql30="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', '$nombre', '".utf8_encode($row27['usuario'])."', '1', '0','$estado2')";
							$resultado05=$mysqli->query($sql05);
						}
					}
				}
			}
		}else{//Si no se consiguen registros se deben agregar nuevamente (tecnologia, servicesdesk y los involucrados)
			$sql31="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', 'Admin Admin', 'tecnologia@prosein.com', '1', '0','$estado2')";
			$resultado31=$mysqli->query($sql31);
			$sql32="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', 'Services Desk', 'servicesdesk@prosein.com', '1', '0','$estado2')";
			$resultado32=$mysqli->query($sql32);
			if($estado2=='0'){//Si no está asignado se debe buscar el jefe
				$sql33="SELECT usuario FROM usuarios_dptos WHERE dpto='".$_POST['dpto']."' AND idgerencia='".$_SESSION['gerencia']."'";
				$resultado33=$mysqli->query($sql33);
				while($row33=$resultado33->fetch_assoc()){
					$sql34="SELECT nombre, apellido, nivel FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$row33['usuario']."'";
					$resultado34=$mysqli->query($sql34);
					$row34=$resultado34->fetch_assoc();
					if($row34['nivel']==2){//2 para Jefe de departamento
						$nombre=utf8_decode($row34['nombre'].' '.$row34['apellido']);
						$sql35="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', '".utf8_encode($nombre)."', '".$row33['usuario']."', '1', '0','$estado2')";
						$resultado35=$mysqli->query($sql35);
					}
				}				
			}else{//para cuando el ticket si está asignado
				$sql36="SELECT usuario FROM usuarios_dptos WHERE dpto='".$_POST['dpto']."' AND idgerencia='".$_SESSION['gerencia']."'";
				$resultado36=$mysqli->query($sql36);
				while($row36=$resultado36->fetch_assoc()){
					$sql37="SELECT nombre, apellido, correo FROM usuarios WHERE nivel='2' AND idgerencia='".$_SESSION['gerencia']."' AND activo='0' AND correo='".$row36['usuario']."'";
					$resultado37=$mysqli->query($sql37);
					$rows37=$resultado37->num_rows;
					if($rows37>0){
						$row37=$resultado37->fetch_assoc();
						$nombre=utf8_decode($row37['nombre'].' '.$row37['apellido']);
						$sql38="INSERT INTO tickets_seguimiento (idgerencia, idticket, nombre, correo, aviso, valor, estado) VALUES ('".$_SESSION['gerencia']."', '$idticket', '".utf8_encode($nombre)."', '".$row37['correo']."', '1', '0','$estado2')";
						$resultado38=$mysqli->query($sql38);
					}
				}
			}
		}
		$nuevafecha3=invertirfecha($nuevafecha2);		
		$solicitante=reemplazar($solicitante);
		$asignacion2=utf8_decode($asignacion2);
		$cuerpo='
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			</head>
			<body>';
				if($hora['hours']<12){
					if($estado=='Cerrado'){
						$cuerpo='<p style="color:#F00">Buen día, En relación a su solicitud se ha Cerrado temporalmente el ticket N°: '.$idticket.'.<br/>
						El mismo poseerá dicho estatus por 3 días hábiles para su verificación o certificación, de existir inconformidad notificarnos para reabrir el ticket, de no poseerse en este tiempo observación alguna pasará a cierre definitivo.
</p>';
					}else{
						$cuerpo.='<p>Buen día, se ha actualizado la información del Ticket N°: '.$idticket.'</p>';
					}
				}else{
					if($estado=='Cerrado'){
						$cuerpo='<p style="color:#F00">Buenas tardes, En relación a su solicitud se ha Cerrado temporalmente el ticket N°: '.$idticket.'.<br/>
						El mismo poseerá dicho estatus por 3 días hábiles para su verificación o certificación, de existir inconformidad notificarnos para reabrir el ticket, de no poseerse en este tiempo observación alguna pasará a cierre definitivo.
</p>';
					}else{
						$cuerpo.='<p>Buenas tardes, se ha actualizado la informacion del Ticket N°: '.$idticket.'</p>';
					}
				}		
				$cuerpo.='<p><strong>DETALLES DEL TICKET</strong></p>
				<p><strong>Solicitante: </strong>'.$solicitante.'</p>
				<p><strong>Título: </strong>'.utf8_decode($titulo).'</p>
				<p><strong>Estado:  </strong>'.$estado.'</p>
				<p><strong>Fecha de actualización: </strong>'.$nuevafecha3.'</p>';
				if($asignacion2!='Sin Asignar'){
					$cuerpo.='
					<p><strong>Atendido por: </strong>'.$asignacion2.'</p>
					<p><strong>Correo: </strong>'.$correo2.'</p>';
				}
				if($estado=='Cerrado'){
					$fechacierre=invertirfecha($fechacierre);
					$cuerpo.='<p><strong>Fecha de cierre: </strong>'.$fechacierre.'</p>';
				}
				
				$cuerpo.='
				<p><strong>Detalles: </strong>'.utf8_decode($comentarios).'</p>
				<p style="color:#00F">
				Gracias por comunicarse a Services Desk,  el único punto de contacto para el manejo de solicitudes.
				</p><br />
				<p><img src="http://prsbousa.eastus2.cloudapp.azure.com/prosein/firma.png" width="464" height="214" /></p>
			</body>
		</html>';
		$cuerpo=utf8_encode($cuerpo);
		// PHPMAILER
		$sql39="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
		$resultado39=$mysqli->query($sql39);
		$row39=$resultado39->fetch_assoc();
		$sql40="SELECT correo FROM usuarios WHERE nombre='Services' AND apellido='Desk' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado40=$mysqli->query($sql40);
		$row40=$resultado40->fetch_assoc();
		$mail = new PHPMailer();
		$mail->IsSMTP(); 					// set mailer to use  SMTP
		$mail->Host = "smtp.office365.com"; 			// specify main and backup server
		$mail->Port	  = "587";             			// set the SMTP server port
		$mail->SMTPSecure = "tls";				// set the encryption. - mbilotti.
		$mail->SMTPAuth = "true";				// turn on SMTP authentication
		$mail->CharSet = 'UTF-8';
		$mail->Username = "servicesdesk@prosein.com";
		$mail->Password = "Prosein_2019*"; 
		$mail->From = "servicesdesk@prosein.com";
		$mail->FromName = "Sistema de Tickets De ".utf8_decode($row39['descripcion'])."";
		$mail->AddAddress ("".$correo."");                 		 // name is optional
		$mail->addCC("".$row40['correo']."", "Services Desk");
		if($_POST['estado']!='Sin Asignar'){//Cuando tiene asignado el usuario
			$mail->addCC($correo2,$asignacion2);
		}else{//Si esta sin asignar busco al Jefe del Departamento	
			$sql41="SELECT usuarios_dptos.usuario as correo, usuarios.nombre as nombre, usuarios.apellido as apellido FROM usuarios_dptos INNER JOIN usuarios ON usuarios.correo=usuarios_dptos.usuario WHERE usuarios_dptos.idgerencia='".$_SESSION['gerencia']."' AND usuarios_dptos.dpto='".$_POST['dpto']."' AND usuarios.nivel>1";
			$resultado41=$mysqli->query($sql41);
			$rows41=$resultado41->num_rows;
			if($rows41>0){
				while($row41=$resultado41->fetch_assoc()){
					$mail->addCC($row41['correo'],utf8_decode($row41['nombre'].' '.$row41['apellido']));
				}
			}
		}
		$mail->WordWrap = 50; // set word wrap to 50 characters
		$mail->IsHTML(true); // set email format to HTML
		$mail->Subject = "Ticket: ".$idticket." De la ".utf8_decode($row39['descripcion'])."";
		$mail->Body    = $cuerpo;
		/*if(!$mail->Send()) {
			echo("Fall&oacute; env&iacute;o mail confirmaci&oacute;n");
			echo "Error: " . $mail->ErrorInfo;
		}else{
			echo "Enviado";
		}*/
	}
	if($_POST['aux']==8){
		date_default_timezone_set('america/caracas');
		switch($_POST['estado']){
			case 'Pendiente por Asignar':
				$fecha = $_POST['fecha'];
				$aux=0;
				$i=0;
				while($aux==0){
					$i++;
					if($i==1){
						$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
						$nuevafecha =date('d-m-Y H:i',$nuevafecha);
					}else{
						$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
						$nuevafecha =date('d-m-Y H:i',$nuevafecha);
					}
					$day=date("D",strtotime($nuevafecha	));
					$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
					$resultado2=$mysqli->query($sql2);
					$rows2=$resultado2->num_rows;
					if($i==10){
						$aux=1;
					}
					if($rows2>0){
						$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
						$resultado3=$mysqli->query($sql3);
						$rows3=$resultado3->num_rows;
						if($rows3>0){
						}else{
							$aux=1;
						}
					}
				}
				break;
			case 'Asignado':
				$sql="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcate']."'";
				$resultado=$mysqli->query($sql);
				$row=$resultado->fetch_assoc();
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<$row['sla'];$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
			case 'En Proceso':
				$sql="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcate']."'";
				$resultado=$mysqli->query($sql);
				$row=$resultado->fetch_assoc();
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<$row['sla'];$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
			case 'Cerrado':
				$sql="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcate']."'";
				$resultado=$mysqli->query($sql);
				$row=$resultado->fetch_assoc();
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<$row['sla'];$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
			case 'En Manos del Cliente':
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<=7;$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
			case 'En Espera por Terceros':
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<=15;$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
			case 'Anulado':
				$sql="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcate']."'";
				$resultado=$mysqli->query($sql);
				$row=$resultado->fetch_assoc();
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<$row['sla'];$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
			case 'Falsa Alarma':
				$sql="SELECT sla FROM sub_categorias WHERE id='".$_POST['subcate']."'";
				$resultado=$mysqli->query($sql);
				$row=$resultado->fetch_assoc();
				$fecha = $_POST['fecha'];
				$i=0;
				for($j=0;$j<$row['sla'];$j++){
					$aux=0;
					while($aux==0){
						$i++;
						if($i==1){
							$nuevafecha = strtotime ( '+1 day' , strtotime ($fecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}else{
							$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
							$nuevafecha =date('d-m-Y H:i',$nuevafecha);
						}
						$day=date("D",strtotime($nuevafecha	));
						$sql2="SELECT * FROM dias_laborables WHERE dia='$day'";
						$resultado2=$mysqli->query($sql2);
						$rows2=$resultado2->num_rows;
						if($rows2>0){
							$sql3="SELECT * FROM calendario WHERE fecha='$nuevafecha'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
							}else{
								$aux=1;
							}
						}
					}
				}
				break;
		}
		echo str_replace('-','/',$nuevafecha);
	}
	if($_POST['aux']==9){
		if(($_POST['nivel']==0) or($_POST['nivel']==1)){
			$i=0;
			$sql="SELECT * FROM tickets ";
			if($_POST['titulo']!=''){
				$i++;
				$sql.=" WHERE titulo LIKE '%".utf8_encode($_POST['titulo'])."%' ";
			}
			if($_POST['estado']!=''){
				if($i==0){
					$sql.=" WHERE estado LIKE '%".$_POST['estado']."%'";
				}else{
					$sql.=" AND estado LIKE '%".$_POST['estado']."%'";
				}
				$i++;
			}
			if($_POST['solicitante']!=''){
				if($i==0){
					$sql.=" WHERE solicitante LIKE '%".$_POST['solicitante']."%'";
				}else{
					$sql.=" AND solicitante LIKE '%".$_POST['solicitante']."%'";
				}
				$i++;
			}
			if($_POST['departamento']!=''){
				if($i==0){
					$sql.=" WHERE departamento LIKE '%".$_POST['departamento']."%'";
				}else{
					$sql.=" AND departamento LIKE '%".$_POST['departamento']."%'";
				}
				$i++;
			}
			if($_POST['asignado']!=''){
				if($i==0){
					$sql.=" WHERE idasignacion LIKE '%".$_POST['asignado']."%'";
				}else{
					$sql.=" AND idasignacion LIKE '%".$_POST['asignado']."%'";
				}
				$i++;
			}
			if($_POST['fechadesde']!=''){
				if($i==0){
					$sql.=" WHERE fechacreacion BETWEEN '".$_POST['fechadesde']."' AND '".$_POST['fechahasta']."'";
				}else{
					$sql.=" AND fechacreacion BETWEEN '".$_POST['fechadesde']."' AND '".$_POST['fechahasta']."'";
				}
			}
			$sql.=" AND idgerencia='".$_SESSION['gerencia']."' ORDER BY ID DESC";
			$resultado=$mysqli->query($sql);
			$tabla="";
			$tabla.='<table width="100%" border="1" id="tickets">
					<tr>
						<td width="3%"></td>
						<td><strong>ID</strong></td>
						<td><strong>Titulo</strong></td>
						<td><strong>Solicitante</strong></td>
						<td><strong>Estado</strong></td>
						<td width="5%"></td>
						<td width="5%"></td>
					</tr>';
			$rows=$resultado->num_rows;
			if($rows>0){		
				while($row=$resultado->fetch_assoc()){
					$sql3="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row['id']."' AND correo='".$_SESSION['correo']."'";
					$resultado3=$mysqli->query($sql3);
					$row3=$resultado3->fetch_assoc();
					switch($row3['estado']){
						case '':
							$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
							break;
						case 0:
							if(($row3['aviso']==1)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row3['aviso']==2)&&($row3['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
								}
							}
							break;
						case 1:
							if(($row3['aviso']==1)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row3['aviso']==2)&&($row3['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
								}
							}
							break;
						case 2:
							if(($row3['aviso']==1)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row3['aviso']==2)&&($row3['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
								}
							}
							break;
						case 3:
							if(($row3['aviso']==1)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row3['aviso']==2)&&($row3['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
								}
							}
							break;
						case 4:
							if(($row3['aviso']==1)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row3['aviso']==2)&&($row3['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
								}
							}
							break;
						case 5:
							$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
							break;
						case 6:
							$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
							break;
						case 7:
							$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
							break;	
						case 8:
							if(($row3['aviso']==1)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
							}
							break;
						case 9:
							if(($row3['aviso']==2)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
							}else{
								$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
							}
							break;
						case 10:
							if(($row3['aviso']==3)&&($row3['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row['id'].')"><img src="../media/alerta3.png"  /></button></td>';
							}else{
								$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
							}
							break;	
						case 11:
							$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
							break;					
					}
					$tabla.='<td>'.$row['id'].'</td>
							<td>'.utf8_decode($row['titulo']).'</td>
							<td>'.utf8_decode($row['solicitante']).'</td>
							<td>'.utf8_decode($row['estado']).'</td>
							<td><button id="info" name="info" onclick="info('.$row['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
							<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
						</tr>';
				}
			}else{
				$tabla.='<tr>
						<td colspan="4">No hay tickets creados.</td>
					</tr>';
			}
			$tabla.='</table>';
			echo $tabla;
			
		}
		if($_POST['nivel']==2){
			$sql="SELECT * FROM usuarios_dptos WHERE usuario='".$_SESSION['correo']."' AND idgerencia='".$_SESSION['gerencia']."'";
			$resultado=$mysqli->query($sql);
			$tabla="";
			$i=0;
			while($row=$resultado->fetch_assoc()){
				$i++;
				$sql2="SELECT descripcion FROM departamentos WHERE id='".$row['dpto']."'";
				$resultado2=$mysqli->query($sql2);
				$row2=$resultado2->fetch_assoc();
				$tabla.='
				<table width="100%" border="1" id="tickets'.$i.'">
					<tr>
						<td colspan="7"><p style="text-align:center"><strong>'.$row2['descripcion'].'</strong></p></td>
					</tr>
					<tr>
						<td width="3%"></td>
						<td><strong>ID</strong></td>
						<td><strong>Titulo</strong></td>
						<td><strong>Solicitante</strong></td>
						<td><strong>Estado</strong></td>
						<td width="5%"></td>
						<td width="5%"></td>
					</tr>';
				$sql3="SELECT * FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['dpto']."' ";
				if($_POST['titulo']!=''){
					$sql3.=" AND titulo LIKE '%".$_POST['titulo']."%' ";
				}
				if($_POST['estado']!=''){
					$sql3.=" AND estado LIKE '%".$_POST['estado']."%'";
				}
				if($_POST['solicitante']!=''){
					$sql3.=" AND solicitante LIKE '%".$_POST['solicitante']."%'";
				}
				if($_POST['asignado']!=''){
					$sql3.=" AND idasignacion LIKE '%".$_POST['asignado']."%'";
				}
				if($_POST['fechadesde']!=''){
					$sql3.=" AND fechacreacion BETWEEN '".$_POST['fechadesde']."' AND '".$_POST['fechahasta']."'";
				}
				$sql3.=" ORDER BY id DESC";
				$resultado3=$mysqli->query($sql3);
				$rows3=$resultado3->num_rows;
				if($rows3>0){
					while($row3=$resultado3->fetch_assoc()){
						$sql4="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row3['id']."' AND correo='".$_SESSION['correo']."'";
					$resultado4=$mysqli->query($sql4);
					$row4=$resultado4->fetch_assoc();
					switch($row4['estado']){
						case '':
							$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
							break;
						case 0:
							if(($row4['aviso']==1)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row4['aviso']==2)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
								}
							}
							break;
						case 1:
							if(($row4['aviso']==1)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row4['aviso']==2)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
								}
							}
							break;
						case 2:
							if(($row4['aviso']==1)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row4['aviso']==2)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
								}
							}
							break;
						case 3:
							if(($row4['aviso']==1)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row4['aviso']==2)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
								}
							}
							break;
						case 4:
							if(($row4['aviso']==1)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								if(($row4['aviso']==2)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
								}
							}
							break;
						case 5:
							$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
							break;
						case 6:
							$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
							break;
						case 7:
							$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
							break;	
						case 8:
							if(($row4['aviso']==1)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
							}else{
								$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
							}
							break;
						case 9:
							if(($row4['aviso']==2)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
							}else{
								$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
							}
							break;
						case 10:
							if(($row4['aviso']==3)&&($row4['valor']==1)){
								$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row3['id'].')"><img src="../media/alerta3.png"  /></button></td>';
							}else{
								$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
							}
							break;	
						case 11:
							$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
							break;					
					}
					$tabla.='<td>'.$row3['id'].'</td>
							<td>'.utf8_decode($row3['titulo']).'</td>
							<td>'.utf8_decode($row3['solicitante']).'</td>
							<td>'.$row3['estado'].'</td>
							<td><button id="info" name="info" onclick="info('.$row3['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
							<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row3['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
						</tr>';
					}
				}else{

					$tabla.='<tr>
						<td colspan="7">No hay tickets creados.</td>
					</tr>';
				}
			}
			echo $tabla;
			$i=0;
		}
		if($_POST['nivel']==3){
			$sql="SELECT * FROM usuarios_dptos WHERE usuario='".$_SESSION['correo']."'";
			$resultado=$mysqli->query($sql);
			$tabla="";
			while($row=$resultado->fetch_assoc()){
				$sql2="SELECT descripcion FROM departamentos WHERE id='".$row['dpto']."'";
				$resultado2=$mysqli->query($sql2);
				$row2=$resultado2->fetch_assoc();
				$tabla.='
				<table width="100%" border="1" id="tickets">
					<tr>
						<td colspan="7"><p style="text-align:center"><strong>'.$row2['descripcion'].'</strong></p></td>
					</tr>
					<tr>
						<td width="3%"></td>
						<td><strong>ID</strong></td>
						<td><strong>Titulo</strong></td>
						<td><strong>Solicitante</strong></td>
						<td><strong>Estado</strong></td>
						<td width="5%"></td>
						<td width="5%"></td>
					</tr>';
				$sql3="SELECT * FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['dpto']."' AND asignacion='".$_SESSION['nom_us']."'";
				if($_POST['titulo']!=''){
					$sql3.=" AND titulo LIKE '%".utf8_encode($_POST['titulo'])."%' ";
				}
				if($_POST['estado']!=''){
					$sql3.=" AND estado LIKE '%".$_POST['estado']."%'";
				}
				if($_POST['solicitante']!=''){
					$sql3.=" AND solicitante LIKE '%".$_POST['solicitante']."%'";
				}
				if($_POST['fechadesde']!=''){
					$sql3.=" AND fechacreacion BETWEEN '".$_POST['fechadesde']."' AND '".$_POST['fechahasta']."'";
				}
				$sql3.=" ORDER BY id DESC";
				$resultado3=$mysqli->query($sql3);
				$rows3=$resultado3->num_rows;
				if($rows3>0){
					while($row3=$resultado3->fetch_assoc()){
						$sql4="SELECT estado, aviso, valor FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND idticket='".$row3['id']."' AND correo='".$_SESSION['correo']."'";
						$resultado4=$mysqli->query($sql4);
						$row4=$resultado4->fetch_assoc();
						switch($row3['estado']){
							case '':
								$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
								break;
							case 0:
								if(($row4['aviso']==1)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
								}else{
									if(($row4['aviso']==2)&&($row4['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
									}else{
										$tabla.='<td title="Pendiente Por Asignar"><button><img src="../media/pendiente.png"  /></button></td>';
									}
								}
								break;
							case 1:
								if(($row4['aviso']==1)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
								}else{
									if(($row4['aviso']==2)&&($row4['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
									}else{
										$tabla.='<td title="Asignado"><button><img src="../media/asignado.png"  /></button></td>';
									}
								}
								break;
							case 2:
								if(($row4['aviso']==1)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
								}else{
									if(($row4['aviso']==2)&&($row4['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row['id'].')"><img src="../media/alerta2.png"  /></button></td>';
									}else{
										$tabla.='<td title="En Proceso"><button><img src="../media/enproceso.png"  /></button></td>';
									}
								}
								break;
							case 3:
								if(($row4['aviso']==1)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
								}else{
									if(($row4['aviso']==2)&&($row4['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
									}else{
										$tabla.='<td title="En Manos Del Cliente"><button><img src="../media/cliente.png"  /></button></td>';
									}
								}
								break;
							case 4:
								if(($row4['aviso']==1)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
								}else{
									if(($row4['aviso']==2)&&($row4['valor']==1)){
										$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
									}else{
										$tabla.='<td title="En Espera Por Terceros"><button><img src="../media/terceros.png"  /></button></td>';
									}
								}
								break;
							case 5:
								$tabla.='<td title="Anulado"><button><img src="../media/anulado.png"  /></button></td>';
								break;
							case 6:
								$tabla.='<td title="Falsa Alarma"><button><img src="../media/falsaalarma.png"  /></button></td>';
								break;
							case 7:
								$tabla.='<td title="Cerrado"><button><img src="../media/check.png"  /></button></td>';
								break;	
							case 8:
								if(($row4['aviso']==1)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(1,'.$row3['id'].')"><img src="../media/alerta1.png"  /></button></td>';
								}else{
									$tabla.='<td title="Primer alerta"><button><img src="../media/alerta1.png"  /></button></td>';
								}
								break;
							case 9:
								if(($row4['aviso']==2)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(2,'.$row3['id'].')"><img src="../media/alerta2.png"  /></button></td>';
								}else{
									$tabla.='<td title="Segunda alerta"><button><img src="../media/alerta2.png"  /></button></td>';
								}
								break;
							case 10:
								if(($row4['aviso']==3)&&($row4['valor']==1)){
									$tabla.='<td title="Detener Alerta"><button onclick="cancelaralerta(3,'.$row3['id'].')"><img src="../media/alerta3.png"  /></button></td>';
								}else{
									$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
								}
								break;	
							case 11:
								$tabla.='<td title="Vencido"><button><img src="../media/vencido.png"  /></button></td>';
								break;					
						}
						$tabla.='<td>'.$row3['id'].'</td>
								<td>'.utf8_decode($row3['titulo']).'</td>
								<td>'.utf8_decode($row3['solicitante']).'</td>
								<td>'.$row3['estado'].'</td>
								<td><button id="info" name="info" onclick="info('.$row3['id'].')"><img src="../media/info.png" width="30" height="30" alt="info" title="Solicitar informacion del Ticket" /></button></td>
								<td><button id="visualizar" name="visualizar" onclick="visualizar2('.$row3['id'].')"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar Detalles Tickets" /></button></td>
							</tr>';
					}
				}else{
					$tabla.='<tr>
						<td colspan="5">No hay tickets creados.</td>
					</tr>';
				}	
			}
			$tabla.='</table>';
			echo $tabla;
		}
	}
	if($_POST['aux']==10){
		$nuevafecha=strtotime ($_POST['fechacreacion']);
		$fecha =date('Y-m-d',$nuevafecha);
		$horas_trabajo=date('H:i:s',$nuevafecha);
		$sql="SELECT * FROM calendario WHERE fecha='$fecha'";
		$resultado=$mysqli2->query($sql);
		$rows=$resultado->num_rows;
		if($rows>0){
			echo 1;
		}else{
			$sql2="SELECT * FROM horas_trabajo";
			$resultado2=$mysqli2->query($sql2);
			$aux=0;
			while($row2=$resultado2->fetch_assoc()){	
				if(($horas_trabajo>$row2['horadesde'])&&($horas_trabajo<=$row2['horahasta'])){
					$aux++;
				}
			}
			if($aux>0){
				echo '3';
			}else{
				echo '2';
			}
		}
	}
?>