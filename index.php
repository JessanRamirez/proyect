<?php
	session_start();
	include_once("p/conexion.php");
	if (isset($_SESSION['us'])){
		header("Location: p/principal.php");
	}
	if(!isset($_SESSION['captcha'])){
		$_SESSION['captcha']=0;
		$_SESSION['error']=0;
	}
	if(!empty($_POST)){	
		$usuario=mysqli_real_escape_string($mysqli,$_POST['usuario']);
		$clave=mysqli_real_escape_string($mysqli,$_POST['clave']);
		$p=sha1($clave);
		if($_SESSION['captcha']==0){
			$sql="SELECT id, nombre, apellido, correo, nivel, idgerencia FROM usuarios WHERE correo='$usuario' AND clave='$p'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){
				unset($_SESSION['captcha']);
				unset($_SESSION['error']);
				$row=$resultado->fetch_assoc();
				$_SESSION['us']=$row['id'];
				$_SESSION['nivel']=$row['nivel'];
				$_SESSION['gerencia']=$row['idgerencia'];
				$_SESSION['correo']=$row['correo'];
				$_SESSION['nom_us']=utf8_decode($row['nombre'].' '.$row['apellido']);
				header("Location: p/principal.php");
			}else{
				$_SESSION['error']++;
				if($_SESSION['error']>=3){
					$_SESSION['captcha']=1;
				}
				$error="El correo o la clave son incorrectas, intente de nuevo.";
			}
		}else{
			$secretkey="6LeN3YcUAAAAAH8HwbKQQBwPdotTE_BoSMtIZH7X";
			$responsekey=$_POST['g-recaptcha-response'];
			//opcional
			$userIP=$_SERVER['REMOTE_ADDR'];
			$url="https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$responsekey&remote=$userIP";
			$response=file_get_contents($url);
			$response= json_decode($response);
			if($response->success){
				$sql="SELECT id, nombre, apellido, correo, nivel, idgerencia FROM usuarios WHERE correo='$usuario' AND clave='$p'";
				$resultado=$mysqli->query($sql);
				$rows=$resultado->num_rows;
				if($rows>0){
					unset($_SESSION['captcha']);
					unset($_SESSION['error']);
					$row=$resultado->fetch_assoc();
					$_SESSION['us']=$row['id'];
					$_SESSION['nivel']=$row['nivel'];
					$_SESSION['gerencia']=$row['idgerencia'];
					$_SESSION['correo']=$row['correo'];
					$_SESSION['nom_us']=$row['nombre'].' '.$row['apellido'];
					header("Location: p/principal.php");
				}else{
					$_SESSION['error']++;
					if($_SESSION['error']>=3){
						$_SESSION['captcha']=1;
					}
					$error="El correo o la clave son incorrectas, intente de nuevo.";
				}
			}else{
				$error='Debe oprimir el captcha, intente de nuevo.';
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<head>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.backstretch.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="shortcut icon" href="media/favicon.ico">
</head>
<title>Services Desk - PROSEIN</title>
	<body>
		<script>
            $(document).ready(function(e){
            $.backstretch(["media/fondo10.jpg"]);
            });
        </script>
    	<div class="banner">
        	<img src="media/descarga.png"/>
        </div>
		<div class="principal">
        	<div class="cuerpo">
             <?php
			$sql0="SELECT estado FROM mantenimiento";
			$resultado0=$mysqli->query($sql0);
			$row0=$resultado0->fetch_assoc();
			if($row0['estado']==0){?>
                <form id="login" action="" method="post">
                    <h3>SERVICES DESK</h3>
                    <div style='font-size:16px; color:#cc0000;'><?php echo isset($error)? utf8_decode($error):'';?></div>
                    <label>Correo:</label>
                    <input required="required" name="usuario" type="email" value=""/><br />
                    <label>Clave:</label>
                    <input required="required" name="clave" type="password" value="" />
                    <p>Indique su usuario y clave para entrar al Sistema.</p>
                    <?php
					if($_SESSION['captcha']==1){
					?>
                    	<center><div class="g-recaptcha" data-sitekey="6LeN3YcUAAAAACshD7-qDy2eRyDkKwBcq90rl_bk"></div></center><br/>
                    <?php
					}?>
                    <input name="login" id="login" type="submit" value="ENTRAR" />
            	</form>
                <?php
			}else{?>
				<center>
                    <img src="media/mantenimiento2.png" />
                    <h3>Lo sentimos, por favor intente luego.</h3>
                    <a href="p/administrar.php">Administrar</a>
                </center>  
            <?php
			}
			?>
            </div>
        </div>
    </body>
</html>