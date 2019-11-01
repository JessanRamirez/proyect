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
		if($_SESSION['nivel']<=1){
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
	$(obtener_registros());
	function obtener_registros(gerencia){
		$.ajax({
			url : 'consultas/con_listagerencias.php', //Lista de gerencias
			type : 'POST',
			dataType : 'html',
			data : { gerencia: gerencia },
			})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
		})
	}
	$(document).on('keyup', '#busqueda', function(){
		var valorBusqueda=$(this).val();
		if (valorBusqueda!=""){
			obtener_registros(valorBusqueda);
		}
		else{
			obtener_registros();
		}
	});
	
	$(obtener_registros2());
	function obtener_registros2(area){
		$.ajax({
			url : 'consultas/con_listadepartamentos.php', //Lista de departamentos
			type : 'POST',
			dataType : 'html',
			data : { area: area },
			})
		.done(function(resultado2){
			$("#tabla_resultado2").html(resultado2);
		})
	}
	$(document).on('keyup', '#busqueda2', function(){
		var valorBusqueda2=$(this).val();
		if (valorBusqueda2!=""){
			obtener_registros2(valorBusqueda2);
		}
		else{
			obtener_registros2();
		}
	});
	$(obtener_registros3());
	function obtener_registros3(usuario){
		$.ajax({
			url : 'consultas/con_listausuarios.php', //Lista de usuarios
			type : 'POST',
			dataType : 'html',
			data : { usuario: usuario },
			})
		.done(function(resultado3){
			$("#tabla_resultado3").html(resultado3);
		})
	}
	$(document).on('keyup', '#busqueda3', function(){
		var valorBusqueda3=$(this).val();
		if (valorBusqueda3!=""){
			obtener_registros3(valorBusqueda3);
		}
		else{
			obtener_registros3();
		}
	});
	$(obtener_registros4());
	function obtener_registros4(calendario){
		$.ajax({
			url : 'consultas/con_listacalendario.php', //Calendario
			type : 'POST',
			dataType : 'html',
			data : { calendario: calendario },
			})
		.done(function(resultado4){
			$("#tabla_resultado4").html(resultado4);
		})
	}
	$(document).on('keyup', '#busqueda4', function(){
		var valorBusqueda4=$(this).val();
		if (valorBusqueda4!=""){
			obtener_registros4(valorBusqueda4);
		}
		else{
			obtener_registros4();
		}
	});
	
