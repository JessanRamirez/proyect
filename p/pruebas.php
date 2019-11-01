<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Autocomplete - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="../js/jquery-3.2.1.min.js"></script>
 	<script src="../js/jqueryui.js"></script>
    <script type="text/javascript">
        $(function () {
			var getData = function (request, response) {
			$.ajax({
				url : 'consultas/con_buscarsolicitante2.php', //
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
            }
            $("#solicitante").autocomplete({
                source: getData,
                select: selectItem,
                minLength: 4
            });
        });
		function obtener_registros(nom){
			$.ajax({
				url : 'consultas/con_buscarsolicitante2.php', //
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
				url : 'consultas/con_buscarsolicitante2.php', //
				type : 'POST',
				dataType : 'html',
				data : { sol:nom, aux: 3 },
			})
			.done(function(resultado){
				$("#idsolicilante").val(resultado.trim());
			});
		}
    </script>
</head>
<body>
 
<div class="ui-widget">
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
            <td><section><input type="text" autocomplete="off" id="solicitante" placeholder="Tecnología" name="solicitante" onKeyPress="return validaletra(event)" /></section></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="text" id="idsolicilante"name="idsolicilante" /><input type="hidden" id="opcsol"name="opcsol" /><input type="text" id="solicitante2" placeholder="" name="solicitante2" readonly="readonly" /></td>
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
</div>
 
 
</body>
</html>