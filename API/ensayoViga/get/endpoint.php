<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../EnsayoViga.php");

	switch ($function) {
		case 'calcularPromedio':
			$ensayo = new EnsayoViga();
			echo $ensayo->calcularPromedio($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoViga']);
			break;
		case 'calcularModulo':
			$ensayo = new EnsayoViga();
			echo $ensayo->calcularModulo($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoViga']);
			break;
		case 'getRegistrosByID':
			$ensayo = new EnsayoViga();
			echo $ensayo->getRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoViga']);
			break;
	}
?>