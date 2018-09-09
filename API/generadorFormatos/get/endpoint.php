<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../GeneradorFormatos.php");

	switch ($function) {
		case 'generateInformeCampo':
			$generador = new GeneradorFormatos();
			echo $generador->generateInformeCampo($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
			break;
		case 'generateCCH':
			$generador = new GeneradorFormatos();
			echo $generador->generateCCH($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
			break;
		case 'getRegCuboByFCCH':
			$generador = new GeneradorFormatos();
			echo $generador->getRegCuboByFCCH($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
			break;
	}
?>