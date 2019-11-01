<?php
	include("con_verificarsession.php");
	include("../conexion.php");
	include("../../phpmailer/class.phpmailer.php");
	$sql="SELECT usuario FROM usuarios_dptos WHERE idgerencia='".$_SESSION['gerencia']."' AND dpto='1'";
	$resultado=$mysqli->query($sql);
	$sql2="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
	$resultado2=$mysqli->query($sql2);
	$row2=$resultado2->fetch_assoc();
	$hora=getdate();
	while($row=$resultado->fetch_assoc()){
		$sql3="SELECT nombre, apellido FROM usuarios WHERE correo='".$row['usuario']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado3=$mysqli->query($sql3);
		$row3=$resultado3->fetch_assoc();
		if(($row3['nombre']=='Services') and ($row3['apellido']=='Desk')){
			$cuerpo='
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				</head>
				<body>';
				if($hora['hours']<12){
					$cuerpo.='<p>Buen dia,</p>';
				}else{
					$cuerpo.='<p>Buenas tardes,</p>';
				}
				$cuerpo.='
				<p><strong>El usuario: </strong>'.$_SESSION['nom_us'].', <strong>correo: </strong>'.$_SESSION['correo'].'</p>
				<p>Ha generado un solicitud de informacion relacionada con el Ticket N°: '.$_POST['id'].'.</p>
				<p>Agredeciendo su pronta colaboracion</p>
				<p><img src="http://prsbousa.eastus2.cloudapp.azure.com/prosein/firma.png" width="464" height="214" /></p>
				</body>
			</html>';
			// PHPMAILER
			$mail = new PHPMailer();
			$mail->IsSMTP(); 					// set mailer to use  SMTP
			$mail->Host = "mail.prosein.com.ve"; 			// specify main and backup server
			$mail->Port	  = "587";             			// set the SMTP server port
			$mail->SMTPSecure = "tls";				// set the encryption. - mbilotti.
			$mail->SMTPAuth = "true";				// turn on SMTP authentication
			$mail->Username = "info@grupoprosein.com";
			$mail->Password = "rmNN2vDcUaEu"; 
			$mail->From = "servicesdesk@prosein.com";
			$mail->FromName = "Sistema de Tickets De ".$row2['descripcion']."";
			$mail->AddAddress ("".$row['usuario']."");
			$mail->WordWrap = 50; // set word wrap to 50 characters
			$mail->IsHTML(true); // set email format to HTML
			$mail->Subject = "Solicitud de informacion - Ticket ".$_POST['id']." De la ".$row2['descripcion']."";
			$mail->Body  = utf8_decode($cuerpo);
			if(!$mail->Send()) {
				echo("Fall&oacute; env&iacute;o mail confirmaci&oacute;n");
				echo "Error: " . $mail->ErrorInfo;
			}else{
				echo "Enviado";
			}
		}
	}
?>