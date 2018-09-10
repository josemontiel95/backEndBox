<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Laboratorio_cliente.php");

	switch ($function) {
		case 'getByIDAdminHerra':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->getByIDAdminHerra($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'try':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->try();
		break;
		case 'getAllLabsForCli':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->getAllLabsForCli($_GET['token'],$_GET['rol_usuario_id'],$_GET['cliente_id']);
		break;
		case 'getAllHerraAvailable':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->getAllHerraAvailable($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getHerramientaForDropdownRegistro':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->getHerramientaForDropdownRegistro($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;

	}
?>