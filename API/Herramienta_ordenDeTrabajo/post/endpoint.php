
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

	include_once("./../Herramienta_ordenDeTrabajo.php");

	switch ($function){
		/*		FUNCION BIEN
		case 'insertAdmin':
			$herra = new Herramienta_ordenDeTrabajo();
			echo $herra->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['ordenDeTrabajo_id'],$_POST['herramienta_id']);
		break;
		*/
		//PRUEBA
		case 'insertAdmin':
			$herra = new Herramienta_ordenDeTrabajo();
			echo $herra->insertAdmin($_POST['token'],$_POST['rol_usuario_id']);
		break;
		case 'deactivateHerra':
			$herra = new Herramienta_ordenDeTrabajo();
			echo $herra->deactivateHerra($_POST['token'],$_POST['rol_usuario_id'],$_POST['herramienta_id']);
		break;
		case 'deleteHerra':
			$herra = new Herramienta_ordenDeTrabajo();
			echo $herra->deleteHerra($_POST['token'],$_POST['rol_usuario_id'],$_POST['herramienta_id']);
		break;
	}

	

	/*
	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}
	include_once("./../Usuario.php");

	switch ($function){
		case 'upLoadFoto':
			$usuario = new Usuario();
			echo $usuario->upLoadFoto($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario']);
		break;

	}
	*/
?>

