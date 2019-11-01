<?php
	include("con_verificarsession.php");
	include("../conexion.php");
	if($_POST['aux']==1){//Ver lista de categorias
		$sql="SELECT * FROM categorias WHERE iddpto='".$_POST['dpto']."' AND idgerencia='".$_SESSION['gerencia']."'";
		$resultado=$mysqli->query($sql);
		$rows=$resultado->num_rows;?>
        <br><br>
        <table width="100%" border="1">
        <?php
		if($rows>0){?>
        	<tr>
            	<td><strong>Descripción</strong></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
            </tr>
		<?php	
			while($row=$resultado->fetch_assoc()){?>
	            <tr>
            	<td><?php echo utf8_decode($row['descripcion']);?></td>
                <?php if($row['descripcion']=='Ticket Devuelto'){?>
                	<td></td>
                    <td></td>
                    <td></td>
                <?php
				}else{?>
                <td><button id="visualizar" name="visualizar" onclick="visualizar(<?php echo $row['id']?>)"><img src="../media/lupa.png" width="30" height="30" alt="visualizar" title="Visualizar SubCategorias" /></button></td>
                <td><button id="editar" name="editar" onclick="editar4(<?php echo $row['id']?>)"><img src="../media/edit_rule.png" width="30" height="30" alt="editar" title="Editar" /></button></td>
                <td><button id="eliminar" name="eliminar" onclick="eliminar4(<?php echo $row['id']?>)"><img src="../media/del01.png" width="30" height="30" alt="eliminar" title="Eliminar" /></button></td>
                <?php
				}
				?>
            </tr>
			<?php	
			}
		}else{?>
	        <tr>
            	<td><strong>Descripción</strong></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
            </tr>
        	<tr>
            	<td colspan="4">No hay categorias creadas para el departamento.</td>
            </tr>
		<?php	
		}?>
        </table><br>
        <table width="100%" border="1" id="mod" name="mod" style="display:none;">
        </table>
        <table width="100%" border="1" id="visual" name="visual" style="display:none;">
        </table><br>
        <table width="100%" border="1" id="mod2" name="mod2" style="display:none;">
        </table><br>
        <table width="100%" border="1" id="visual2" name="visual2" style="display:none;">
        	<tr>
            	<td><strong>Crear subcategoria</strong></td>
            </tr>
        </table>
        <table width="100%" border="1" id="visual3" name="visual3" style="display:none;">    
            <tr>
            	<td><strong>Descripcion</strong></td>
                <td><strong>SLA (En días)</strong></td>
                <td width="5%"></td>
            </tr>
            <tr>
            	<td><input type="text" id="subcategorianew" name="subcategorianew" style="width:99%" ></td>
                <td><input type="number" min="1" id="slanew" name="slanew" style="width:99%" onkeypress="return valida(event)"></td>
                <td><button id="agregar" name="agregar" onclick="agregar5(subcategorianew.value,slanew.value)"><img src="../media/add_rule.png" width="30" height="30" alt="agregar" title="agregar" /></button></td>
            </tr>
        </table>
        <br><br>
		<table width="100%" border="1">
        	<tr>
            	<td><strong>Crear categoria</strong></td>
                <td width="5%"></td>
            </tr>
            <tr>
            	<td><input type="text" id="categorianew" name="categorianew" style="width:99%" </td>
                <td><button id="agregar" name="agregar" onclick="agregar4(categorianew.value)"><img src="../media/add_rule.png" width="30" height="30" alt="agregar" title="agregar" /></button></td>
            </tr>
        </table>
        <?php
	}
	if($_POST['aux']==2){
		$sql="SELECT descripcion FROM categorias WHERE id='".$_POST['idcategoria']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$tabla='';
		$tabla.='<tr>
				<td><strong>Modificar Descripción</strong></td>
				<td width="5%"></td>
			</tr>
			<tr>
				<td><input type="text" id="descripcionmod" name="descripcionmod" value="'.utf8_decode($row['descripcion']).'" style="width:99%" onkeypress="return validaletra(event)"/></td>
				<td width="5%"><button onclick="actualizar4(descripcionmod.value,'.$_POST['idcategoria'].' )"><img src="../media/actualizar.png"/></button></td>
			</tr>';
		echo $tabla;
	}
	if($_POST['aux']==3){
		$sql="DELETE FROM categorias WHERE id='".$_POST['idcategoria']."'";
		$resultado=$mysqli->query($sql);
		$sql2="DELETE FROM sub_categorias WHERE idcategoria='".$_POST['idcategoria']."'";
		$resultado2=$mysqli->query($sql2);
	}
	if($_POST['aux']==4){
		$sql="INSERT INTO categorias (idgerencia, iddpto, descripcion) VALUES ('".$_SESSION['gerencia']."', '".$_POST['dpto']."', '".mysqli_real_escape_string($mysqli,utf8_encode($_POST['descripcion']))."')";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==5){
		$sql="UPDATE categorias SET descripcion='".mysqli_real_escape_string($mysqli,utf8_encode($_POST['descripcion']))."' WHERE id='".$_POST['idcategoria']."'";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==6){
		$sql="SELECT descripcion FROM categorias WHERE id='".$_POST['idcategoria']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$tabla='';
		$tabla.='<tr>
				<td colspan="3"><strong>Categoria: </strong>'.utf8_decode($row['descripcion']).'<input type="hidden" id="cat" name="cat" value="'.$_POST['idcategoria'].'" /></td>
			</tr>
			<tr>
				<td><strong>Subcategorias</td>
				<td width="5%"></td>
				<td width="5%"></td>
			</tr>';
		$sql2="SELECT id, descripcion FROM sub_categorias WHERE idcategoria='".$_POST['idcategoria']."'";
		$resultado2=$mysqli->query($sql2);
		$rows2=$resultado2->num_rows;
		if($rows2>0){
			$i=0;
			while($row2=$resultado2->fetch_assoc()){
				$i++;
				$tabla.='<tr>
					<td><input type="text" id="subcat'.$i.'" name="subcat'.$i.'" value="'.utf8_decode($row2['descripcion']).'" style="width:99%; background-color:transparent; border:0; "</td>
					<td><button id="editar" name="editar" onclick="editar5('.$row2['id'].')"><img src="../media/edit_rule.png" width="30" height="30" alt="editar" title="editar" /></button></td>
					<td><button id="eliminar" name="eliminar" onclick="eliminar5('.$row2['id'].')"><img src="../media/del01.png" width="30" height="30" alt="eliminar" title="eliminar" /></button></td>
				</tr>';	
			}
		}else{
			$tabla.='<tr>
					<td colspan="3">No existen subcategorias.</td>
				</tr>';
		}
		echo $tabla;
	}
	if($_POST['aux']==7){
		$sql="SELECT descripcion, sla FROM sub_categorias WHERE id='".$_POST['id']."'";
		$resultado=$mysqli->query($sql);
		$row=$resultado->fetch_assoc();
		$tabla='';
		$tabla.='<tr>
				<td colspan="3"><strong>Modificar datos de Subcategoria</strong></td>
			</tr>
			<tr>
				<td><strong>Descripción</strong></td>
				<td><strong>SLA</strong></td>
				<td width="5%"></td>
			</tr>
			<tr>
				<td><input type="text" id="descripcionsub" name="descripcionsub" value="'.utf8_decode($row['descripcion']).'" style="width:99%" onkeypress="return validaletra(event)"/></td>
				<td><input type="number" min="1" id="slasub" name="slasub" value="'.$row['sla'].'" style="width:99%" onkeypress="return valida(event)"/></td>
				<td width="5%"><button onclick="actualizar5(descripcionsub.value,'.$_POST['id'].', slasub.value)"><img src="../media/actualizar.png"/></button></td>
			</tr>';
			echo $tabla;
	}if($_POST['aux']==8){
		$sql="UPDATE sub_categorias SET descripcion='".mysqli_real_escape_string($mysqli,utf8_encode($_POST['descripcion']))."', sla='".$_POST['sla']."' WHERE id='".$_POST['id']."'";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==9){
		$sql="DELETE FROM sub_categorias WHERE id='".$_POST['id']."'";
		$resultado=$mysqli->query($sql);
	}
	if($_POST['aux']==10){
		$sql="INSERT INTO sub_categorias (idcategoria, descripcion, sla) VALUES ('".$_POST['idcat']."', '".mysqli_real_escape_string($mysqli,utf8_encode($_POST['descripcion']))."', '".$_POST['sla']."')";
		$resultado=$mysqli->query($sql);
	}
		
?>