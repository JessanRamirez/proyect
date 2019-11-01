<?php
	include("../../PHPExcel-1.8/Classes/PHPExcel.php");
	include("../conexion.php");
	session_start();
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
		getProperties()
			->setCreator("Dpto Tecnología")
			->setLastModifiedBy("Dpto Tecnología")
			->setTitle("UsoSistema")
			->setSubject("Services Desk")
			->setDescription("Documento generado con PHPExcel")
			->setKeywords("Services Desk")
			->setCategory("Services Desk");
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	$styleArray = array(
		'font' => array(
			'bold' => true
		));
	if($_GET['aux']==1){
		$nombre='Resumen de casos';
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', '')
				->setCellValue('B1', 'Activos')
				->setCellValue('C1', 'Cerrados')
				->setCellValue('D1', 'En Manos del Cliente')
				->setCellValue('E1', 'En epsera por Terceros');
		$objPHPExcel->getActiveSheet(0)->getStyle('A1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('B1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('C1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('D1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('E1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setAutoSize(true);
		$sql="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
        $resultado=$mysqli->query($sql);
		$i=2;
        while($row=$resultado->fetch_assoc()){
			$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado!='Cerrado' AND estado!='En Manos del Cliente' AND estado!='Falsa Alarma' AND estado!='Anulado' AND estado!='En espera por terceros' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado2=$mysqli->query($sql2);
			$row2=$resultado2->fetch_assoc();
			$sql3="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado='Cerrado' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado3=$mysqli->query($sql3);
			$row3=$resultado3->fetch_assoc();
			$sql4="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado='En espera por terceros' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado4=$mysqli->query($sql4);
			$row4=$resultado4->fetch_assoc();
			$sql5="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND departamento='".$row['id']."' AND estado='En Manos del Cliente' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado5=$mysqli->query($sql5);
			$row5=$resultado5->fetch_assoc();
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, ''.$row['descripcion'].'')
				->setCellValue('B'.$i, ''.$row2['COUNT(*)'].'')
				->setCellValue('C'.$i, ''.$row3['COUNT(*)'].'')
				->setCellValue('D'.$i, ''.$row5['COUNT(*)'].'')
				->setCellValue('E'.$i, ''.$row4['COUNT(*)'].'');
			$objPHPExcel->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
			$i++;
		}
		
	}
	if($_GET['aux']==2){
		$nombre='Tickets Más Frecuentes';
		$sql="SELECT tickets.subcategoria as id, sub_categorias.descripcion as descripcion, categorias.descripcion as descripcion2, departamentos.descripcion as dpto FROM tickets INNER JOIN sub_categorias ON sub_categorias.id=tickets.subcategoria INNER JOIN categorias ON tickets.categoria=categorias.id INNER JOIN departamentos ON tickets.departamento=departamentos.id WHERE tickets.idgerencia='".$_SESSION['gerencia']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY tickets.subcategoria";
		$resultado=$mysqli->query($sql);
		$i=0;
		while($row=$resultado->fetch_assoc()){
			$tid[$i]=$row['id'];
			$tdes[$i]=$row['descripcion'];
			$tdes2[$i]=$row['descripcion2'];
			$tdes3[$i]=$row['dpto'];
			$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND subcategoria='".$row['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado2=$mysqli->query($sql2);
			$row2=$resultado2->fetch_assoc();
			$tnum[$i]=$row2['COUNT(*)'];
			$i++;
		}
		function burbuja($tnum,$tid,$tdes, $tdes2, $tdes3){
			for($i=1;$i<count($tnum);$i++)
			{
				for($j=0;$j<count($tnum)-$i;$j++)
				{
					if($tnum[$j]<$tnum[$j+1])
					{
						$k=$tnum[$j+1];
						$l=$tid[$j+1];
						$p=$tdes[$j+1];
						$s=$tdes2[$j+1];
						$o=$tdes3[$j+1];
						
						$tnum[$j+1]=$tnum[$j];
						$tid[$j+1]=$tid[$j];
						$tdes[$j+1]=$tdes[$j];
						$tdes2[$j+1]=$tdes2[$j];
						$tdes3[$j+1]=$tdes3[$j];
						
						$tnum[$j]=$k;
						$tid[$j]=$l;
						$tdes[$j]=$p;
						$tdes2[$j]=$s;
						$tdes3[$j]=$o;
					}
				}
			}
		 
			return array($tnum, $tid, $tdes, $tdes2, $tdes3);
		}
		$arrayB=burbuja($tnum, $tid, $tdes, $tdes2, $tdes3);
		$arrayC=$arrayB[0];
		$arrayD=$arrayB[1];
		$arrayE=$arrayB[2];
		$arrayX=$arrayB[3];
		$arrayF=$arrayB[4];
		if(count($arrayC)>10){
			$num=10;
		}else{
			$num=count($arrayC);
		}
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Pos.')
				->setCellValue('B1', 'Dpto')
				->setCellValue('C1', 'Categoría')
				->setCellValue('D1', 'Subcategoría')
				->setCellValue('E1', 'Cantidad');
		$objPHPExcel->getActiveSheet(0)->getStyle('A1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('B1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('C1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('D1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('E1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setAutoSize(true);
		$j=2;
		for($i=0;$i<$num;$i++){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j, ''.($i+1).'')
				->setCellValue('B'.$j, ''.utf8_decode($arrayF[$i]).'')
				->setCellValue('C'.$j, ''.utf8_decode($arrayX[$i]).'')
				->setCellValue('D'.$j, ''.utf8_decode($arrayE[$i]).'')
				->setCellValue('E'.$j, ''.$arrayC[$i].'');
			$j++;
		}
	}
	if($_GET['aux']==3){
		$nombre='Estatus de Casos';
		$sql="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
		$i=66;
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Categoría/SubCategoría');
		$objPHPExcel->getActiveSheet(0)->getStyle('A1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
		while($row=$resultado->fetch_assoc()){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue(''.chr($i).'1', utf8_decode($row['descripcion']));
			$objPHPExcel->getActiveSheet(0)->getStyle(''.chr($i).'1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet(0)->getColumnDimension(''.chr($i).'')->setAutoSize(true);
			$i++;
		}
		$sql2="SELECT sub_categorias.id as idsub, sub_categorias.descripcion as descripcion, categorias.descripcion as descripcion2 FROM tickets INNER JOIN sub_categorias ON sub_categorias.id=tickets.subcategoria INNER JOIN categorias ON tickets.categoria=categorias.id WHERE tickets.idgerencia='".$_SESSION['gerencia']."'  AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY tickets.subcategoria";
		$resultado2=$mysqli->query($sql2);
		$j=2;
		while($row2=$resultado2->fetch_assoc()){
			$i=65;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue(''.chr($i).$j, utf8_decode($row2['descripcion2'].' / '.$row2['descripcion']));?>
			<?php
            $sql3="SELECT id FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
            $resultado3=$mysqli->query($sql3);
            while($row3=$resultado3->fetch_assoc()){
				$i++;
                $sql4="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND subcategoria='".$row2['idsub']."' AND departamento='".$row3['id']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
                $resultado4=$mysqli->query($sql4);
                $row4=$resultado4->fetch_assoc();
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue(''.chr($i).$j, $row4['COUNT(*)']);  
            }
			$j++;
		}
	}
	if($_GET['aux']==5){
		$nombre='Medio de Recepción de casos';
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Método de Recepción')
				->setCellValue('B1', 'Cantidad');
		$objPHPExcel->getActiveSheet(0)->getStyle('A1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getStyle('B1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setAutoSize(true);
		$sql="SELECT recepcion FROM tickets  WHERE idgerencia='".$_SESSION['gerencia']."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."' GROUP BY recepcion";
		$resultado=$mysqli->query($sql);
		$i=2;
		while($row=$resultado->fetch_assoc()){
			$descripcion=utf8_decode($row['recepcion']);
			$sql2="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND recepcion='".utf8_encode($descripcion)."' AND fechacreacion BETWEEN '".$_GET['fecha']."' AND '".$_GET['fecha2']."'";
			$resultado2=$mysqli->query($sql2);
			$row2=$resultado2->fetch_assoc();
			$cant=$row2['COUNT(*)'];
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $descripcion)
				->setCellValue('B'.$i, $cant);
				$i++;
		}
	}
	if($_GET['aux']==6){
		$nombre='Resumen por participantes';
		$sql="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
		$i=66;
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', '');
		$objPHPExcel->getActiveSheet(0)->getStyle('A1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
		while($row=$resultado->fetch_assoc()){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue(''.chr($i).'1', utf8_decode($row['descripcion']));
			$objPHPExcel->getActiveSheet(0)->getStyle(''.chr($i).'1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet(0)->getColumnDimension(''.chr($i).'')->setAutoSize(true);
			$i++;
		}
		$sheet=$objPHPExcel->getActiveSheet(0);
		$sql2="SELECT id, nombre, apellido FROM usuarios WHERE idgerencia='".$_SESSION['gerencia']."' AND activo='0' AND id>1 AND nombre!='Services'";
		$resultado2=$mysqli->query($sql2);
		$j=2;
		while($row2=$resultado2->fetch_assoc()){
			$i=65;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue(''.chr($i).$j, utf8_decode($row2['nombre'].' '.$row2['apellido']));
			$sql3="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
			$resultado3=$mysqli->query($sql3);
			while($row3=$resultado3->fetch_assoc()){
				$i++;
				$sql4="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND estado='Cerrado' AND departamento='".$row3['id']."' AND idasignacion='".$row2['id']."'";
				$resultado4=$mysqli->query($sql4);
				$row4=$resultado4->fetch_assoc();
				$sql5="SELECT COUNT(*) FROM tickets WHERE idgerencia='".$_SESSION['gerencia']."' AND estado='Cerrado' AND departamento='".$row3['id']."'";
				$resultado5=$mysqli->query($sql5);
				$row5=$resultado5->fetch_assoc();
				if($row4['COUNT(*)']!=0){
					$porc=($row4['COUNT(*)']*100)/$row5['COUNT(*)'];
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(''.chr($i).$j, $row4['COUNT(*)'].' ('.number_format($porc,2).'%)');
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(''.chr($i).$j, $row4['COUNT(*)']);
				}
			}
			$j++;
		}
	}
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nombre.'.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	ob_end_clean();
	$objWriter->save('php://output');
	exit;
?>