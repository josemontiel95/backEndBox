
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

	include_once("./../Tecnicos_ordenDeTrabajo.php");

	switch ($function){
		case 'insertAdmin':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['ordenDeTrabajo_id'],$_POST['tecnicosArray']);		
		break;
		case 'deleteTec':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->deleteTec($_POST['token'],$_POST['rol_usuario_id'],$_POST['tecnicosArray']);		
		break;
		/*	---No se usa--- De reserva por si se requiere despues.
		case 'upDateAdmin':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['tecnico_id'],$_POST['ordenDeTrabajo_id']);
		break;*/
		
	}

?>
