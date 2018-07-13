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

	switch ($function) {
		case 'insert':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['tipo'],$_GET['placas']);		
		break;
		case 'upDate':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta_tipo'],$_GET['tipo'],$_GET['placas']);
		break;
		case 'getAll':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->getAll($_GET['token'],$_GET['rol_usuario_id']);
		break;
	}
?>