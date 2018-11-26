<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../loteCorreos.php");
	//include_once("./../registrosCampo.php");

	switch ($function) {
		case 'getAllAdmin':
			$lote = new loteCorreos();
			echo $lote->getAllAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['status']);
		break;
		
		
		case 'getLoteByID':
			$lote = new loteCorreos();
			echo $lote->getLoteByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote']);
		break;

		case 'getAllAdministrativo':
			$lote = new loteCorreos();
			echo $lote->getAllAdministrativo($_GET['token'],$_GET['rol_usuario_id']);
		break;
		
		case 'getAllAdministrativoFULL':
			$lote = new loteCorreos();
			echo $lote->getAllAdministrativoFULL($_GET['token'],$_GET['rol_usuario_id'],$_GET['obra_id']);
		break;
		case 'getAllFormatosByLote':
			$lote = new loteCorreos();
			echo $lote->getAllFormatosByLote($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote']);
		break;
		case 'generateAllFormatosByLote':
			$lote = new loteCorreos();
			echo $lote->generateAllFormatosByLote($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote']);
		break;

		case 'sentAllEmailFormatosByLote':
			$lote = new loteCorreos();
			echo $lote->sentAllEmailFormatosByLote($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote'],$_GET['all']);
		break;
		
		case 'sentGroupMailFormatosByLote':
			$lote = new loteCorreos();
			echo $lote->sentGroupMailFormatosByLote($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote']);
		break;
		
		case 'checkViabilityOfGroupMail':
			$lote = new loteCorreos();
			echo $lote->checkViabilityOfGroupMail($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote']);
		break;
		case 'getAllFormatos':
			$lote = new loteCorreos();
			echo $lote->getAllFormatos($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'ping2':
			$lote = new loteCorreos();
			echo $lote->ping2($_GET['data']);
		break;
		
	}
?>