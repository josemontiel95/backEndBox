<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Obra.php");

	switch ($function) {
		case 'getForDroptdownAdmin':
			$obra = new Obra();
			echo $obra->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllAdmin':
			$obra = new Obra();
			echo $obra->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllJefaLab':
			$obra = new Obra();
			echo $obra->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id']);
		break;
		
		case 'getByIDAdmin':
			$obra = new Obra();
			echo $obra->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra']);
		break;


	}
?>