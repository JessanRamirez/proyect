<?php
	include("con_verificarsession.php");
	include("../conexion.php");
?>
	<p style="text-align:center"><strong>Días Laborables</strong></p>
    <table width="100%" border="1">
    	<tr>
        	<td>Lunes</td>
            <td>Martes</td>
            <td>Miercoles</td>
            <td>Jueves</td>
            <td>Viernes</td>
            <td>Sabado</td>
            <td>Domingo</td>
        </tr>
        <tr>
        	<?php 
			$sql="SELECT * FROM dias_laborables WHERE dia='Mon'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia1" name="dia1" value="Mon" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia1" name="dia1" value="Mon"></td>
			<?php	
			}
			$sql="SELECT * FROM dias_laborables WHERE dia='Tue'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia2" name="dia2" value="Tue" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia2" name="dia2" value="Tue"></td>
			<?php	
			}
			$sql="SELECT * FROM dias_laborables WHERE dia='Wed'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia3" name="dia3" value="Wed" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia3" name="dia3" value="Wed"></td>
			<?php	
			}
			$sql="SELECT * FROM dias_laborables WHERE dia='Thu'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia4" name="dia4" value="Thu" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia4" name="dia4" value="Thu"></td>
			<?php	
			}
			$sql="SELECT * FROM dias_laborables WHERE dia='Fri'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia5" name="dia5" value="Fri" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia5" name="dia5" value="Fri"></td>
			<?php	
			}
			$sql="SELECT * FROM dias_laborables WHERE dia='Sat'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia6" name="dia6" value="Sat" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia6" name="dia6" value="Sat"></td>
			<?php	
			}
			$sql="SELECT * FROM dias_laborables WHERE dia='Sun'";
			$resultado=$mysqli->query($sql);
			$rows=$resultado->num_rows;
			if($rows>0){?>
            	<td><input type="checkbox" id="dia7" name="dia7" value="Sun" checked></td>
			<?php	
			}else{?>
            	<td><input type="checkbox" id="dia7" name="dia7" value="Sun"></td>
			<?php	
			}
			?>
        </tr>
        <tr>
        	<td colspan="7"><button onClick="actualizardias()"><img src="../media/actualizar.png" title="Actualizar días laborables"></button></td>
        </tr>
    </table><br>
    <p style="text-align:center"><strong>Horas de trabajo</strong></p>
    <table width="100%" border="1"  id="horas" name="horas">
	    <tr>
        	<td>Hora desde:</td>
            <td>Hora hasta:</td>
            <td width="5%"></td>
        </tr>
        <?php
		$sql0="SELECT * FROM horas_trabajo";
		$resultado0=$mysqli->query($sql0);
		$rows0=$resultado0->num_rows;
		if($rows0>0){
			while($row0=$resultado0->fetch_assoc()){?>
            <tr>
                <td><?php echo $row0['horadesde']?></td>
                <td><?php echo $row0['horahasta']?></td>
                <td width="5%"><button onClick="eliminarhorario(<?php echo $row0['id'];?>)"><img src="../media/del01.png" title="Borrar rango de horas"></button></td>
			</tr>
        <?php
			}
		}else{?>
    	    <tr>
	        	<td colspan="7">No hay datos</td>
            </tr>
        <?php
		}
		?>
        </tr>
    </table><br />
    <table width="100%" border="1">
    	<tr>
        	<td colspan="7"><strong>Agregar Hora</strong></td>
        </tr>
    	<tr>
        	<td>Hora desde:</td>
            <td>Hora hasta:</td>
            <td width="5%"></td>
        </tr>
        <tr>
        	<td><input type="time" min="1" max="12" id="horadesde" name="horadesde"/></td>
            <td><input type="time" min="1" max="12" id="horahasta" name="horahasta"/></td>
            <td><button onclick="agregarhorario()"><img src="../media/add_rule.png" title="Agregar horario"/></button></td>
        </tr>
    </table>
    <p style="text-align:center"><strong>Días Festivos</strong></p>
    <table width="100%" border="1">
	    <tr>
        	<td align="right"></td>
        	<td width="8%"><strong>Año</strong></td>
            <td align="left"></td>
        </tr>
    	<tr>
        	<td align="right"><button onClick="atrasyear(ano.value)"><img src="../media/atras2.png"></button></td>
        	<td><input type="text" readonly id="ano" name="ano" value="<?php echo date('Y');?>" style="background:none; border:0"></td>
            <td align="left"><button onClick="siguienteyear(ano.value)"><img src="../media/siguiente.png"></button></td>
        </tr>
    </table><br>
    <table width="100%" border="1" id="diafestivo" name="diafestivo">
        <tr>
            <td><strong>Fecha</strong></td>
            <td width="5%"><strong>-</strong></td>
        </tr>
        <?php
        $sql2="SELECT * FROM calendario WHERE ano='".date('Y')."'order by fecha desc";
        $resultado2=$mysqli->query($sql2);
        $rows2=$resultado2->num_rows;
        if($rows2>0){
            while($row2=$resultado2->fetch_assoc()){?>
             <tr>
                <td><?php echo $row2['fecha'];?></td>
                <td><button onClick="eliminarfecha(<?php echo $row2['id'];?>)"><img src="../media/del01.png" title="Borrar día festivo"></button></td>
            </tr>
            <?php	
            }
        }else{
        ?>
        <tr>
            <td colspan="2">No hay días festivos.</td>
        </tr>
        <?php
        }?>
    </table><br><br>
    <table width="100%" border="1">
    	<tr>
        	<td colspan="2"><strong>Agregar fecha festiva.</strong></td>
        </tr>
    	<tr>
        	<td><input type="date" id="fechafestiva" name="fechafestiva"></td>
            <td width="5%"><button id="agregardiafestivo" name="agregardiafestivo" onClick="agregardiafestivo(fechafestiva.value, ano.value)"><img src="../media/add_rule.png"></button></td>
        </tr>
    </table>