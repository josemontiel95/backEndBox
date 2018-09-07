
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

	include_once("./../EnsayoCilindro.php");

	switch ($function){
		case 'initInsert':
			$footer = new EnsayoCilindro();
			echo $footer->initInsert($_POST['token'],$_POST['rol_usuario_id'],$_POST['registrosCampo_id'],$_POST['formatoCampo_id'],$_POST['footerEnsayo_id']);		
		break;
		case 'insertRegistroTecMuestra':
			$footer = new EnsayoCilindro();
			echo $footer->insertRegistroTecMuestra($_POST['token'],$_POST['rol_usuario_id'],$_POST['campo'],$_POST['valor'],$_POST['id_ensayoCilindro']);		
		break;
		case 'completeEnsayo':
			$footer = new EnsayoCilindro();
			echo $footer->completeEnsayo($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ensayoCilindro']);		
		break;
		
	}
	
?>
	
