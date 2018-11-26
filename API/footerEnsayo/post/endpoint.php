
<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: multipart/form-data ; charset=utf-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	//echo json_encode("hola");
	
	if(!empty($_POST['function'])){
		$function= $_POST['function'];
	}else{
		echo json_encode(2);
	}

	include_once("./../footerEnsayo.php");

	switch ($function){
		case 'initInsert':
			$footer = new footerEnsayo();
			echo $footer->initInsert($_POST['token'],$_POST['rol_usuario_id'],$_POST['tipo'],$_POST['id_RegistroCCH']);
		break;
		case 'insertRegistroTecMuestra':
			$footer = new footerEnsayo();
			echo $footer->insertRegistroTecMuestra($_POST['token'],$_POST['rol_usuario_id'],$_POST['campo'],$_POST['valor'],$_POST['id_footerEnsayo']);		
		break;
		
		case 'completeFormato':
			$footer = new footerEnsayo();
			echo $footer->completeFormato($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_footerEnsayo']);		
		break;
		
		case 'generatePDFEnsayo':
			$footer = new footerEnsayo();
			echo $footer->generatePDFEnsayo($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_footerEnsayo']);		
		break;
		case 'generatePDFFinal':
			$footer = new footerEnsayo();
			echo $footer->generatePDFFinal($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoCampo'],$_POST['id_ensayo']);		
		break;
		case 'autEnsayoForAdmin':
			$footer = new footerEnsayo();
			echo $footer->autEnsayoForAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoCampo'],$_POST['id_ensayo']);		
		break;
		
		case 'formatoSeen':
			$footer = new footerEnsayo();
			echo $footer->formatoSeen($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoCampo'],$_POST['id_footerEnsayo']);		
		break;
		case 'getLog':
			$footer = new footerEnsayo();
			echo $footer->getLog($_POST['token'],$_POST['rol_usuario_id']);		
		break;
	}
?>
	
