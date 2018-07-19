<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Cliente.php");

	switch ($function) {
		
		case 'getAllAdmin':
			$cliente = new Cliente();
			echo $cliente->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$cliente = new Cliente();
			echo $cliente->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_cliente']);
		break;
		case 'getForDroptdownAdmin';
			$cliente = new Cliente();
			echo $cliente->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;

	}
?>