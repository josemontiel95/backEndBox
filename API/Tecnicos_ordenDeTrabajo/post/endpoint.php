
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
			echo $tecorden->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['tecnico_id'],$_POST['ordenDeTrabajo_id']);		
		break;
		case 'upDateAdmin':
			$tecorden = new Tecnicos_ordenDeTrabajo();
			echo $tecorden->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['tecnico_id'],$_POST['ordenDeTrabajo_id']);
		break;
		
	}

?>
