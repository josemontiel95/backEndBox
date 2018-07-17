<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Cliente.php");

	switch ($function) {
		case 'insert':
			$cliente = new Cliente();
			echo $cliente->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['rfc'],$_GET['razonSocial'],$_GET['nombre'],$_GET['direccion'],$_GET['email'],$_GET['telefono'],$_GET['nombreContacto'],$_GET['telefonoDeContacto']);		
		break;
		case 'upDate':
			$cliente = new Cliente();
			echo $cliente->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_cliente'],$_GET['rfc'],$_GET['razonSocial'],$_GET['nombre'],$_GET['direccion'],$_GET['email'],$_GET['telefono'],$_GET['nombreContacto'],$_GET['telefonoDeContacto']);
		break;
		case 'getAll':
			$cliente = new Cliente();
			echo $cliente->getAll($_GET['token'],$_GET['rol_usuario_id']);
		break;
	}
?>