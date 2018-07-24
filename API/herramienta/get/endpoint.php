<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Herramienta.php");

	switch ($function) {
		case 'getAllAdmin':
			$herra = new Herramienta();
			echo $herra->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$herra = new Herramienta();
			echo $herra->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'getForDroptdownAdmin':
			$herra = new Herramienta();
			echo $herra->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllJefaLab':
			$herra = new Herramienta();
			echo $herra->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id']);
		break;

		
	}
?>