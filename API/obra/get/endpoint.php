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
		case 'insert':
			$obra = new Obra();
			echo $obra->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['obra'],$_GET['prefijo'],$_GET['fechaDeCreacion'],$_GET['descripcion'],$_GET['cliente_id'],$_GET['concretera'],$_GET['tipo']);		
		break;
		case 'upDate':
			$obra = new Obra();
			echo $obra->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra'],$_GET['obra'],$_GET['prefijo'],$_GET['fechaDeCreacion'],$_GET['descripcion'],$_GET['cliente_id'],$_GET['concretera'],$_GET['tipo']);
		break;
		case 'getAllUser':
			$obra = new Obra();
			echo $obra->getAllUser($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllAdmin':
			$obra = new Obra();
			echo $obra->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'deactive':
			$obra = new Obra();
			echo $obra->deactive($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra']);
		break;
		case 'active':
			$obra = new Obra();
			echo $obra->active($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra']);
		break;


	}
?>