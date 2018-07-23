<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Laboratorio.php");

	switch ($function) {
		case 'getAllAdmin':
			$lab = new Laboratorio();
			echo $lab->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$lab = new Laboratorio();
			echo $lab->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_laboratorio']);
		break;
		case 'getForDroptdownAdmin':
			$lab = new Laboratorio();
			echo $lab->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
	}
?>