<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Herramienta_tipo.php");

	switch ($function){
		case 'getAllUser':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->getAllUser($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllAdmin':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownAdmin':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		
		case 'getForDroptdownForOrdenServicio':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->getForDroptdownForOrdenServicio($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta_tipo']);
		break;




	}
?>