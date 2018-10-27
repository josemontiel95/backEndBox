<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../EnsayoCilindro.php");

	switch ($function) {
		case 'calcularAreaResis':
			$ensayo = new EnsayoCilindro();
			echo $ensayo->calcularAreaResis($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoCilindro']);
		break;
		case 'getRegistrosByID':
			$ensayo = new EnsayoCilindro();
			echo $ensayo->getRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoCilindro']);
		break;
			
		case 'getOldMembers':
			$ensayo = new EnsayoCilindro();
			echo $ensayo->getOldMembers($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoCilindro']);
		break;
		
		case 'getAllRegistrosFromFooterByID':
			$ensayo = new EnsayoCilindro();
			echo $ensayo->getAllRegistrosFromFooterByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['footerEnsayo_id']);
		break;
		case 'ping':
			$ensayo = new EnsayoCilindro();
			echo $ensayo->ping($_GET['data']);
		break;
		case 'calcularVelocidad':
			$ensayo = new EnsayoCilindro();
			echo $ensayo->calcularVelocidad($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ensayoCilindro']);
		break;
	}
?>