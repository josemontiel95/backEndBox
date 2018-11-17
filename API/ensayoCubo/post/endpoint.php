
<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: multipart/form-data ; charset=utf-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	
	if(!empty($_POST['function'])){
		$function= $_POST['function'];
	}else{
		echo json_encode(2);
	}

	include_once("./../EnsayoCubo.php");

	switch ($function){
		case 'initInsert':
			$footer = new EnsayoCubo();
			echo $footer->initInsert($_POST['token'],$_POST['rol_usuario_id'],$_POST['registrosCampo_id'],$_POST['formatoCampo_id'],$_POST['footerEnsayo_id']);		
		break;
		case 'insertRegistroTecMuestra':
			$footer = new EnsayoCubo();
			echo $footer->insertRegistroTecMuestra($_POST['token'],$_POST['rol_usuario_id'],$_POST['campo'],$_POST['valor'],$_POST['id_ensayoCubo']);		
		break;
		case 'completeEnsayo':
			$footer = new EnsayoCubo();
			echo $footer->completeEnsayo($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ensayoCubo']);		
		break;
		case 'completeEnsayoJL':
			$footer = new EnsayoCubo();
			echo $footer->completeEnsayoJL($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ensayoCubo']);		
		break;
		case 'editEnsayoJL':
			$footer = new EnsayoCubo();
			echo $footer->editEnsayoJL($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ensayoCubo']);
		break;
		case 'editEnsayo':
			$footer = new EnsayoCubo();
			echo $footer->editEnsayo($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ensayoCubo']);
		break;
	}
	
?>
	
