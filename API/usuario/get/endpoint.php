
<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		echo -2;
	}
	include_once("./../Usuario.php");

	switch ($function){
		case'hola':
			echo 'como estas?';
		break;
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
		case 'insert':
			$usuario = new Usuario();
			echo $usuario->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['nombre'],$_GET['apellido'],$_GET['laboratorio_id'],$_GET['nss'],$_GET['email'],$_GET['fechaDeNac'],$_GET['rol_usuario_id_new'],$_GET['constrasena']);
		break;
		case 'upDateContrasena':
			$usuario = new Usuario();
			echo $usuario->upDateContrasena($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario'],$_GET['constrasena']);
		break;

		case 'deactivate':
			$usuario = new Usuario();
			echo $usuario->deactivate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario']);
		break;
		case 'upLoadFoto':
			$usuario = new Usuario();
			echo $usuario->upLoadFoto($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario']);
		break;
		case 'getIDByToken':
			$usuario = new Usuario();
			echo $usuario->getIDByToken($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'activate':
			$usuario = new Usuario();
			echo $usuario->activate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario']);
		break;
		//Hacerlo enviar los datos que necesitan
		case 'getUserByID':
			$usuario = new Usuario();
			echo $usuario->getUserByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario']);
		break;
	/*	case 'upDate':
			$usuario = new Usuario();
			echo $usuario->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_usuario'],$_GET['nombre'],$_GET['apellido'],$_GET['laboratorio_id'],$_GET['nss'],$_GET['email'],$_GET['fechaDeNac'],$_GET['rol_usuario_id_new']);
		break;*/
/*
		

		
		case 'insertHerra':
			$usuario = new Usuario();
			echo $usuario->insertHerra($_GET['token'],$_GET['rol_usuario_id'],$_GET['herramienta_tipo_id'],$_GET['fechaDeCompra'],$_GET['condicion']);
		break;
		case 'upDateHerra':
			$usuario = new Usuario();
			echo $usuario->upDateHerra($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta'],$_GET['herramienta_tipo_id'],$_GET['fechaDeCompra'],$_GET['condicion']);
		break;
		case 'deactivateHerra':
			$usuario = new Usuario();
			echo $usuario->deactivateHerra($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'insertObra':
			$usuario = new Usuario();
			echo $usuario->insertObra($_GET['token'],$_GET['rol_usuario_id'],$_GET['obra'],$_GET['prefijo'],$_GET['fechaDeCreacion'],$_GET['descripcion'],$_GET['cliente_id'],$_GET['concretera'],$_GET['tipo']);
		break;
		case 'upDateObra':
			$usuario = new Usuario();
			echo $usuario->upDateObra($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra'],$_GET['obra'],$_GET['prefijo'],$_GET['fechaDeCreacion'],$_GET['descripcion'],$_GET['cliente_id'],$_GET['concretera'],$_GET['tipo']);
		break;*/
	/*	case 'deactivateObra': PENDIENTE
			$usuario = new Usuario();
			echo $usuario->deactivateObra($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_obra']);
		break;*/
/*		case 'insertCliente':
			$usuario = new Usuario();
			echo $usuario->insertCliente($_GET['token'],$_GET['rol_usuario_id'],$_GET['rfc'],$_GET['razonSocial'],$_GET['email'],$_GET['telefono'],$_GET['nombreContacto'],$_GET['telefonoDeContacto']);
		break;
		case 'upDateCliente':
			$usuario = new Usuario();
			echo $usuario->insertCliente($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_cliente'],$_GET['rfc'],$_GET['razonSocial'],$_GET['email'],$_GET['telefono'],$_GET['nombreContacto'],$_GET['telefonoDeContacto']);
		break;
		case 'insertFormato':
			$usuario = new Usuario();
			echo $usuario->insertFormato($_GET['token'],$_GET['rol_usuario_id'],$_GET['formato'],$_GET['titulo'],$_GET['noCamposHeader'],$_GET['noCamposTecnico'],$_GET['noCamposMuestras'],$_GET['noCamposFooter'],$_GET['noFirmas']);
		break;
		case 'upDateFormato':
			$usuario = new Usuario();
			echo $usuario->upDateObra($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formato'],$_GET['formato'],$_GET['titulo'],$_GET['noCamposHeader'],$_GET['noCamposTecnico'],$_GET['noCamposMuestras'],$_GET['noCamposFooter'],$_GET['noFirmas']);
		break;*/
	}

	/*prueba*/

?>




