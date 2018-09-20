<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../EnsayoCubo.php");

	switch ($function) {
		case 'calcularAreaResis':
			$ensayo = new EnsayoCubo();
			echo $ensayo->calcularAreaResis($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoCubo']);
			break;
		case 'getRegistrosByID':
			$ensayo = new EnsayoCubo();
			echo $ensayo->getRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoCubo']);
			break;
		case 'getAllRegistrosFromFooterByID':
			$ensayo = new EnsayoCubo();
			echo $ensayo->getAllRegistrosFromFooterByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['footerEnsayo_id']);
			break;
		
	}
?>