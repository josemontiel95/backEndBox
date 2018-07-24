<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../OrdenDeServicio.php");

	switch ($function) {
		case 'getAllAdmin':
			$orden = new OrdenDeServicio();
			echo $orden->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$orden = new OrdenDeServicio();
			echo $orden->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeServicio']);
		break;
		case 'getForDroptdownAdmin':
			$orden = new OrdenDeServicio();
			echo $orden->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		
	}
?>