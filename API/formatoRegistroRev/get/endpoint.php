<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../FormatoRegistroRev.php");
	include_once("./../RegistrosRev.php");

	switch ($function) {
		
		case 'getInfoByID':
			$formato = new FormatoRegistroRev();
			echo $formato->getInfoByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoRegistroRev']);
		break;
		case 'getRegistrosByID':
			$registro = new RegistrosRev();
			echo $registro->getRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_registrosRev']);
		break;
		case 'getAllAdmin':
			$formato = new FormatoRegistroRev();
			echo $formato->getAllAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		//								FUNCIONES DE LA CLASE REGISTROS
		case 'getAllRegistrosByID':
			$registro = new RegistrosRev();
			echo $registro->getAllRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoRegistroRev']);
		break;

	}

?>