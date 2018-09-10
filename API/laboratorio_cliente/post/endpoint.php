
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

	include_once("./../Laboratorio_cliente.php");

	switch ($function){
		
		case 'insertAdmin':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['ordenDeTrabajo_id'],$_POST['herramientasArray']);
		break;
		
		case 'changeLaboratorio_clienteState':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->changeLaboratorio_clienteState($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_laboratorio'],$_POST['estadoNo'],$_POST['cliente_id']);
		break;
		case 'deactivateHerra':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->deactivateHerra($_POST['token'],$_POST['rol_usuario_id'],$_POST['herramienta_id']);
		break;
		case 'deleteHerra':
			$lab_cli = new Laboratorio_cliente();
			echo $lab_cli->deleteHerra($_POST['token'],$_POST['rol_usuario_id'],$_POST['herramientasArray']);
		break;
	}

	
?>

