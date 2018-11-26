<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../ordenDeTrabajo.php");

	switch ($function) {
		case 'getAllAdmin':
			$orden = new ordenDeTrabajo();
			echo $orden->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllJefaLab':
			$orden = new ordenDeTrabajo();
			echo $orden->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id'],$_GET['status']);
		break;
		case 'getByObraJefaLab':
			$orden = new ordenDeTrabajo();
			echo $orden->getByObraJefaLab($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra']);
		break;
		case 'getAllFormatos':
			$orden = new ordenDeTrabajo();
			echo $orden->getAllFormatos($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		case 'getAllByJefeBrigada':
			$orden = new ordenDeTrabajo();
			echo $orden->getAllByJefeBrigada($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getFULLByJefeBrigada':
			$orden = new ordenDeTrabajo();
			echo $orden->getFULLByJefeBrigada($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$orden = new ordenDeTrabajo();
			echo $orden->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		case 'getForDroptdownAdmin':
			$orden = new ordenDeTrabajo();
			echo $orden->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllHerraAvailable':
			$herra = new ordenDeTrabajo();
			echo $herra->getAllHerraAvailable($_GET['token'],$_GET['rol_usuario_id']);
		break;
		
		case 'ping':
			$herra = new ordenDeTrabajo();
			echo $herra->ping($_GET['data']);
		break;
	}
?>