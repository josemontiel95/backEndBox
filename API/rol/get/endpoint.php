<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Rol.php");

	switch ($function) {
		case 'insert':
			$rol = new Rol();
			echo $rol->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['rol']);		
		break;
		case 'upDate':
			$rol = new Rol();
			echo $rol->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_rol_usuario'],$_GET['rol']);
		break;
		case 'getAll':
			$rol = new Rol();
			echo $rol->getAll($_GET['token'],$_GET['rol_usuario_id']);
		break;
	}
?>