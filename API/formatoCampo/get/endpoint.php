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
	include_once("./../registrosCampo.php");

	switch ($function) {
		case 'getHeader':
			$formato = new formatoCampo();
			echo $formato->getHeader($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		case 'getAllAdmin':
			$formato = new formatoCampo();
			echo $formato->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		//FUNCIONES DE LA CLASE REGISTROS
		case 'getAllRegistrosByID':
			$registro = new registrosCampo();
			echo $registro->getAllRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
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