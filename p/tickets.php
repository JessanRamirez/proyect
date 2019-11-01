<?php
	session_start();
	if(!isset($_SESSION['us'])){
		header("Location: prohibido.php");
	}else{
		include("conexion.php");
		$sql0="SELECT estado FROM mantenimiento";
		$resultado0=$mysqli->query($sql0);
		$row0=$resultado0->fetch_assoc();
		if($row0['estado']==1){
			if($_SESSION['us']!='1'){
				include('liberar.php');
				liberar();
				header("Location: ../index.php");
			}
		}		
		setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
		date_default_timezone_set('America/Caracas');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Services Desk - PROSEIN - Tickets</title>
<link rel="stylesheet" type="text/css" href="../css/style.css"/>
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css"/>
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/jqueryui.js"></script>
<script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
<link rel="shortcut icon" href="../media/favicon.ico">
<script type="text/javascript" src="../js/funciones.js"></script>
<script>
$(document).ready(function(){
	$("#departamento").change(function () {
		dpto=$("#departamento").val();
		$.ajax({
		url : 'consultas/con_tickets.php', //Obtener usuarios del departamento
		type : 'POST',
		dataType : 'html',
		data : { dpto: dpto, aux: 1 },
		})
		.done(function(resultado){
			$("#asignar").html(resultado);
			$.ajax({
			url : 'consultas/con_tickets.php', //Obtener las categorias del departamento
			type : 'POST',
			dataType : 'html',
			data : { dpto: dpto, aux: 2 },
			})
			.done(function(resultado2){
				$("#categoria").html(resultado2);
				$("#subcategoria").html('');
				if($("#fechaestimada").val()!=''){
					$("#fechacreacion").val('');
					$("#fechaestimada").val('');
				}
				subcate=$("#subcategoria").val();
			})
		})
			 
	})
	$("#categoria").change(function () {
		cate=$("#categoria").val();
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { cate: cate, aux: 3 },
		})
		.done(function(resultado){
			$("#subcategoria").html(resultado);
		})
	})
	$("#subcategoria").change(function () {
		document.getElementById("fechacreacion").disabled = false; 
		if($("#fechacreacion").val()!=''){
			subcate=$("#subcategoria").val();
			fecha=$("#fechacreacion").val();
			$.ajax({
			url : 'consultas/con_tickets.php', //Lista de las subs categorias
			type : 'POST',
			dataType : 'html',
			data : { subcate: subcate, fecha: fecha, aux: 9 },
			})
			.done(function(resultado){
				$("#fechaestimada").val(resultado);
			})
		}
	})
	$("#fechacreacion").change(function () {
		subcate=$("#subcategoria").val();
		fecha=$("#fechacreacion").val();
		estado=$("#estado").val();
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { subcate: subcate, fecha: fecha, estado: estado, aux: 8 },
		})
		.done(function(resultado){
			$("#fechaestimada").val(resultado);
		})
	})
	$("#estado").change(function (){
		if($("#estado").val()=='Cerrado'){
			document.getElementById("fechacierre").disabled = false; 
		}else{
			document.getElementById("fechacierre").disabled = true; 
		}
		if($("#fechacreacion").val()!=''){
			fecha=$("#fechacreacion").val();
			estado=$("#estado").val();
			$.ajax({
			url : 'consultas/con_tickets.php', //Lista de las subs categorias
			type : 'POST',
			dataType : 'html',
			data : { subcate: subcate, fecha: fecha, estado: estado, aux: 8 },
			})
			.done(function(resultado){
				$("#fechaestimada").val(resultado);
			})
		}
	})
	$(function () {
			var getData = function (request, response) {
			$.ajax({
				url : 'consultas/con_buscarsolicitante.php', //
				type : 'POST',
				dataType : 'json',
				data : { sol: request.term, aux: 1 },
			})
			.done(function(resultado){
					response(resultado);
				})
			};
            var selectItem = function (event, ui) {
                $("#solicitante").val(ui.item.value);
				obtener_registros(ui.item.value);
				obtener_registros2(ui.item.value);
				obtener_registros3(ui.item.value);
            }
            $("#solicitante").autocomplete({
                source: getData,
                select: selectItem,
                minLength: 2
            });
        });
		function obtener_registros(nom){
			$.ajax({
				url : 'consultas/con_buscarsolicitante.php', //
				type : 'POST',
				dataType : 'html',
				data : { sol:nom, aux: 2 },
			})
			.done(function(resultado){
				$("#correo").val(resultado.trim());
			});
		}
		function obtener_registros2(nom){
			$.ajax({
				url : 'consultas/con_buscarsolicitante.php', //
				type : 'POST',
				dataType : 'html',
				data : { sol:nom, aux: 3 },
			})
			.done(function(resultado){
				$("#idsolicilante").val(resultado.trim());
			});
		}
		function obtener_registros3(nom){
			$.ajax({
				url : 'consultas/con_buscarsolicitante.php', //
				type : 'POST',
				dataType : 'html',
				data : { sol:nom, aux: 4 },
			})
			.done(function(resultado){
				$("#opcsol").val(resultado.trim());
			});
		}
})
</script>	
</head>
<body>
	<script>
		$(document).ready(function(e){
		$.backstretch(["../media/fondo10.jpg"]);
		});
	</script>
    <div class="principal">
        <div class="menu">
        	<h2>MENÚ TICKETS</h2>
            <?php
				$sql4="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
				$resultado4=$mysqli->query($sql4);
				$row4=$resultado4->fetch_assoc();
			?>
            <p><strong><?php echo utf8_decode($row4['descripcion'])?></strong></p>
            <ul>
            	<?php
					if($_SESSION['nivel']<2){
				?>
            	<li><button name="crearpost" value="crearpost" id="crearpost" onclick="crear()"><img src="../media/configuraciones.png"/></button><label for="crearpost"><strong>  Crear Tickets</strong></label> </li>
                <?php
					}
				?>
                <li><button name="verpost" value="verpost" id="verpost" onclick="bandeja()" ><img src="../media/ticket.svg"/></button><label for="verpost"><strong>  Ver Tickets</strong></label> </li>
                <li><a href="principal.php"><img src="../media/atras.png"/><label><strong>  Atras</strong></label></a></li>
                <li><a href="salir.php"><img src="../media/salir.png"/><label><strong>   Salir</strong></label></a></li>
            </ul>
        </div>
        <div class="contenido" id="contenido">
        	<div id="creacion" style="display:none;"><br /><br />
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
                    	<td><strong>Método de recepción: </strong></td>
                        <td>
                        	<select id="recepcion" required>
	                            <option value="Correo Services Desk">Correo Services Desk</option>
                            	<option value="Teléfono Services Desk">Teléfono Services Desk</option>
                                <option value="Grupo WhatsApp">Grupo WhatsApp</option>
                                <option value="Otro">Otro</option>
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
                        <td><section><input type="text" id="solicitante" autocomplete="off" class="ui-autocomplete-input" placeholder="Tecnología" name="solicitante" onKeyPress="return validaletra(event)" /></section></td>
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
                        <td><input type="text" id="titulo" placeholder="Cambio de clave" name="titulo" /></td>
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
                        <td><input disabled="disabled" type="datetime-local" id="fechacreacion" name="fechacreacion" required="required" style="width:100%" value="<?php echo date('d/m/Y');?>"/></td>
                    </tr>
                    <tr>
                    	<td><strong>Fecha estimada de cierre: </strong></td>
                        <td><input disabled="disabled" type="text" id="fechaestimada" name="fechaestimada" required="required" style="width:100%" /></td>
                    </tr>
                    <tr>
                    	<td><strong>Fecha de cierre: </strong></td>
                        <td><input disabled="disabled" type="datetime-local" id="fechacierre" name="fechacierre" style="width:100%" /></td>
                    </tr>
                    <tr>
                    	<td><strong>Comentarios: </strong></td>
                        <td>
                        	<textarea required="required" id="comentario" style="height:80px;"></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2"><button id="vaciar" name="vaciar" onclick="vaciar()"><img src="../media/borrar.png" alt="Limpiar campos" title="Limpiar Campos" /></button></td>
                    </tr>
                    <tr>
                    	<td colspan="2"><button id="crear2" name="crear2" onclick="crear2()"><img src="../media/add_rule.png" title="Crear Ticket" /></button></td>
                    </tr>	
                </table>
                <table id="carga" style="display:none;"><tr><td><img src="../media/ajax-loader.gif"/></td></tr></table>
            </div>
            <br /><br /><div id="bandeja" style="display:none;">
            </div>
            <div id="detalles" style="display:none;">
            </div>
        </div>
    </div>
</body>
</html>
<?php
	}
?>