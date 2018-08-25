<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Tecnicos_ordenDeTrabajo.php");

	switch ($function) {
		case 'getAllTecOrden':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->getAllTecOrden($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;

	}
?>