<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../formatoCampo.php");

	switch ($function) {
		case 'getHeader':
			$herra = new formatoCampo();
			echo $herra->getHeader($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		/*
		case 'getByIDAdmin':
			$herra = new formatoCampo();
			echo $herra->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'getForDroptdownAdmin':
			$herra = new formatoCampo();
			echo $herra->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllJefaLab':
			$herra = new formatoCampo();
			echo $herra->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id']);
		break;
		*/
	}
?>