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
			echo $concretera->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);		
		break;
		case 'getAllJefaLab':
			$concretera = new Concretera();
			echo $concretera->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id']);		
		break;
		case 'getForDroptdownAdmin':
			$concretera = new Concretera();
			echo $concretera->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$concretera = new Concretera();
			echo $concretera->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_concretera']);
		break;


	}
?>