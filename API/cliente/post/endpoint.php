
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

	include_once("./../Cliente.php");

	switch ($function){
		case 'upDate':
			$cliente = new Cliente();
			echo $cliente->upDate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_cliente'],$_POST['rfc'],$_POST['razonSocial'],$_POST['nombre'],$_POST['direccion'],$_POST['email'],$_POST['telefono'],$_POST['nombreContacto'],$_POST['telefonoDeContacto']);
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

