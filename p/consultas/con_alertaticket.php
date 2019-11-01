<?php
	session_start();
	include("../conexion.php");
	if($_POST['aux']==1){
		$alertas=1;
		$aux=0;
		$sql="SELECT * FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND aviso='1' AND valor='1' AND correo='".$_SESSION['correo']."' order by idticket";
		$resultado=$mysqli->query($sql);
		$rows=$resultado->num_rows;
		if($rows>0){
			$aux++;
			$alertas="";
			$alertas="Estimado usuario, los siguientes tickets acaban de cumplir la mitad del tiempo asignado:";
			while($row=$resultado->fetch_assoc()){
				$sql2="SELECT titulo FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND id='".$row['idticket']."'";
				$resultado2=$mysqli->query($sql2);
				$row2=$resultado2->fetch_assoc();
				$alertas.="\nTicket: ".$row['idticket']." - Título: ".utf8_decode($row2['titulo']);
			}
			$alertas.="\n\n";
		}
		$sql3="SELECT * FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND aviso='2' AND valor='1' AND correo='".$_SESSION['correo']."' order by idticket";
		$resultado3=$mysqli->query($sql3);
		$rows3=$resultado3->num_rows;
		if($rows3>0){
			if($aux==0){
				$alertas="";
				$aux++;
			}
			$alertas.="Estimado usuario, los siguientes tickets acaban de cumplir la segunda mitad del tiempo asignado:\n";
			while($row3=$resultado3->fetch_assoc()){
				$sql4="SELECT titulo FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND id='".$row3['idticket']."'";
				$resultado4=$mysqli->query($sql4);
				$row4=$resultado4->fetch_assoc();
				$alertas.="\nTicket: ".$row3['idticket']." - Título: ".utf8_decode($row4['titulo']);
			}
			$alertas.="\n\n";
		}
		$sql5="SELECT * FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND aviso='3' AND valor='1' AND correo='".$_SESSION['correo']."' order by idticket";
		$resultado5=$mysqli->query($sql5);
		$rows5=$resultado5->num_rows;
		if($rows5>0){
			if($aux==0){
				$alertas="";
				$aux++;
			}
			$alertas.="Estimado usuario, los siguientes tickets acaban de cumplir todo el tiempo asignado:";
			while($row5=$resultado5->fetch_assoc()){
				$sql6="SELECT titulo FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND id='".$row5['idticket']."'";
				$resultado6=$mysqli->query($sql6);
				$row6=$resultado6->fetch_assoc();
				$alertas.="\nTicket: ".$row5['idticket']." - Título: ".utf8_decode($row6['titulo']);
			}
			$alertas.="\n\n";
		}
		echo $alertas;
	}else{
		$aviso=$_POST['aviso'];
		if($aviso<3){
			$aviso++;
		}
		$sql="SELECT estado FROM tickets_seguimiento WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$_SESSION['correo']."' AND idticket='".$_POST['idticket']."' ";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		if($row['estado']==10){
			$sql2="UPDATE tickets_seguimiento SET aviso='$aviso', valor='0', estado='11' WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$_SESSION['correo']."' AND idticket='".$_POST['idticket']."' ";
		}else{
			$sql2="UPDATE tickets_seguimiento SET aviso='$aviso', valor='0' WHERE idgerencia='".$_SESSION['gerencia']."' AND correo='".$_SESSION['correo']."' AND idticket='".$_POST['idticket']."' ";
		}
		$resultado2=$mysqli->query($sql2);
	}
?>