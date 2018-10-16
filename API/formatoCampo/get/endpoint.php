<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../formatoCampo.php");
	include_once("./../registrosCampo.php");

	switch ($function) {
		/*	ERROR EN PARAMETRO, NO SE UTILIZA, PENDIENTE DE AUTORIZACION
		case 'getHeader':
			$formato = new formatoCampo();
			echo $formato->getHeader($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		*/
		case 'getInfoByID':
			$formato = new formatoCampo();
			echo $formato->getInfoByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		case 'completeFormato':
			$formato = new formatoCampo();
			echo $formato->completeFormato($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		
		case 'generatePDF':
			$formato = new formatoCampo();
			echo $formato->generatePDF($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		case 'getRegistrosByID':
			$registro = new registrosCampo();
			echo $registro->getRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_registrosCampo']);
		break;
		case 'getNumberOfRegistrosByID':
			$formato = new formatoCampo();
			echo $formato->getNumberOfRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		
		case 'getAllAdministrativo':
			$formato = new formatoCampo();
			echo $formato->getAllAdministrativo($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllAdmin':
			$formato = new formatoCampo();
			echo $formato->getAllAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		case 'getformatoDefoults':
			$formato = new formatoCampo();
			echo $formato->getformatoDefoults($_GET['token'],$_GET['rol_usuario_id'],$_GET['tipo']);
		break;
		
		case 'getDaysPruebasForCompletition':
			$registro = new registrosCampo();
			echo $registro->getDaysPruebasForCompletition($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formato']);
		break;
		case 'getDaysPruebasForDropDown':
			$registro = new registrosCampo();
			echo $registro->getDaysPruebasForDropDown($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formato']);
		break;
		//								FUNCIONES DE LA CLASE REGISTROS
		case 'getAllRegistrosByID':
			$registro = new registrosCampo();
			echo $registro->getAllRegistrosByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_formatoCampo']);
		break;
		case 'getRegistrosForToday':
			$registro = new registrosCampo();
			echo $registro->getRegistrosForToday($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getRegistrosForTodayByID':
			$registro = new registrosCampo();
			echo $registro->getRegistrosForTodayByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_registrosCampo']);
		break;
		case 'ping2':
			$formato = new formatoCampo();
			echo $formato->ping2($_GET['data']);
		break;
		
		/*
		case 'getByIDAdmin':
			$herra = new formatoCampo();
			echo $herra->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'getForDroptdownAdmin':
			$herra = new formatoCampo();
			echo $herra->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllJefaLab':
			$herra = new formatoCampo();
			echo $herra->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id']);
		break;
		*/
	}
?>