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
	}
?>