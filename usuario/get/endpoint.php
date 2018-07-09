<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}
	include_once("./../Usuario.php");

	switch ($function){
		case'login':
			$usuario = new Usuario();
			echo $usuario->login($_GET['email'],$_GET['constrasena']);
		break;
		case'validateSesion':
			$usuario = new Usuario();
			echo $usuario->validateSesion($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case'cerrarSesion':
			$usuario = new Usuario();
			echo $usuario->cerrarSesion($_GET['token']);
		break;
		case'getAllUsuarios':
			$usuario = new Usuario();
			echo $usuario->getAllUsuarios($_GET['token']);
		break;
		case 'upDate':
			$usuario = new Usuario();
			echo $usuario->setUsuario($_GET['id'],$_GET['nombre'],$_GET['apellido'],$_GET['email'],$_GET['fechaDeNac'],$_GET['rol_usuario_id'],$_GET['constrasena']);
		break;
		case 'insert':
			$usuario = new Usuario();
			echo $usuario->insert($_GET['nombre'],$_GET['apellido'],$_GET['email'],$_GET['fechaDeNac'],$_GET['rol_usuario_id'],$_GET['constrasena']);
		break;
	}

?>