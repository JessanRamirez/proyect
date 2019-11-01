<?php
	//Con esto se buscar evitar la ejecución de alguna actividad sin verificar primero si las variables Session estan creadas
	session_start();
	if(!isset($_SESSION['us'])){
		echo "no";?>    
    	<script>location.reload();</script>
	<?php	
	}else{
		echo '';
	}
?>