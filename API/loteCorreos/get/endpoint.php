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
		case 'completeFormato':
			$lote = new loteCorreos();
			echo $lote->completeFormato($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		case 'completeFormato':
			$lote = new loteCorreos();
			echo $lote->completeFormato($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		case 'getRegistrosByID':
			$registro = new registrosCampo();
			echo $registro->getRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_registrosCampo']);
		break;
		
		case 'getAllFormatosByLote':
			$lote = new loteCorreos();
			echo $lote->getAllFormatosByLote($_GET['token'],$_GET['rol_usuario_id'],$_GET['lote']);
		break;
		
		case 'ping2':
			$lote = new loteCorreos();
			echo $lote->ping2($_GET['data']);
		break;
		
	}
?>