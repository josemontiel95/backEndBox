<?php 
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: multipart/form-data ; charset=utf-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	//echo json_encode("hola");
	
	if(!empty($_POST['function'])){
		$function= $_POST['function'];
		//echo json_encode(1);
	}else{
		echo json_encode(2);
	}

	include_once("./../FormatoRegistroRev.php");
	
	switch ($function){
		case 'insertJefeBrigada':
			$formato = new FormatoRegistroRev();
			echo $formato->insertJefeBrigada($_POST['token'],$_POST['rol_usuario_id'],$_POST['regNo'],$_POST['ordenDeTrabajo_id'],$_POST['localizacion'],$_POST['cono_id'],$_POST['varilla_id'],$_POST['flexometro_id'],$_POST['longitud'],$_POST['latitud']);		
		break;
		case 'updateFooter':
			$formato = new FormatoRegistroRev();
			echo $formato->updateFooter($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoRegistroRev'],$_POST['observaciones'],$_POST['cono_id'],$_POST['varilla_id'],$_POST['flexometro_id']);
		break;
		case 'updateHeader':
			$formato = new FormatoRegistroRev();
			echo $formato->updateHeader($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoRegistroRev'],$_POST['regNo'],$_POST['localizacion']);
		break;
		case 'initInsert':
			$registrorev = new RegistrosRev();
			echo $registrorev->initInsert($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoRegistroRev']);
		break;
		case 'insertRegistroJefeBrigada':
			$registrorev = new RegistrosRev();
			echo $registrorev->insertRegistroJefeBrigada($_POST['token'],$_POST['rol_usuario_id'],$_POST['campo'],$_POST['valor'],$_POST['id_registrosRev']);
		break;
		case 'deactivate':
					$formatocampo = new RegistrosRev();
					echo $formatocampo->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_registrosRev']);
		break;

	}

?>
