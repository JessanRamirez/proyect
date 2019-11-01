<?php
	date_default_timezone_set('America/Caracas');
	$mysqli=new mysqli("localhost","services_desk","Tecno2018*","services_desk"); //Servidor, usuario de la base de datos, contraseña de usuario y nombre de la base de datos
	if(mysqli_connect_error()){
		echo 'Conexión Fallida: ', mysqli_connect_error();
		exit();
	}
	function invertirfecha($fecha){
		$fecha2=$fecha[8].$fecha[9].'/'.$fecha[5].$fecha[6].'/'.$fecha[0].$fecha[1].$fecha[2].$fecha[3].' '.$fecha['11'].$fecha['12'].$fecha['13'].$fecha['14'].$fecha['15'];
		return $fecha2;
	}
	function invertirfecha2($fecha){
		$fecha2=$fecha[6].$fecha[7].$fecha[8].$fecha[9].'-'.$fecha[3].$fecha[4].'-'.$fecha[0].$fecha[1].' '.$fecha['11'].$fecha['12'].$fecha['13'].$fecha['14'].$fecha['15'];
		return $fecha2;
	}
	$hora=date("G:i:s");
	$hora2=date("G");
	$minutos=date("i");
	$ampm=date("a");
	$sql0="SELECT * FROM horas_trabajo";
	$resultado0=$mysqli->query($sql0);
	$aux=0;
	$aux2=1;
	$cant=0;
	$nuevaHora='';
	while($row0=$resultado0->fetch_assoc()){//Calculo la cantidad de minutos (30 minutos) habiles $cant
		$aux++;		
		$horaInicial=$row0['horadesde'];
		$horaFinal=$row0['horahasta'];
		$segundos_horaInicial=strtotime($horaInicial);
		$segundos_minutoAnadir=1800;//30 minutos
		$nuevaHora=$horaInicial;
		while($nuevaHora<$horaFinal){
			$segundos_horaInicial=strtotime($nuevaHora);
			$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
			$cant++;
		}
	}
	$mitadhoras=(($cant*30)/60)/2; //es la mitad de las horas habiles
	$sql="SELECT id FROM gerencias";
	$resultado=$mysqli->query($sql);
	$o=0;
	while($row=$resultado->fetch_assoc()){
		$sql2="SELECT id, fechacreacion, fechaestimada, estado, subcategoria FROM tickets WHERE idgerencia='".$row['id']."' AND estado!='Cerrado'";
		$resultado2=$mysqli->query($sql2);
		while($row2=$resultado2->fetch_assoc()){
			if(($row2['estado']=='Cerrado')or($row2['estado']=='Anulado')or($row2['estado']=='Falsa Alarma')){
				$sql6="DELETE FROM tickets_seguimiento WHERE idgerencia='".$row['id']."' AND idticket='".$row2['id']."'";
				$resultado6=$mysqli->query($sql6);
			}else{
				echo '*************************'.$row2['id'].'******************<br>';
				$datetime1 = date_create(''.$row2['fechacreacion'].'');
				$datetime2 = date_create(''.$row2['fechaestimada'].'');
				$datetime3 = date_create(''.date('Y-m-d H:i').'');
				$interval2 = date_diff($datetime1, $datetime2);		
				$dias1=$interval2->format('%R%a ');			
				$longitud2=strlen($dias1);
				$dias2='';
				for($i=1;$i<$longitud2;$i++){
					$dias2.=$dias1[$i];
				}
				if($dias2>5){
					$sql8="SELECT sla FROM sub_categorias WHERE id='".$row2['subcategoria']."'";
					$resultado8=$mysqli->query($sql8);
					$row8=$resultado8->fetch_assoc();
					$dias2=$row8['sla'];
				}
				if($datetime3>$datetime2){//Si ya la fecha es mayor directamente actualizo el estado en aviso 3
					echo "Ticket: ".$row2['id']." ya esta quemado. ".$row2['fechacreacion']." y ".$row2['fechaestimada']."<br>";
					$sql5="SELECT estado, correo FROM tickets_seguimiento WHERE idgerencia='".$row['id']."' AND idticket=".$row2['id']."";
					$resultado5=$mysqli->query($sql5);
					$rows5=$resultado5->num_rows;
					if($rows5>0){
						while($row5=$resultado5->fetch_assoc()){
							if($row5['estado']!=11){
								$sql6="UPDATE tickets_seguimiento set aviso='3', valor='1', estado='10' WHERE idgerencia='".$row['id']."' AND idticket=".$row2['id']." AND correo='".$row5['correo']."'";
								$resultado6=$mysqli->query($sql6);
							}
						}
					}
				}else{
					$i=0;
					$j=0;
					for($x=1;$x<=$dias2;$x++){
						$aux=0;
						while($aux==0){
							$i++;
							if($i==1){
								$nuevafecha = strtotime ( '+1 day' , strtotime ($row2['fechacreacion'])) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}else{
								$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
								$nuevafecha =date('d-m-Y H:i',$nuevafecha);
							}
							$day=date("D",strtotime($nuevafecha));
							$sql3="SELECT * FROM dias_laborables WHERE dia='$day'";
							$resultado3=$mysqli->query($sql3);
							$rows3=$resultado3->num_rows;
							if($rows3>0){
								$nuevafecha3=invertirfecha2($nuevafecha);
								$sql4="SELECT * FROM calendario WHERE fecha='$nuevafecha3'";
								$resultado4=$mysqli->query($sql4);
								$rows4=$resultado4->num_rows;
								if($rows4>0){
								}else{
									$j++;
									$aux=1;
								}
							}
							if($nuevafecha3==$row2['fechaestimada']){
								$x=1000;
							}
						}
					}
					//Calculo la nueva fecha
					$nuevafecha=invertirfecha2($nuevafecha);
					echo $nuevafecha.'<br>';
					if($dias2>1){
						$redondeo=ceil($j/2);//cantidad de dias
					}else{
						$redondeo=0;//cuando es 1 dia de diferencia
					}				
					$i=0;
					$j=0;
					$aux3=0;
					if($redondeo>=1){//Cuando el SLA es mayor a 1
						for($x=1;$x<=$redondeo;$x++){
							$aux=0;
							while($aux==0){
								$i++;
								if($i==1){
									$nuevafecha = strtotime ( '+1 day' , strtotime ($row2['fechacreacion'])) ;
									$nuevafecha =date('d-m-Y H:i',$nuevafecha);
								}else{
									$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
									$nuevafecha =date('d-m-Y H:i',$nuevafecha);
								}
								$day=date("D",strtotime($nuevafecha));
								$sql3="SELECT * FROM dias_laborables WHERE dia='$day'";
								$resultado3=$mysqli->query($sql3);
								$rows3=$resultado3->num_rows;
								if($rows3>0){
									$nuevafecha3=invertirfecha2($nuevafecha);
									$sql4="SELECT * FROM calendario WHERE fecha='$nuevafecha3'";
									$resultado4=$mysqli->query($sql4);
									$rows4=$resultado4->num_rows;
									if($rows4>0){
									}else{
										$j++;
										$aux=1;
									}
								}
								if($nuevafecha3==$row2['fechaestimada']){
									$x=1000;
								}
							}
						}
						$aux3=0;
						$nuevafecha=invertirfecha2($nuevafecha);
						$nuevafecha3=date('d-m-Y H:i');
						//aviso 1
						if($nuevafecha3>=$nuevafecha){
							$nuevaHora2=date('H:i:s');	
							$nuevaHora=date('H:i',strtotime($nuevafecha));	
							$aux3=0;
							if($nuevaHora<=$nuevaHora2){
								$aviso=1;
								$aux3=1;
							}
						}
						//viene aviso 2
						$dias3=$dias2-$redondeo;
						$dias3=ceil($dias3/2);//cantidad de dias
						if($aux3==1){
							//Ahora viene verificar para alerta
							$segundamitadhoras=$mitadhoras/2;
							$cantminutos2=$segundamitadhoras*2;
							for($x=1;$x<=$dias3;$x++){
								$aux=0;
								while($aux==0){
									$i++;
									$nuevafecha = strtotime ( '+1 day' , strtotime ($nuevafecha)) ;
									$nuevafecha =date('d-m-Y H:i',$nuevafecha);
									$day=date("D",strtotime($nuevafecha));
									$sql3="SELECT * FROM dias_laborables WHERE dia='$day'";
									$resultado3=$mysqli->query($sql3);
									$rows3=$resultado3->num_rows;
									if($rows3>0){
										$nuevafecha3=invertirfecha2($nuevafecha);
										$sql4="SELECT * FROM calendario WHERE fecha='$nuevafecha3'";
										$resultado4=$mysqli->query($sql4);
										$rows4=$resultado4->num_rows;
										if($rows4>0){
										}else{
											$j++;
											$aux=1;
										}
									}
									if($nuevafecha3==$row2['fechaestimada']){
										$x=1000;
									}
								}
							}
							$nuevafecha3=date('d-m-Y H:i',strtotime($nuevafecha3));
							if($nuevafecha3>=$nuevafecha){
								$nuevaHora2=date('H:i:s');	
								$nuevaHora=date('H:i',strtotime($nuevafecha));	
								$aux3=0;
								if($nuevaHora<=$nuevaHora2){
									$aviso=2;
									$aux3=1;
								}
							}
						}
					}else{
						$horaInicial = date("H:i", strtotime($row2['fechacreacion']));
						$aux2=1;
						$cantminutos=$mitadhoras*2;
						$fechacreacion=$row2['fechacreacion'];
						$nuevaHora2='';
						if(date('Y-m-d')==date("Y-m-d", strtotime($row2['fechacreacion']))){
							echo 'paso por aqui1<br>';
							for($i=1;$i<=$cantminutos;$i++){
								$aux=0;
								while($aux==0){
									if($i==1){
										$segundos_horaInicial=strtotime($horaInicial);
									}else{
										$segundos_horaInicial=strtotime($nuevaHora);
									}
									$j++;
									$segundos_minutoAnadir=1800;
									$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
									$sql6="SELECT * FROM horas_trabajo";
									$resultado6=$mysqli->query($sql6);
									while($row6=$resultado6->fetch_assoc()){	
										if(($nuevaHora>$row6['horadesde'])&&($nuevaHora<=$row6['horahasta'])){
											$aux++;
										}
										
									}
								}
							}
							$nuevaHora2=date('H:i:s');			
							$longitud4=strlen($nuevaHora);
							$longitud5=strlen($nuevaHora2);
							$aux3=0;
							if($nuevaHora<=$nuevaHora2){
								$aviso=1;
								$aux3=1;
							}
							//viene aviso 2
							if($aux3==1){
								//Ahora viene verificar para alerta
								$segundamitadhoras=$mitadhoras/2;
								$cantminutos2=$segundamitadhoras*2;
								for($i=1;$i<=$cantminutos2;$i++){
									$aux=0;
									while($aux==0){
										$segundos_horaInicial=strtotime($nuevaHora);
										$j++;
										$segundos_minutoAnadir=1800;
										$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
										$sql6="SELECT * FROM horas_trabajo";
										$resultado6=$mysqli->query($sql6);
										while($row6=$resultado6->fetch_assoc()){	
											if(($nuevaHora>$row6['horadesde'])&&($nuevaHora<=$row6['horahasta'])){
												$aux++;
											}
											
										}
									}
								}
								if($nuevaHora<=$nuevaHora2){
									$aviso=2;
								}
							}
						}else{
							$diasiguiente = strtotime ( '+1 day' , strtotime ($row2['fechacreacion'])) ;
							$diasiguiente =date('Y-m-d',$diasiguiente);
							$diasiguiente='2019-06-11';
							if(date('Y-m-d')==$diasiguiente){
								//Si es al día siguiente
								for($i=1;$i<=$cantminutos;$i++){
									$aux=0;
									while($aux==0){
										if($i==1){
											$segundos_horaInicial=strtotime($horaInicial);
										}else{
											$segundos_horaInicial=strtotime($nuevaHora);
										}
										$j++;
										$segundos_minutoAnadir=1800;
										$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
										$sql6="SELECT * FROM horas_trabajo";
										$resultado6=$mysqli->query($sql6);
										while($row6=$resultado6->fetch_assoc()){	
											if(($nuevaHora>$row6['horadesde'])&&($nuevaHora<=$row6['horahasta'])){
												$aux++;
											}
											
										}
									}
								}
								$nuevaHora2=date('H:i:s');			
								$longitud4=strlen($nuevaHora);
								$longitud5=strlen($nuevaHora2);
								$aux3=0;
								if($nuevaHora<=$nuevaHora2){
									$aviso=1;
									$aux3=1;
								}
								//viene aviso 2
								if($aux3==1){
									//Ahora viene verificar para alerta
									$segundamitadhoras=$mitadhoras/2;
									$cantminutos2=$segundamitadhoras*2;
									for($i=1;$i<=$cantminutos2;$i++){
										$aux=0;
										while($aux==0){
											$segundos_horaInicial=strtotime($nuevaHora);
											$j++;
											$segundos_minutoAnadir=1800;
											$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
											$sql6="SELECT * FROM horas_trabajo";
											$resultado6=$mysqli->query($sql6);
											while($row6=$resultado6->fetch_assoc()){	
												if(($nuevaHora>$row6['horadesde'])&&($nuevaHora<=$row6['horahasta'])){
													$aux++;
												}
												
											}
										}
									}
									if($nuevaHora<=$nuevaHora2){
										$aviso=2;
									}
								}
							}
						}
					}
					if($aux3==1){//Si es igual a 1 es debido a que se genero una de las alertas
						$sql6="SELECT aviso, correo FROM tickets_seguimiento WHERE idgerencia='".$row['id']."' AND idticket='".$row2['id']."'";
						$resultado6=$mysqli->query($sql6);
						while($row6=$resultado6->fetch_assoc()){
							if($aviso<$row6['aviso']){
								echo "Ticket: ".$row2['id']." correo: ".$row6['correo']." No actualizo.<br>";
							}else{
								$sql7="UPDATE tickets_seguimiento SET aviso='$aviso', valor='1' WHERE idgerencia='".$row['id']."' AND idticket='".$row2['id']."' AND correo='".$row6['correo']."'";
								$resultado7=$mysqli->query($sql7);
								echo $sql7.'<br>';
							}
						}
					}else{
						echo "Ticket: ".$row2['id']." No es la fecha.<br>";
					}
				}	
			}
		}
	}
?>