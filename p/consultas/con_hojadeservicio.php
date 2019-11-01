<?php
	session_start();
	function invertirfecha($fecha){
		$fecha2=$fecha[8].$fecha[9].'/'.$fecha[5].$fecha[6].'/'.$fecha[0].$fecha[1].$fecha[2].$fecha[3];
		return $fecha2;
	}
	function invertirfecha2($fecha){
		$fecha2=$fecha[6].$fecha[7].$fecha[8].$fecha[9].'-'.$fecha[3].$fecha[4].'-'.$fecha[0].$fecha[1];
		return $fecha2;
	}
	include("../conexion.php");
//	require_once '../../dompdf/autoload.inc.php';
	include("../../dompdf/autoload.inc.php");
	use Dompdf\Dompdf;
	$sql="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
	$resultado=$mysqli->query($sql);
	$row=$resultado->fetch_assoc();
	$sql2="SELECT * FROM tickets WHERE id='".$_GET['id']."'";
	$resultado2=$mysqli->query($sql2);
	$row2=$resultado2->fetch_assoc();
	$rows2=$resultado2->num_rows;
	if($rows2>0){
		if($row2['opc']==1){
			$idusuario=$row2['idsol'];
			$nombre=$row2['solicitante'];
			$sql3="SELECT cargos.nombre as cargo, gerencias.nombre as gerencia FROM usuarios INNER JOIN cargos ON usuarios.cargo=cargos.id INNER JOIN gerencias ON usuarios.gerencia=gerencias.id WHERE usuarios.id='".$idusuario."'";
			$resultado3=$mysqli2->query($sql3);
			$row3=$resultado3->fetch_assoc();
			$cargo=$row3['cargo'];
			$gerencia=$row3['gerencia'];
			$sql4="SELECT comentarios FROM tickets_detalles WHERE idticket='".$_GET['id']."' AND idgerencia='".$_SESSION['gerencia']."' ORDER BY fecha DESC";
			$resultado4=$mysqli->query($sql4);
			$row4=$resultado4->fetch_assoc();
		}else{
			$nombre=$row2['solicitante'];
			$cargo='';
			$gerencia='';
			$sql4="SELECT comentarios FROM tickets_detalles WHERE idticket='".$_GET['id']."' AND idgerencia='".$_SESSION['gerencia']."' ORDER BY fecha DESC";
			$resultado4=$mysqli->query($sql4);
			$row4=$resultado4->fetch_assoc();
		}
		if($row2['fechacierre']!='0000-00-00 00:00:00'){
			$fecha=invertirfecha($row2['fechacierre']);
		}else{
			$fecha=date('d/m/Y');
		}
		$content = '<html>';
			$content .= '<head>';
				$content .= '<style>';
				$content .= '</style>';
			$content .= '</head>';
			$content .= '<body style="font-size:12px">';
				$content .= '<table border="1" width="100%">
								<tr>
									<td colspan="6">
										<img src="../../media/logo.png"><br>'.utf8_decode($row['descripcion']).'<br>
									</td>
								</tr>
								<tr>
									<td colspan="6">    
										<center><strong>Hoja de Servicio</strong></center>
									</td>
								</tr>
								<tr>
									<td colspan="6">
										<strong><center>1. INFORMACIÓN DEL USUARIO</center></strong>
									</td>
								</tr>
								<tr>
									<td colspan="2">'.utf8_decode($nombre).'</td>
									<td colspan="2">'.utf8_decode($cargo).'</td>
									<td colspan="2">'.$fecha.'</td>
								</tr>
								<tr>
									<td colspan="2">'.utf8_decode($gerencia).'</td>
									<td colspan="3">'.utf8_decode($row2['oficina']).'</td>
									<td></td>
								</tr>
							</table><br />
							<table border="1" width="100%">
								<tr>
									<td colspan="4">
										<strong><center>2. INFORMACIÓN DEL SOPORTE</center></strong>
									</td>
								</tr>
								<tr>
									<td colspan="2">Reporte N°'.$_GET['id'].'</td>
									<td colspan="2" width="50%">Atendido por: '.utf8_decode($row2['solicitante']).'</td>
								</tr>
								<tr>
									<td colspan="4">
										OBSERVACIONES<br /><br />
									</td>
								</tr>
								<tr>
									<td colspan="4">Copia de Seguridad<br></td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="radio" id="respaldo"><label for="seguridad">Mis Doc. / Escritorio</label> <br />
									</td>
									<td colspan="2">
										<input type="radio" id="respaldo2"><label for="respaldo2">NAS</label>
										<input type="radio" id="respaldo3"><label for="respaldo3">Disco Duro Externo</label>
									</td>
								</tr>
								<tr>
									<td colspan="4">
										OBSERVACIONES<br /><br />
									</td>
								</tr>
							</table><br />
							<table border="1" width="100%">
								<tr>
									<td colspan="4">
										<strong><center>3. ACERCA DE LA EJECUCIÓN DEL TRABAJO</center></strong>
									</td>
								</tr>
								<tr>
									<td colspan="4">Leyenda: 1)Nada Conforme. 2)Algo conforme. 3)Conforme. 4)Más que conforme.</td>
								</tr>
								<tr>
									<td colspan="2">Tiempo de atención de su solicitud: <label for="tiempo">1</label><input type="radio" id="tiempo"><label for="tiempo">2</label><input type="radio" id="tiempo"><label for="tiempo">3</label><input type="radio" id="tiempo"><label for="tiempo">4</label><input type="radio" id="tiempo"></td>
									<td colspan="2">Cómo califica el soporte recibido: <label for="tiempo">1</label><input type="radio" id="tiempo"><label for="tiempo">2</label><input type="radio" id="tiempo"><label for="tiempo">3</label><input type="radio" id="tiempo"><label for="tiempo">4</label><input type="radio" id="tiempo"></td>
								</tr>	
								<tr>
									<td colspan="4">Cómo se siente al respecto de la atención recibida: <label for="tiempo">1</label><input type="radio" id="tiempo"><label for="tiempo">2</label><input type="radio" id="tiempo"><label for="tiempo">3</label><input type="radio" id="tiempo"><label for="tiempo">4</label><input type="radio" id="tiempo"></td>
								</tr>
								<tr>
									<td colspan="4">
										OBSERVACIONES<br /><br />
									</td>
								</tr>
							</table><br />
							<table width="100%" border="1">
								<tr>
									<td colspan="8">
										Recuerde firmar su solicitud en la parte de abajo. Favor no escribir en la siguiente sección, es para uso exclusivo del área de Soporte Técnico
									</td>
								</tr>
								<tr>
									<td colspan="8">
										<strong><center>4. DIAGNOSTICO Y SOLUCIONES</center></strong>
									</td>
								</tr>
								<tr>
									<td colspan="5">DIAGNOSTICO<br />'.utf8_decode($row2['titulo']).'<br /></td>
									<td width="5%">DÍA<br/> <br/></td>
									<td width="5%">MES<br/> <br/></td>
									<td width="5%">AÑO<br/> <br/></td>
								</tr>
								<tr>
									<td colspan="5">SOLUCIÓN<br />'.utf8_decode($row4['comentarios']).'<br /></td>
									<td width="5%"><br/> <br/></td>
									<td width="5%"><br/> <br/></td>
									<td width="5%"><br/> <br/></td>
								</tr>
								<tr>
									<td colspan="8">OBSERVACIONES<br></td>
								</tr>
								<tr>
									<td colspan="3">FIRMA DEL USUARIO<br><br></td>
									<td colspan="5">SOPORTE TÉCNICO<br><br></td>
								</tr>
							</table>';
			$content .= '</body>';
		$content .= '</html>';
		//echo $content;
		$dompdf = new Dompdf();
		$dompdf->loadHtml($content);
		$dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
		$dompdf->render(); // Generar el PDF desde contenido HTML
		$pdf = $dompdf->output(); // Obtener el PDF generado
		$dompdf->stream('Hoja de servicio - '.$_GET['id']); // Enviar el PDF generado al navegador y se le asigna el nombre al archivo
	}
?>