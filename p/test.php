<?php
	include("../p/conexion.php");
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Services Desk - PROSEIN - Administracion</title>
<link rel="stylesheet" type="text/css" href="../css/style.css"/>
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="../js/funciones.js"></script>
<script>
$(document).ready(function() {
	function obtener_registros(sol){
		$.ajax({
			url : 'consultas/con_buscarsolicitante.php', //
			type : 'POST',
			dataType : 'text',
			data : { sol: sol, aux: 1 },
			})
		.done(function(resultado){
			resu=resultado;
			id='';
			aux=0;
			pos='';
			long=id.length;
			i=0;
			while(aux==0){
				if(resu[i]=='/'){
					pos=i;
					aux=1;
				}else{
					id=id+resu[i];
				}
				i++;
			}
			opc=resu[pos+1];
			$("#idsolicilante").val(id);
			$("#opcsol").val(opc);
			obtener_registros2(id,opc);
			obtener_registros3(id,opc);
		})
		
	}
	function obtener_registros2(id, opc){
		$.ajax({
			url : 'consultas/con_colocarsolicitante.php', //
			type : 'POST',
			dataType : 'text',
			data : { id: id, opc:opc, aux: 1 },
			})
		.done(function(resultado){
			$("#solicitante2").val(resultado);
		})
		
	}
	function obtener_registros3(id, opc){
		$.ajax({
			url : 'consultas/con_colocarcorreo.php', //
			type : 'POST',
			dataType : 'text',
			data : { id: id, opc:opc, aux: 1 },
			})
		.done(function(resultado){
			$("#correo").val(resultado);
		})
		
	}
	$(document).on('keyup', '#solicitante', function(){
		var valorBusqueda=$("#solicitante").val();
		if (valorBusqueda!=''){
			obtener_registros(valorBusqueda);
		}else{
			$("#solicitante2").val('');
		}
	});
	
	$(document).on('keyup', '#idsolicilante', function(){
		var valorBusqueda=$("#idsolicilante").val();
		if (valorBusqueda!=''){
			obtener_registros2(valorBusqueda);
		}else{
			$("#solicitante2").val('');
			$("#correo").val('');
		}
	});
})
</script>	
<div class="contenido" id="contenido">
    <div id="creacion" ><br /><br />
        <p style="text-align:center"><strong>CREACIÓN DE TICKET</strong></p>
        <table width="100%" border="1">
            <tr>
                <td width="30%"><strong>Clasificación: </strong></td>
                <td>
                    <select id="clasificacion" required>
                        <option value="Incidencia">Incidencia</option>
                        <option value="Falla">Falla</option>
                        <option value="Problema">Problema</option>
                        <option value="Requerimiento">Requerimiento</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Prioridad: </strong></td>
                <td>
                    <select id="prioridad" required>
                        <option value="Normal">Normal</option>
                        <option value="Alta">Alta</option>
                        <option value="Baja">Baja</option>
                    </select>
                </td>
            </tr>
             <tr>
                <td><strong>Estado: </strong></td>
                <td>
                    <select id="estado" required>
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
                <td><strong>Oficina: </strong></td>
                <td>
                    <select id="oficina" required>
                        <option value="Sede Administrativa">Sede Administrativa</option>
                        <option value="Barquisimeto">Barquisimeto</option>
                        <option value="Boleita">Boleita</option>
                        <option value="Colombia">Colombia</option>
                        <option value="El Bosque">El Bosque</option>
                        <option value="El Llanito">El Llanito</option>
                        <option value="El Paraiso">El Paraiso</option>
                        <option value="Franquicia">Franquicia</option>
                        <option value="Guarenas AIV">Guarenas AIV</option>
                        <option value="La Castellana">La Castellana</option>
                        <option value="Las Mercedes">Las Mercedes</option>
                        <option value="Los Naranjos">Los Naranjos</option>
                        <option value="San Isidro">San Isidro</option>
                        <option value="San Martín">San Martín</option>
                        <option value="USA">USA</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Solicitante: </strong></td>
                <td><section><input type="text" id="solicitante" placeholder="Tecnología" name="solicitante" onKeyPress="return validaletra(event)" /></section></td>
            </tr>
            <tr>
            	<td></td>
                <td><input type="hidden" id="idsolicilante"name="idsolicilante" /><input type="hidden" id="opcsol"name="opcsol" /><input type="text" id="solicitante2" placeholder="" name="solicitante2" readonly="readonly" /></td>
            </tr>
             <tr>
                <td><strong>Correo: </strong></td>
                <td><input type="email" id="correo" placeholder="tecnologia@prosein.com" name="correo"  /></td>
            </tr>
            <tr>
                <td><strong>Titulo: </strong></td>
                <td><input type="text" id="titulo" placeholder="Cambio de clave" name="titulo" onKeyPress="return validaletra(event)" /></td>
            </tr>
            <tr>
                <td><strong>Departamento: </strong></td>
                <td>
                    <select id="departamento" required>
                        <option value="">-Seleccione-</option>
                    <?php
                        $sql="SELECT id, descripcion FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."' ORDER BY descripcion ASC";
                        $resultado=$mysqli->query($sql);
                        while($row=$resultado->fetch_assoc()){?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['descripcion'];?></option>
                        <?php
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Asignar a: </strong></td>
                <td>
                    <select id="asignar" required>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Categorias: </strong></td>
                <td>
                    <select id="categoria" required>
                    </select>
                </td>
            </tr>
             <tr>
                <td><strong>Sub-Categorias: </strong></td>
                <td>
                    <select id="subcategoria" required>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Fecha de creación: </strong></td>
                <td><input disabled="disabled" type="date" id="fechacreacion" name="fechacreacion" required style="width:100%" value="<?php echo date('d/m/Y');?>"/></td>
            </tr>
            <tr>
                <td><strong>Fecha estimada de cierre: </strong></td>
                <td><input disabled="disabled" type="text" id="fechaestimada" name="fechaestimada" required style="width:100%" /></td>
            </tr>
            <tr>
                <td><strong>Fecha de cierre: </strong></td>
                <td><input disabled="disabled" type="date" id="fechacierre" name="fechacierre" style="width:100%" /></td>
            </tr>
            <tr>
                <td><strong>Comentarios: </strong></td>
                <td>
                    <textarea required id="comentario" style="height:80px;"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2"><button id="vaciar" name="vaciar" onClick="vaciar()"><img src="../media/borrar.png" alt="Limpiar campos" title="Limpiar Campos" /></button></td>
            </tr>
            <tr>
                <td colspan="2"><button id="crear2" name="crear2" onClick="crear2()"><img src="../media/add_rule.png" title="Crear Ticket" /></button></td>
            </tr>	
        </table>
        <table id="carga" style="display:none;"><tr><td><img src="../media/ajax-loader.gif"/></td></tr></table>
    </div>
    <br /><br /><div id="bandeja" style="display:none;">
    </div>
    <div id="detalles" style="display:none;">
    </div>
</div>
</head>
</html>