<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Concretera.php");

	switch ($function) {
		case 'getAllAdmin':
			$concretera = new Concretera();
			echo $rol->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);		
		break;
		case 'insert':
			$concretera = new Concretera();
			echo $rol->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['concretera']);		
		break;
		case 'upDate':
			$concretera = new Concretera();
			echo $concretera->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_concretera'],$_GET['concretera']);
		break;
		case 'getForDroptdownAdmin':
			$concretera = new Concretera();
			echo $concretera->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
	}
?>