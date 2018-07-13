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
		case 'insert':
			$lab = new Laboratorio();
			echo $lab->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['laboratorio'],$_GET['estado'],$_GET['municipio']);		
		break;
		case 'upDate':
			$lab = new Laboratorio();
			echo $lab->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_laboratorio'],$_GET['laboratorio'],$_GET['estado'],$_GET['municipio']);
		break;
		case 'getAll':
			$lab = new Laboratorio();
			echo $lab->getAll($_GET['token'],$_GET['rol_usuario_id']);
		break;
	}
?>