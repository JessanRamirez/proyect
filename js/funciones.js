// JavaScript Document
function valida(e){ //Permite solo números
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla == 8) return true;
	patron = /\d/;
	te = String.fromCharCode(tecla);
	return patron.test(te);
}
function valida2(e){ //Permite solo números y puntos
	tecla = (document.all) ? e.keyCode : e.which;
	if ((tecla==8) || (tecla==46)){ return true;}
	patron = /\d/;
	te = String.fromCharCode(tecla);
	return patron.test(te);
}
function validaletra(e){  //Permite solo letras
	tecla = (document.all) ? e.keyCode : e.which;
	//Tecla de retroceso para borrar, siempre la permite
	/*8 retroseso, 32 espacio, 225 á, 233 é, 237 í, 243 ó, 250 ú, 193 Á, 201 É, 205 Í, 211 Ó, 2018 U, 209 Ñ, 241 ñ, 64 @, . 46*/
	if ((tecla==8) || (tecla==32)|| (tecla==225)|| (tecla==233)|| (tecla==237)|| (tecla==243)|| (tecla==250)|| (tecla==193)|| (tecla==201)|| (tecla==205)|| (tecla==211)|| (tecla==218)|| (tecla==209)|| (tecla==241)||(tecla==64)|| (tecla==46)){
		return true;
	}
	// Patron de entrada, en este caso solo acepta numeros
	patron =/[a-zA-Z]/;
	tecla_final = String.fromCharCode(tecla);
	return patron.test(tecla_final);
}
function validarEmail(valor) {
	if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
		return 0;//correcto
	}else{
		return 1;//incorrecto
	}
}
function agregar(gerencia){
	if(gerencia!=''){
		$.ajax({
			url : 'consultas/con_gerencias.php', //Agregar Gerencia
			type : 'POST',
			dataType : 'html',
			data : { gerencia: gerencia, aux: 1 },
		})
		.done(function(resultado){
			alert(resultado);
			location.reload();			
		})
	}
}
function editar(idgerencia,i){//Mostrar Editar gerencia
	$("#editar_gerencia").show();
	$("#id_gerencia").val(idgerencia);
	descdpto=$("#descripcion"+i).val();
	$("#desc_gerencia").val(descdpto);
}
function actualizar(descgerencia){
	if (confirm("¿Desea modificar la descripción?") == true) {
		idgerencia=$("#id_gerencia").val();
		$.ajax({
			url : 'consultas/con_gerencias.php', //Actualizar descripcion de gerencia
			type : 'POST',
			dataType : 'html',
			data : { idgerencia: idgerencia, descgerencia:descgerencia, aux:2 },
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function eliminar(idgerencia){
	if (confirm("¿Eliminar la Gerencia?") == true) {
		if (confirm("Al eliminar la gerencia se eliminaran todo registro de la misma, ¿Esta de acuerdo?") == true) {
			$.ajax({
				url : 'consultas/con_gerencias.php', //Eliminar Gerencia
				type : 'POST',
				dataType : 'html',
				data : { idgerencia: idgerencia, aux:3 },
			})
			.done(function(resultado){
				location.reload();
			})
		}
	}
}
function cambiar(){		
	gerencia=$("#gerencia").val();
	$.ajax({
		url : 'consultas/con_gerencias.php', //Cambiar Departamento al usuario Admin
		type : 'POST',
		dataType : 'html',
		data : { gerencia: gerencia, aux:4 },
	})
	.done(function(resultado){
		location.reload();
	})
}
function agregar2(dpto){
	if(dpto!=''){
		$.ajax({
			url : 'consultas/con_departamentos.php', //Agregar Departamento
			type : 'POST',
			dataType : 'html',
			data : { dpto: dpto, aux: 1 },
		})
		.done(function(resultado){
			window.setTimeout(alert(resultado), 20000);
			location.reload();
		})
	}
}
function editar2(iddpto,i){//Mostrar Editar Departamento
	$("#editar_dpto").show();
	$("#id_dpto").val(iddpto);
	descdpto=$("#descripcion"+i).val();
	$("#desc_dpto").val(descdpto);
}
function actualizar2(descdpto){
	if (confirm("¿Desea modificar la descripción?") == true) {
		id_dpto=$("#id_dpto").val();
		$.ajax({
			url : 'consultas/con_departamentos.php', //Actualizar descripcion de Departamento
			type : 'POST',
			dataType : 'html',
			data : { id_dpto: id_dpto, descdpto:descdpto, aux:2 },
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function eliminar2(dpto){
	if (confirm("¿Eliminar Departamento?") == true) {
		$.ajax({
			url : 'consultas/con_departamentos.php', //Eliminar Departamento
			type : 'POST',
			dataType : 'html',
			data : { dpto: dpto, aux:3 },
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function crearuser(){
	if($("#nombreuser").val()==''){
		alert("Debe colocar el nombre del usuario.");
		$("#nombreuser").focus();
		return;
	}
	if($("#apellidouser").val()==''){
		alert("Debe colocar el apellido del usuario.");
		$("#apellidouser").focus();
		return;
	}
	if($("#correouser").val()==''){
		alert("Debe colocar el correo del usuario.");
		$("#correouser").focus();
		return;
	}else{
		valida=validarEmail($("#correouser").val());
		if(valida!=0){
			alert("El correo no cumple con el formato valido, ejemplo: prosein@prosein.com .");
			$("#correouser").focus();
			return;
		}
	}

	if($("#nivel").val()==''){
		alert("Debe seleccionar el nivel del usuario.");
		$("#nivel").focus();
		return;
	}
	if($("#claveuser").val()==''){
		alert("Debe colocar la clave del usuario.");
		$("#claveuser").focus();
		return;
	}
	if($("#claveuser2").val()==''){
		alert("Debe repetir la clave del usuario.");
		$("#claveuser2").focus();
		return;
	}
	if($("#claveuser").val()!=$("#claveuser2").val()){
		alert("Las clave debe coincidir.");
		$("#claveuser").focus();
		return;
	}else{
		a=0;
		for(i=1;i<=$("#i").val();i++){
			checkBox = document.getElementById("dpto"+i);
			if(checkBox.checked == true){
				a++;	
			}
		}
		if(a==0){
			alert("Debe seleccionar al menos un departamento para el usuario.");
		}else{
			nombre=$("#nombreuser").val();
			apellido=$("#apellidouser").val();
			correo=$("#correouser").val();
			clave=$("#claveuser").val();
			nivel=$("#nivel").val();
			activo=$("#activo").val();
			$.ajax({
				url : 'consultas/con_usuarios.php', //Crear usuarios
				type : 'POST',
				dataType : 'html',
				data : { nombre: nombre, apellido: apellido, correo: correo, clave: clave, nivel: nivel, activo: activo, aux: 1 },
				})
			.done(function(resultado3){
				if(resultado3==0){
					for(i=1;i<=$("#i").val();i++){
						checkBox = document.getElementById("dpto"+i);
						if(checkBox.checked == true){
							dpto=checkBox.value;
							$.ajax({
							url : 'consultas/con_usuarios.php', //Crear usuarios
							type : 'POST',
							dataType : 'html',
							data : { correo:correo, dpto: dpto, aux:0 },
							})
						}
					}
				}else{
					alert(resultado3);
				}
				location.reload();
			})
		}
	}
}
function editar3(iduser,i){
	$("#editarusuario").show();
	$.ajax({
		url : 'consultas/con_usuarios.php', //Modificar usuarios
		type : 'POST',
		dataType : 'html',
		data : { iduser:iduser,  aux:2 },
		})
	.done(function(resultado3){
		//location.reload();
		$("#editarusuario").html(resultado3);
	})
}
function actualizar3(iduser){
	if (confirm("¿Desea modificar la información?") == true) {
		if($("#nombreuser").val()==''){
			alert("Debe colocar el nombre del usuario");
			$("#nombreuser").focus();
			return;
		}
		if($("#apellidouser").val()==''){
			alert("Debe colocar el apellido del usuario");
			$("#apellidouser").focus();
			return;
		}
		if($("#correouser").val()==''){
			alert("Debe colocar el correo del usuario");
			$("#correouser").focus();
			return;
		}else{
			valida=validarEmail($("#correouser").val());
			if(valida!=0){
				alert("El correo no cumple con el formato valido, ejemplo: prosein@prosein.com");
				$("#correouser").focus();
				return;
			}
		}
		a=0;
		for(i=1;i<=$("#i2").val();i++){
			checkBox = document.getElementById("dpto2"+i);
			if(checkBox.checked == true){
				a++;	
			}
		}
		if(a==0){
			alert("Debe seleccionar al menos un departamento para el usuario.");
			return;
		}
		if($("#nivel").val()==''){
			alert("Debe seleccionar el nivel del usuario");
			$("#nivel").focus();
			return;
		}
		if($("#claveuser").val()!='' || $("#claveuser2").val()!=''){
			if($("#claveuser").val()!=$("#claveuser2").val()){
				alert("Las clave debe coincidir");
				$("#claveuser").focus();
				return;
			}
		}
		nombre=$("#nombreuser").val();
		apellido=$("#apellidouser").val();
		correo=$("#correouser").val();
		dpto=$("#dpto").val();
		nivel=$("#nivel").val();
		clave=$("#claveuser").val();
		activo=$("#activo").val();
		$.ajax({
			url : 'consultas/con_usuarios.php', //Actualizar datos de usuarios
			type : 'POST',
			dataType : 'html',
			data : { iduser: iduser, nombre: nombre, apellido: apellido, correo: correo, nivel: nivel, clave: clave, activo: activo, aux:4 },
		})
		.done(function(resultado){
			for(i=1;i<=$("#i2").val();i++){
			checkBox = document.getElementById("dpto2"+i);
			if(checkBox.checked == true){
				dpto=checkBox.value;
				$.ajax({
					url : 'consultas/con_usuarios.php', //Actualizar datos de usuarios
					type : 'POST',
					dataType : 'html',
					data : {  correo: correo, dpto: dpto, aux:5 },
				})	
			}
		}
		})
		location.reload();
	}
}
function eliminar3(usuario){
	if (confirm("¿Eliminar Usuario?") == true) {
		$.ajax({
			url : 'consultas/con_usuarios.php', //Eliminar Usuario
			type : 'POST',
			dataType : 'html',
			data : { usuario: usuario, aux:3 },
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function vercategorias(){
	dpto=$("#dptos").val();
	if(dpto!=''){
		$("#categorias").show();
		$.ajax({
			url : 'consultas/con_listacategorias.php', //Ver categorias
			type : 'POST',
			dataType : 'html',
			data : { dpto: dpto, aux:1 },
		})
		.done(function(resultado){
			$("#categorias").html(resultado);
		})
	}else{
		$("#categorias").hide();
	}
}
function editar4(idcategoria){
	$("#visual").hide();
	$("#visual2").hide();
	$("#visual3").hide();
	$("#mod2").hide();
	$("#mod").show();
	$.ajax({
		url : 'consultas/con_listacategorias.php', //Editar categorias
		type : 'POST',
		dataType : 'html',
		data : { idcategoria: idcategoria, aux:2 },
	})
	.done(function(resultado){
		$("#mod").html(resultado);
	})
}
function actualizar4(descripcion, idcategoria){
	if(descripcion==''){
		alert("Debe completar la descripción de la categoria");
	}else{
		$.ajax({
			url : 'consultas/con_listacategorias.php', //Actualizar la descripción de la categoria
			type : 'POST',
			dataType : 'html',
			data : { idcategoria: idcategoria, descripcion: descripcion, aux:5 },
		})
		.done(function(resultado){
			vercategorias();
		})
	}
}
function eliminar4(idcategoria){
	if (confirm("¿Eliminar Categoria?") == true) {
		$.ajax({
			url : 'consultas/con_listacategorias.php', //Eliminar categorias
			type : 'POST',
			dataType : 'html',
			data : { idcategoria: idcategoria, aux:3 },
		})
		.done(function(resultado){
			vercategorias();
		})
	}
}
function agregar4(descripcion){
	if(descripcion==''){
		alert("La descripcion no puede estar vacia.");
	}else{
		dpto=$("#dptos").val();
		$.ajax({
			url : 'consultas/con_listacategorias.php', //Agregar Categoria
			type : 'POST',
			dataType : 'html',
			data : { descripcion: descripcion, dpto: dpto, aux:4 },
		})
		.done(function(resultado){
			vercategorias();
		})
	}
}
function visualizar(idcategoria){
	$.ajax({
		url : 'consultas/con_listacategorias.php', //Editar categorias
		type : 'POST',
		dataType : 'html',
		data : { idcategoria: idcategoria, aux:6 },
	})
	.done(function(resultado){
		$("#mod").hide();
		$("#visual").show();
		$("#visual2").show();
		$("#visual3").show();
		$("#visual").html(resultado);
	})
}
function editar5(id){
	$.ajax({
		url : 'consultas/con_listacategorias.php', //Eliminar Subcategoria
		type : 'POST',
		dataType : 'html',
		data : { id: id, aux:7 },
	})
	.done(function(resultado){
		$("#mod2").show();
		$("#mod2").html(resultado);
	})
}
function actualizar5(descripcion, id, sla){
	if(descripcion==''){
		alert("La descripcion no puede estar vacia.");
		return;
	}else{
		if(sla==''){
			alert("Debe introducir el SLA.");
			return;
		}else{
			$.ajax({
				url : 'consultas/con_listacategorias.php', //Actualizar descripcion de Subcategoria
				type : 'POST',
				dataType : 'html',
				data : { descripcion: descripcion, sla: sla, id: id, aux:8 },
			})
			.done(function(resultado){
				id2=$("#cat").val();
				text="";
				$("#mod2").html(text);
				visualizar(id2);
			})
		}
	}
	
}
function eliminar5(id){
	if (confirm("¿Eliminar SubCategoria?") == true) {
		$.ajax({
			url : 'consultas/con_listacategorias.php', //Eliminar Subcategoria
			type : 'POST',
			dataType : 'html',
			data : { id: id, aux:9 },
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function agregar5(descripcion, sla){
	if(descripcion==''){
		alert("La descripcion no puede estar vacia.");
		return;
	}else{
		if(sla==''){
			alert("Debe introducir el SLA.");
			return;
		}else{
			idcat=$("#cat").val();
			$.ajax({
				url : 'consultas/con_listacategorias.php', //Agregar Subcategoria
				type : 'POST',
				dataType : 'html',
				data : { idcat: idcat, descripcion: descripcion, sla: sla, aux:10 },
			})
			.done(function(resultado){
				vercategorias();
			})
		}
	}
	
}
function actualizardias(){
	if (confirm("¿Actualizar Días Laborables?") == true) {
		$.ajax({
			url : 'consultas/con_calendario.php', //Eliminar toda la tabla de días laborables
			type : 'POST',
			dataType : 'html',
			data : { aux:1},
		})
		.done(function(resultado){
			for(i=1;i<=7;i++){
				checkBox = document.getElementById("dia"+i);
				if(checkBox.checked == true){
					dia=checkBox.value;
					$.ajax({
						url : 'consultas/con_calendario.php', //Eliminar toda la tabla de días laborables
						type : 'POST',
						dataType : 'html',
						data : { dia:dia, aux:2},
					})
				}
			}
			location.reload();
		})
	}
}
function agregardiafestivo(fecha, ano){
	if (confirm("¿Agregar día festivo?") == true) {
		if($("#fechafestiva").val()==''){
			alert("Debe indicar una fecha");
			$("#fechafestiva").focus();
		}else{
			$.ajax({
				url : 'consultas/con_calendario.php', //Eliminar toda la tabla de días laborables
				type : 'POST',
				dataType : 'html',
				data : { fecha: fecha, ano: ano, aux:3},
			})
			.done(function(resultado){
				location.reload();
			})
		}
	}
}
function eliminarfecha(id){
	if (confirm("¿Borrar día festivo?") == true) {
		$.ajax({
			url : 'consultas/con_calendario.php', //Eliminar toda la tabla de días laborables
			type : 'POST',
			dataType : 'html',
			data : { id: id, aux:4},
		})	
		.done(function(resultado){
			location.reload();
		})
	}
}
function atrasyear(year){//Ir 1 año atras
	year=year-1;
	$("#ano").val(year);
	diasfestivos(year);
}
function siguienteyear(year){//Ir 1 año adelante
	year= parseInt(year)+1;
	$("#ano").val(year);
	diasfestivos(year);
}
function diasfestivos(year){
	$.ajax({
		url : 'consultas/con_calendario.php', //Volver a Buscar días laborables
		type : 'POST',
		dataType : 'html',
		data : { year: year, aux:5},
	})
	.done(function(resultado){
		$("#diafestivo").html(resultado);
	})
	
}
function agregarhorario(){
	if($("#horadesde").val()==''){
		alert("Debe introducir la Hora Desde");
		$("#horadesde").focus();
		return;
	}
	if($("#horahasta").val()==''){
		alert("Debe introducir la Hora Hasta");
		$("#horahasta").focus();
		return;
	}
	if($("#horadesde").val()>$("#horahasta").val()){
		alert("Los minutos Hasta no pueden ser menores o igual al Desde");
		$("#minutoshasta").focus();
		return;
	}else{
		horadesde=$("#horadesde").val();
		horahasta=$("#horahasta").val();
		$.ajax({
			url : 'consultas/con_calendario.php', //Volver a Buscar días laborables
			type : 'POST',
			dataType : 'html',
			data : { horadesde: horadesde, horahasta: horahasta, aux:6},
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function eliminarhorario(id){
	if (confirm("¿Eliminar rango de horas?") == true) {
		$.ajax({
			url : 'consultas/con_calendario.php', //Eliminar rango de horas laborables
			type : 'POST',
			dataType : 'html',
			data : { id: id, aux: 7},
		})
		.done(function(resultado){
			location.reload();
		})
	}
}
function crear(){
	$.ajax({
		url : 'consultas/con_verificarsession.php', //Verificar que este la session iniciada
		type : 'POST',
		dataType : 'html',
		data : { test:1},
	})
	.done(function(resultado){
		if(resultado==''){
			$("#creacion").show();
			$("#bandeja").hide();
			$("#detalles").hide();
		}else{
			location.reload();
		}
	})
	
}
function crear2(){
	if($("#solicitante2").val()==''){
		if($("#solicitante").val()==''){
			alert("Introduzca el solicitante.");
			$("#solicitante").focus();
			return;
		}
	}
	if($("#Correo").val()==''){
		alert("Introduzca el correo del solicitante.");
		return;
	}else{
		valida=validarEmail($("#correo").val());
		if(valida!=0){
			alert("El correo no cumple con el formato valido, ejemplo: prosein@prosein.com .");
			$("#correo").focus();
			return;
		}
	}
	if($("#titulo").val()==''){
		alert("Introduzca el titulo de la solicitud.");
		$("#titulo").focus();
		return;
	}
	if($("#departamento").val()==''){
		alert("Indique el departamento.");
		$("#departamento").focus();
		return;
	}
	if($("#asignar").val()=='Sin Asignar'){
		if($("#estado").val()!='Pendiente por Asignar'){
			alert("Si no se asigna el ticket el estado debe ser colocado Pendiente por asignar.");
			$("#asignar").focus();
			return;
		}
	}
	if($("#categoria").val()=='-Seleccione-'){
		alert("Debe indicar la categoria de la solicitud.");
		$("#categoria").focus();
		return;
	}
	if($("#subcategoria").val()=='-Seleccione-'){
		alert("Debe indicar la sub categoria de la solicitud.");
		$("#subcategoria").focus();
		return;
	}
	if($("#fechacreacion").val()==''){
		alert("Debe seleccionar la fecha de creación del ticket.");
		$("#fechacreacion").focus();
		return;
	}
	if($("#fechaestimada").val()==''){
		alert("Debe seleccionar la fecha estimada de cierre del ticket.");
		$("#fechaestimada").focus();
		return;
	}
	if($("#comentario").val()==''){
		alert("Debe introducir un comentario.");
		$("#comentario").focus();
		return;
	}
	if($("#fechacierre").val()!=''){
		if($("#estado").val()!='Cerrado'){
			alert("Para indicar una fecha de cierre el estado del ticket debe ser Cerrado.");
			return;	
		}
		if($("#fechacierre").val()<$("#fechacreacion").val()){
			alert("La fecha de cierre no puede ser menor al de creación del ticket.");
			$("#fechacreacion").focus();
			return;
		}
	}
	if($("#estado").val()=='Cerrado'){
		if($("#fechacierre").val()==''){
			alert("Debe colocar la fecha de cierre del caso.");
			$("#fechacierre").focus();
			return;
		}
	}
	clasificacion=$("#clasificacion").val();
	prioridad=$("#prioridad").val();		
	recepcion=$("#recepcion").val();		
	estado=$("#estado").val();		
	oficina=$("#oficina").val();		
	if($("#solicitante2").val()!=''){
		solicitante=$("#solicitante2").val();
	}else{
		solicitante=$("#solicitante").val();
	}
	correo=$("#correo").val();		
	titulo=$("#titulo").val();		
	departamento=$("#departamento").val();		
	asignar=$("#asignar").val();		
	if((($("#asignar").val()!='Sin Asignar')&&($("#estado").val()!='En Proceso'))&&($("#estado").val()!='Cerrado')){
		estado='Asignado';
	}
	$("#carga").show();
	idsol=$("#idsolicilante").val();
	opc=$("#opcsol").val();
	categoria=$("#categoria").val();		
	subcategoria=$("#subcategoria").val();		
	fechacreacion=$("#fechacreacion").val();		
	fechaestimada=$("#fechaestimada").val();		
	fechacierre=$("#fechacierre").val();
	comentario=$("#comentario").val();
	$.ajax({
		url : 'consultas/con_tickets.php', //validar que la fecha este en un rango correcto
		type : 'POST',
		dataType : 'html',
		data : { fechacreacion:fechacreacion, aux: 10 },
	})
	.done(function(resultado){
		if(resultado==1){
			alert("La fecha seleccionada no es elegible, por favor ingrese otra.");
			$("#fechacreacion").focus();
			return;
		}
		if(resultado==2){
			alert("La hora seleccionada no es elegible, por favor ingrese otra.");
			$("#fechacreacion").focus();
			return;
		}
	})
	$.ajax({
		url : 'consultas/con_tickets.php', //Creación de ticket
		type : 'POST',
		dataType : 'html',
		data : { clasificacion: clasificacion, recepcion:recepcion, prioridad: prioridad, estado: estado, oficina: oficina, solicitante: solicitante, correo: correo, titulo: titulo, departamento: departamento, asignar: asignar, categoria: categoria, subcategoria: subcategoria, fechacreacion: fechacreacion, fechaestimada: fechaestimada, fechacierre: fechacierre, comentario: comentario, opc:opc, idsol:idsol, aux: 4 },
	})
	.done(function(resultado){
		$("#carga").hide();
		vaciar();
		$("#creacion").hide();
		$("#bandeja").show();
		bandeja();
	})
}
function vaciar(){		
	$("#clasificacion").val('Incidencia');
	$("#prioridad").val('Normal');
	$("#estado").val('Pendiente por Asignar');
	$("#recepcion").val('Correo Services Desk');
	$("#oficina").val('Sede Administrativa');
	$("#solicitante").val('');
	$("#solicitante2").val('');
	$("#idsol").val('');
	$("#correo").val('');		
	$("#titulo").val('');		
	$("#departamento").val('');		
	$("#asignar").html('');		
	$("#categoria").html('');		
	$("#subcategoria").html('');		
	$("#fechacreacion").val('');
	document.getElementById("fechacreacion").disabled = true;		
	$("#fechaestimada").val('');		
	$("#fechacierre").val('');
	$("#comentario").val('');
}
function bandeja(){
	$("#bandeja").show();
	$("#bandeja").html('<table id="carga"><tr><td width="16px"><img src="../media/ajax-loader.gif" "/></td></tr></table>');
	vaciar();
	$.ajax({
		url : 'consultas/con_tickets.php', //Ver Bandeja de ticket
		type : 'POST',
		dataType : 'html',
		data : { aux: 5 },
	})
	.done(function(resultado){
		$("#creacion").hide();
		$("#detalles").hide();
		$("#bandeja").html("");
		$("#bandeja").html(resultado);
		alertas();
	})
}
function alertas(){
	id='';
	$.ajax({
		url : 'consultas/con_alertaticket.php', //Ver Detalles del ticket
		type : 'POST',
		dataType : 'html',
		data : { id: id, aux: 1 },
	}).done(function(resultado){
		if(resultado!=1){
			alert(resultado);
		}
	})
	
}
function cancelaralerta(aviso, idticket){
	if (confirm("¿Detener la alerta?") == true) {
		$.ajax({
			url : 'consultas/con_alertaticket.php', //Ver Detalles del ticket
			type : 'POST',
			dataType : 'html',
			data : { idticket: idticket, aviso: aviso, aux: 2 },
		}).done(function(resultado){
			alert("Se detuvo la alerta para el ticket: "+idticket);
			bandeja();
		})
	}
}
function visualizar2(id){
	$("#carga").hide();
	$("#creacion").hide();
	$("#bandeja").hide();
	$("#detalles").show();
	$("#detalles").html('<table id="carga"><tr><td width="16px"><img src="../media/ajax-loader.gif" "/></td></tr></table>');
	$.ajax({
		url : 'consultas/con_tickets.php', //Ver Detalles del ticket
		type : 'POST',
		dataType : 'html',
		data : { id: id, aux: 6 },
	})
	.done(function(resultado){
		$("#detalles").html("");
		$("#detalles").html(resultado);
	})
}

function marcar(){
	checkBox = document.getElementById("solicitud");
	if(checkBox.checked == true){
		checkBox2 = document.getElementById("devolver");
		checkBox2.checked=true;
	}
}
function cambiodepartamento(dpto){
	if(dpto==''){
		$("#asignado2").html('');
		$("#categoria2").html('');
		$("#subcategoria2").html('');
	}else{
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { dpto: dpto, aux: 1 },
		})
		.done(function(resultado){
			$("#asignado2").html(resultado);
			dpto=$("#departamento2").val();
			$.ajax({
			url : 'consultas/con_tickets.php', //Lista de las subs categorias
			type : 'POST',
			dataType : 'html',
			data : { dpto: dpto, aux: 2 },
			})
			.done(function(resultado2){
				$("#categoria2").html(resultado2);
				cate=$("#categoria2").val();
				subcategoria(cate);
				
			})
		})	
	}
}
function cambiodepartamento2(dpto){
	if(dpto==''){
		$("#asignado2").html('<option></option>');
	}else{
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { dpto: dpto, aux: 1 },
		})
		.done(function(resultado){
			$("#asignado2").html(resultado);
		})	
	}
}
function cambiodepartamento3(dpto){
	if(dpto==''){
		$("#asignado3").html('');
		$("#categoria3").html('');
		$("#subcategoria3").html('');
	}else{
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { dpto: dpto, aux: 1 },
		})
		.done(function(resultado){
			$("#asignado3").html(resultado);
			dpto=$("#departamento3").val();
			$.ajax({
			url : 'consultas/con_tickets.php', //Lista de las subs categorias
			type : 'POST',
			dataType : 'html',
			data : { dpto: dpto, aux: 2 },
			})
			.done(function(resultado2){
				$("#categoria3").html(resultado2);
				cate=$("#categoria3").val();
				subcategoria2(cate);
			})
		})	
	}
}
function subcategoria(cate){
	if(cate=='-Seleccione-'){
		$("#subcategoria2").html('');
	}else{
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { cate: cate, aux: 3 },
		})
		.done(function(resultado){
			$("#subcategoria2").html(resultado);
		})	
	}

}
function subcategoria2(cate){
	if(cate=='-Seleccione-'){
		$("#subcategoria3").html('');
	}else{
		$.ajax({
		url : 'consultas/con_tickets.php', //Lista de las subs categorias
		type : 'POST',
		dataType : 'html',
		data : { cate: cate, aux: 3 },
		})
		.done(function(resultado){
			$("#subcategoria3").html(resultado);
		})	
	}

}
function actualizar6(id,nivel){
	dpto='';
	asignado='';
	estado='';
	if(nivel<2){
		if($("#estado3").val()==''){
			alert("Debe seleccionar el estado");
			$("#estado3").focus();
		}else{
			estado=$("#estado3").val();
		}
		if($("#departamento3").val()==''){
			alert("Debe seleccionar el dpto");
			$("#departamento3").focus();
			return;
		}else{
			dpto=$("#departamento3").val();
		}
	}
	if(nivel<3){
		if($("#asignado3").val()==''){
			alert("Debe asignar el ticket.");
			$("#asignado3").focus();
			return;
		}else{
			asignado=$("#asignado3").val();
		}
	}
	if($("#categoria3").val()=='-Seleccione-'){
		alert("Debe indicar la categoria de la solicitud.");
		$("#categoria3").focus();
		return;
	}else{
		categoria=$("#categoria3").val();
	}
	if($("#subcategoria3").val()=='-Seleccione-'){
		alert("Debe indicar la sub categoria de la solicitud.");
		$("#subcategoria3").focus();
		return;
	}else{
		subcategoria=$("#subcategoria3").val();
	}
	if($("#comentario3").val()==''){
		alert("Debe introducir un comentario.");
		$("#comentario3").focus();
		return;
	}else{
		comentario=$("#comentario3").val();
	}
	checkBox = document.getElementById("devolver");
	if(checkBox.checked == true){
		devolver='si';
	}else{
		devolver='no';
	}
	checkBox = document.getElementById("solicitud");
	if(checkBox.checked == true){
		solicitud='si';
	}else{
		solicitud='no';
	}
	$("#carga2").show();
	$.ajax({
	url : 'consultas/con_tickets.php', //Actualizar Tickets
	type : 'POST',
	dataType : 'html',
	data : { id: id, nivel: nivel, devolver: devolver, solicitud: solicitud, dpto: dpto, asignado: asignado, categoria: categoria, subcategoria: subcategoria, comentario: comentario, estado: estado, aux: 7 },
	})
	.done(function(resultado){
		$("#carga2").hide();
		visualizar2(id);
	})
}
function mostraravanzado(){
	$("#filtro1").hide();
	$("#filtro2").show();
	$("#filtro3").show();
}
function ocultaravanzado(){
	$("#filtro1").show();
	$("#filtro2").hide();
	$("#filtro3").hide();
}
function filtrar(nivel){
	if((nivel==0)||(nivel==1)){
		if(($("#titulo2").val()=='')&&($("#estado2").val()=='')&&($("#solicitante3").val()=='')&&($("#departamento2").val()=='')&&($("#asignado2").val()=='')&&($("#fechadesde").val()=='')&&($("#fechahasta").val()=='')){
			alert("Debe introducir al menos un valor.");
		}else{
			titulo=$("#titulo2").val();
			estado=$("#estado2").val();
			solicitante=$("#solicitante3").val();
			departamento=$("#departamento2").val();
			asignado=$("#asignado2").val();
			fechadesde=$("#fechadesde").val();
			fechahasta=$("#fechahasta").val();
			if(fechadesde!=''){
				if(fechahasta==''){
					alert("Debe colocar una Fecha Hasta.");
					$("#fechahasta").focus();
					return;
				}
			}
			if(fechahasta!=''){
				if(fechadesde==''){
					alert("Debe colocar una Fecha Desde.");
					$("#fechadesde").focus();
					return;
				}
			}
			if(fechahasta<fechadesde){
				alert("La fecha Hasta no puede ser menor a Fecha Desde");
				return;
			}
			$("#carga").show();
			$.ajax({
			url : 'consultas/con_tickets.php', //Actualizar Tickets
			type : 'POST',
			dataType : 'html',
			data : { titulo: titulo, estado: estado, solicitante: solicitante, departamento: departamento, asignado: asignado, fechadesde: fechadesde, fechahasta: fechahasta, aux: 9, nivel: nivel },
			})
			.done(function(resultado){
				$("#carga").hide();
				$("#tickets").html(resultado);
			})	
		}
	}else{
		if(nivel==2){
			if(($("#titulo2").val()=='')&&($("#estado2").val()=='')&&($("#solicitante2").val()=='')&&($("#asignado2").val()=='')&&($("#fechadesde").val()=='')&&($("#fechahasta").val()=='')){
				alert("Debe introducir al menos un valor.");
			}else{
				titulo=$("#titulo2").val();
				estado=$("#estado2").val();
				solicitante=$("#solicitante2").val();
				asignado=$("#asignado2").val();
				fechadesde=$("#fechadesde").val();
				fechahasta=$("#fechahasta").val();
				if(fechadesde!=''){
					if(fechahasta==''){
						alert("Debe colocar una Fecha Hasta.");
						$("#fechahasta").focus();
						return;
					}
				}
				if(fechahasta!=''){
					if(fechadesde==''){
						alert("Debe colocar una Fecha Desde.");
						$("#fechadesde").focus();
						return;
					}
				}
				if(fechahasta<fechadesde){
					alert("La fecha Hasta no puede ser menor a Fecha Desde");
					return;
				}
				for(i=1;i<=$("#cantdptos").val();i++){
					$("#tickets"+i).html('');
				}
				$("#carga").show();
				$.ajax({
				url : 'consultas/con_tickets.php', //Actualizar Tickets
				type : 'POST',
				dataType : 'html',
				data : { titulo: titulo, estado: estado, solicitante: solicitante,  asignado: asignado, fechadesde: fechadesde, fechahasta: fechahasta, aux: 9, nivel: nivel },
				})
				.done(function(resultado){
					$("#carga").hide();
					$("#tickets1").html(resultado);
				})	
			}
		}else{
			if(($("#titulo2").val()=='')&&($("#estado2").val()=='')&&($("#solicitante2").val()=='')&&($("#fechadesde").val()=='')&&($("#fechahasta").val()=='')){
				alert("Debe introducir al menos un valor.");
			}else{
				titulo=$("#titulo2").val();
				estado=$("#estado2").val();
				solicitante=$("#solicitante2").val();
				fechadesde=$("#fechadesde").val();
				fechahasta=$("#fechahasta").val();
				if(fechadesde!=''){
					if(fechahasta==''){
						alert("Debe colocar una Fecha Hasta.");
						$("#fechahasta").focus();
						return;
					}
				}
				if(fechahasta!=''){
					if(fechadesde==''){
						alert("Debe colocar una Fecha Desde.");
						$("#fechadesde").focus();
						return;
					}
				}
				if(fechahasta<fechadesde){
					alert("La fecha Hasta no puede ser menor a Fecha Desde");
					return;
				}
				$("#carga").show();
				$.ajax({
				url : 'consultas/con_tickets.php', //Actualizar Tickets
				type : 'POST',
				dataType : 'html',
				data : { titulo: titulo, estado: estado, solicitante: solicitante, fechadesde: fechadesde, fechahasta: fechahasta, aux: 9, nivel: nivel },
				})
				.done(function(resultado){
					$("#carga").hide();
					$("#tickets").html(resultado);
				})	
			}
		}
	}
}
function info(id){
	if (confirm("¿Solicitar mas informacion?") == true) {
		$.ajax({
		url : 'consultas/con_verificarsession.php', //Verificar que este la session iniciada
		type : 'POST',
		dataType : 'html',
		data : { test:1},
		})
		.done(function(resultado){
			if(resultado==''){
				$("#carga").show();
				$.ajax({
				url : 'consultas/con_informacionticket.php', //Actualizar Tickets
				type : 'POST',
				dataType : 'html',
				data : { id: id },
				})
				.done(function(resultado2){
					$("#creacion").hide();
					$("#carga").hide();
					$("#bandeja").show();
					bandeja();
				})
			}else{
				location.reload();
			}
		})
	}
}
function validarfecha(opc){
	fechadesde=$("#fechadesde").val();
	fechahasta=$("#fechahasta").val();
	if(fechadesde==''){
		alert("La fecha Desde no puede estar vacia");
		$("#fechadesde").focus();
	}else{
		if(fechahasta==''){
			alert("La fecha Hasta no puede estar vacia");
			$("#fechahasta").focus();
		}else{
			if(fechahasta<fechadesde){
				alert("La Fecha Hasta no puede ser menor a la Fecha Desde");
				$("#fechahasta").focus();
			}else{
				switch(opc){
					case 1:
						window.open('../p/graficos/grafico1.php?fecha='+fechadesde+'&fecha2='+fechahasta+'', '_blank');
						break;
					case 2:
						window.open('../p/graficos/grafico2.php?fecha='+fechadesde+'&fecha2='+fechahasta+'', '_blank');
					case 3:
						window.open('../p/graficos/grafico3.php?fecha='+fechadesde+'&fecha2='+fechahasta+'', '_blank');
						break;
					case 4:
						break;
					case 5:
						window.open('../p/graficos/grafico5.php?fecha='+fechadesde+'&fecha2='+fechahasta+'', '_blank');
						break;
					case 6:
						window.open('../p/graficos/grafico6.php?fecha='+fechadesde+'&fecha2='+fechahasta+'', '_blank');
						break;
				}
				
			}
		}
	}
}
function grafico(opc){
	switch(opc){
		case 1:
			contenido='Resumen de casos';
			break;
		case 2:
			contenido='Más frecuentes';
			break;
		case 3:
			contenido='Estatus de casos';
			break;
		case 4:
			contenido='Comparativa';
			break;
		case 5:
			contenido='Medio de recepción';
			break;
		case 6:
			contenido='Resumen por participantes';
			break;
	}
	contenido="<center><h3>"+contenido+"</h3></center><table width='100%' border='1'><tr><td colspan='2'><center><strong>Seleccione Rango de Fechas</strong></center></td></tr><tr><td><center><strong>Desde</strong></center></td><td><center><strong>Hasta</strong></center></td></tr><tr><td><input type='datetime-local' name='fechadesde' id='fechadesde'/></td><td><input type='datetime-local' name='fechahasta' id='fechahasta'/></td></tr><tr><td colspan='2' title='Visualizar reporte'><button onclick='validarfecha("+opc+")'><img src='../media/lupa.png' width='16' height='16' /></button></td></tr></table>";
	$("#grafico").html(contenido);
	$("#grafico").show();
}
function hoja_servicio(id){
	window.open('../p/consultas/con_hojadeservicio.php?id='+id, '_blank');	
}
function generar_reporte(fecha, fecha2, aux){
	window.open('exportar.php?fecha='+fecha+'&fecha2='+fecha2+'&aux='+aux, '_blank');
}
function activar(){
	$.ajax({
		url : 'consultas/con_mantenimiento.php', //Deasctivar Intranet
		type : 'POST',
		dataType : 'html',
		data : {aux:1 },
	})
	.done(function(resultado){
		if(resultado==1){
			alert('Sistema de Services Desk fue Activado.');
			location.reload();
		}
		if(resultado==2){
			alert('No se pudo ejecutar la acción, intente nuevamente.');
			return 0;
		}
	});
}
function desactivar(){
	$.ajax({
		url : 'consultas/con_mantenimiento.php', //Deasctivar Sistema de Services Desk
		type : 'POST',
		dataType : 'html',
		data : {aux:2 },
	})
	.done(function(resultado){
		if(resultado==1){
			alert('Sistema de Services Desk fue Desactivado.');
			location.reload();
		}
		if(resultado==2){
			alert('No se pudo ejecutar la acción, intente nuevamente.');
			return 0;
		}
	});
}