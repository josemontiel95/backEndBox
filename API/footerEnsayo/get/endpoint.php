<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../footerEnsayo.php");

	switch ($function) {
		case 'getFooterByID':
			$footer = new footerEnsayo();
			echo $footer->getFooterByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_footerEnsayo']);
		break;
			
		case 'getFooterByFormatoCampoID':
			$footer = new footerEnsayo();
			echo $footer->getFooterByFormatoCampoID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		case 'isTheWholeFamilyHereAndComplete':
			$footer = new footerEnsayo();
			echo $footer->isTheWholeFamilyHereAndComplete($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_footerEnsayo']);
		break;
		
		case 'getAwaitingApproval':
			$footer = new footerEnsayo();
			echo $footer->getAwaitingApproval($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllFooterPendientes':
			$footer = new footerEnsayo();
			echo $footer->getAllFooterPendientes($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'ping':
			$footer = new footerEnsayo();
			echo $footer->ping();
		break;
			
		case 'ping2':
			$footer = new footerEnsayo();
			echo $footer->ping2($_GET['data']);
		break;
		case 'generatePDFFinal':
			$footer = new footerEnsayo();
			echo $footer->generatePDFFinal($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo'],$_GET['id_ensayo']);		
		break;
	}
?>