</script>
<link rel="shortcut icon" href="../media/favicon.ico">
</head>
<body>
	<script>
		$(document).ready(function(e){
		$.backstretch(["../media/fondo10.jpg"]);
		});
	</script>
    <div class="principal">
    	<div class="menu2">
        	<input type="checkbox" id="menu-bar" />
            <label for="menu-bar" id="icon-menu" ><img src="../media/menuico.png" width="30" height="30" /></label>
        </div>
        <div class="menu">
        	<h2>ZONA ADMINISTRATIVA</h2>
            <?php
				$sql4="SELECT descripcion FROM gerencias WHERE id='".$_SESSION['gerencia']."'";
				$resultado4=$mysqli->query($sql4);
				$row4=$resultado4->fetch_assoc();
			?>
            <p><strong><?php echo utf8_decode($row4['descripcion']);?></strong></p>
            <form action="administracion.php" method="post">
            	
                <ul>
                    <?php
                        if($_SESSION['nivel']==0){
                    ?>
                        <li><button name="zonaadminpost" value="zonaadminpost" id="zonaadminpost" ><img src="../media/configuraciones.png"/></button><label for="zonaadminpost"><strong> Gerencia</strong></label> </li>
                    <?php
                        }
                    ?>
                    <?php
                        if($_SESSION['nivel']==0){
                    ?>
                        <li><button name="calendariopost" value="calendariopost" id="calendariopost" ><img src="../media/calendario.png"/></button><label for="calendariopost"><strong> Calendario</strong></label> </li>
                    <?php
                        }
                    ?>
                    <li><button name="dptopost" value="dptopost" id="dptopost" ><img src="../media/areas.png"/></button><label for="dptopost"><strong>  Departamentos</strong></label> </li>
                    <li><button name="usuariospost" value="usuariospost" id="usuariospost" ><img src="../media/usuarios.png"/></button><label for="usuariospost"><strong> Usuarios</strong></label> </li>
                    <li><button name="categoriapost" value="categoriapost" id="categoriapost" ><img src="../media/incidencia.png"/></button><label for="categoriapost"><strong> Categorias</strong></label> </li>
                    <?php
                        if($_SESSION['nivel']==0){
                    ?>
                    	<li><button name="mantenimientopost" value="mantenimientopost" id="mantenimientopost" ><img src="../media/mantenimiento.png"/></button><label for="mantenimientopost"><strong> Mantenimiento</strong></label> </li>
                        <?php
						}
						?>
                    <li><a href="principal.php"><img src="../media/atras.png"/><label><strong> Atras</strong></label></a></li>
                    <li><a href="salir.php"><img src="../media/salir.png"/><label><strong>   Salir</strong></label></a></li>
                </ul>
            </form>
        </div>
        <div class="contenido">
	        <?php
				if(isset($_POST['zonaadminpost'])){?>
        	    	<p style="text-align:center"><strong>Lista de Gerencias</strong></p>
                    <section>
                        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar...">
                    </section>
                    <section id="tabla_resultado">
                    <!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
                    </section>
			<?php	
				}
				if(isset($_POST['calendariopost'])){?>
                    <!--<section>
                        <input type="text" name="busqueda4" id="busqueda4" placeholder="Buscar...">
                    </section>-->
                    <section id="tabla_resultado4">
                    <!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
                    </section>	
                <?php
				}
				if(isset($_POST['dptopost'])){?>
                	<p style="text-align:center"><strong>Lista de Departamentos</strong></p>
                    <section>
                        <input type="text" name="busqueda2" id="busqueda2" placeholder="Buscar...">
                    </section>
                    <section id="tabla_resultado2">
                    <!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
                    </section>
                <?php
                }
				if(isset($_POST['usuariospost'])){?>
                	<p style="text-align:center"><strong>Lista de Usuarios</strong></p>
                    <section>
                        <input type="text" name="busqueda3" id="busqueda3" placeholder="Buscar...">
                    </section>
                    <section id="tabla_resultado3">
                    <!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
                    </section>
                <?php
				}
				if(isset($_POST['categoriapost'])){?>
                	<p style="text-align:center"><strong>Seleccione el Departamento</strong></p>
                    <?php
					$sql="SELECT * FROM departamentos WHERE idgerencia='".$_SESSION['gerencia']."'";
					$resultado=$mysqli->query($sql);?>
                    <select id="dptos" name="dptos" onchange="vercategorias()">
                    	<option value="">-Seleccione el departamento-</option>
                    <?php
					while($row=$resultado->fetch_assoc()){?>
	                    <option value="<?php echo $row['id'];?>"><?php echo utf8_decode($row['descripcion']);?></option>
                    <?php
					}
					?>
                    </select>
                <?php
				}
				if(isset($_POST['mantenimientopost'])){?>
	                <table width="100%">
                		<tr>
                        	<td colspan="2"><center><h2>Activar/Desactivar Sistema de Services Desk</h2></center></td>
                        </tr>
                    <?php
					$sql="SELECT estado FROM mantenimiento";
					$resultado=$mysqli->query($sql);
					$row=$resultado->fetch_assoc();
					if($row['estado']==0){?>
                    	<tr>
                    		<td>El sistema se encuentra Activo.</td>
                         </tr>
                         <tr>
                        	<td><button name="desactivar" id="desactivar" onclick="desactivar()"><img title="Desactivar Sistema Services Desk" src="../media/apagar.png"/></button></td>
                        </tr>
                    <?php
					}else{?>
	                    <tr>
                    		<td><p><strong>El sistema se encuentra Desactivado.</strong></p></td>
                        </tr>
                        <tr>
		                    <td><button name="activar" id="activar" onclick="activar()"><img title="Activar Sistema Services Desk" src="../media/encender.png"/></button></td>
                        </tr>
                    <?php
					}?>
                    </table>
                <?php   
				}
			?>
            <!--Para cuando se va a editar una descripción de un gerencia-->
            <div id="editar_gerencia" style="display:none;"><br /><br />
            	<table border="1" width="100%">
                	<tr>
                    	<td><strong>Modificar descripción</strong></td>
                        <td width="5%"></td>
                    </tr>
                    <tr>
                    	<td><input type="hidden" id="id_gerencia" name="id_gerencia" /><input type="text" id="desc_gerencia" name="desc_gerencia"/></td>
                        <td><button id="editar_gerencia2" name="editar_gerencia2" onclick="actualizar(desc_gerencia.value)"><img src="../media/actualizar.png"/></button></td>
                    </tr>
                </table>
            </div>
            <!--Para cuando se va a editar una descripción de un departamento-->
            <div id="editar_dpto" style="display:none;"><br /><br />
            	<table border="1" width="100%">
                	<tr>
                    	<td><strong>Modificar descripción</strong></td>
                        <td width="5%"></td>
                    </tr>
                    <tr>
                    	<td><input type="hidden" id="id_dpto" name="id_dpto" /><input type="text" id="desc_dpto" name="desc_dpto"/></td>
                        <td><button id="editar_dpto2" name="editar_dpto2" onclick="actualizar2(desc_dpto.value)"><img src="../media/actualizar.png"/></button></td>
                    </tr>
                </table>
            </div>
             <!--Para cuando se va a visualizar las categorias-->
            <div id="categorias" style="display:none;"><br /><br />
	            
            </div>
        </div>
    </div>
</body>
</html>
<?php
		}else{
			header("location: prohibido.php");
		}
	}

?>