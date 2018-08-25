
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

	include_once("./../formatoCampo.php");
	include_once("./../registrosCampo.php");

	switch ($function){
		case 'insertJefeBrigada':
			$formatocampo = new formatoCampo();
			echo $formatocampo->insertJefeBrigada($_POST['token'],$_POST['rol_usuario_id'],$_POST['informeNo'],$_POST['ordenDeTrabajo_id'],$_POST['tipo'],$_POST['cono_id'],$_POST['varilla_id'],$_POST['flexometro_id'],$_POST['termometro_id'],$_POST['longitud'],$_POST['latitud'],$_POST['status']);		
		break;
		case 'initInsert':
			$registrocampo = new registrosCampo();
			echo $registrocampo->initInsert($_POST['token'],$_POST['rol_usuario_id'],$_POST['formatoCampo_id']);
		break;
		case 'insertRegistroJefeBrigada':
			$registrocampo = new registrosCampo();
			echo $registrocampo->insertRegistroJefeBrigada($_POST['token'],$_POST['rol_usuario_id'],$_POST['campo'],$_POST['valor'],$_POST['id_registrosCampo']);
		break;
		case 'updateFooter':
			$formatocampo = new formatoCampo();
			echo $formatocampo->updateFooter($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoCampo'],$_POST['observaciones'],$_POST['cono_id'],$_POST['varilla_id'],$_POST['flexometro_id'],$_POST['termometro_id'],$_POST['tipo']);
		break;
		case 'updateHeader':
			$formatocampo = new formatoCampo();
			echo $formatocampo->updateHeader($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_formatoCampo'],$_POST['informeNo']);
		break;
		case 'deactivate':
			$formatocampo = new registrosCampo();
			echo $formatocampo->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_registrosCampo']);
		break;



		/*
		case 'upDateAdmin':
			$obra = new Obra();
			echo $obra->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_obra'],$_POST['obra'],$_POST['prefijo'],$_POST['fechaDeCreacion'],$_POST['descripcion'],$_POST['localizacion'],$_POST['nombre_residente'],$_POST['telefono_residente'],$_POST['correo_residente']$_POST['cliente_id'],$_POST['concretera'],$_POST['tipo'],$_POST['revenimiento'],$_POST['incertidumbre']);
		break;
		case 'deactivate':
			$obra = new Obra();
			echo $obra->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_obra']);
		break;
		case 'activate':
			$obra = new Obra();
			echo $obra->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_obra']);
		break;
		*/
	}
	
?>
	
