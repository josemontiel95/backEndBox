<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Tecnicos_ordenDeTrabajo.php");

	switch ($function) {
		case 'getAllTecOrden':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->getAllTecOrden($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		case 'getAllPasesLista':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->getAllPasesLista($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_tecnicos_ordenDeTrabajo']);
		break;
		
		case 'getTecAvailable':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->getTecAvailable($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getTecAsistencia':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->getTecAsistencia($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		
		case 'ping':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->ping($_GET['data']);
		break;
		case 'ping2':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->ping2($_GET['token'],$_GET['rol_usuario_id'],$_GET['data']);
		break;
	}
?>