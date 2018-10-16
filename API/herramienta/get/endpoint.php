<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../Herramienta.php");

	switch ($function) {
		case 'getAllAdmin':
			$herra = new Herramienta();
			echo $herra->getAllAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getByIDAdmin':
			$herra = new Herramienta();
			echo $herra->getByIDAdmin($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'getForDroptdownAdmin':
			$herra = new Herramienta();
			echo $herra->getForDroptdownAdmin($_GET['token'],$_GET['rol_usuario_id']);
		break;

		case 'getForDroptdownJefeBrigadaCono':
			$herra = new Herramienta();
			echo $herra->getForDroptdownJefeBrigadaCono($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;

		case 'getForDroptdownJefeBrigadaVarilla':
			$herra = new Herramienta();
			echo $herra->getForDroptdownJefeBrigadaVarilla($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;

		case 'getForDroptdownJefeBrigadaFlexometro':
			$herra = new Herramienta();
			echo $herra->getForDroptdownJefeBrigadaFlexometro($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;

		case 'getForDroptdownJefeBrigadaTermometro':
			$herra = new Herramienta();
			echo $herra->getForDroptdownJefeBrigadaTermometro($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_ordenDeTrabajo']);
		break;
		case 'getAllJefaLab':
			$herra = new Herramienta();
			echo $herra->getAllJefaLab($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownTipo':
			$herra = new Herramienta();
			echo $herra->getForDroptdownTipo($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getAllFromTipo':
			$herra = new Herramienta();
			echo $herra->getAllFromTipo($_GET['token'],$_GET['rol_usuario_id'],$_GET['herramienta_tipo_id'],$_GET['id_ordenDeTrabajo'] );
		break;
		case 'getForDroptdownBasculas':
			$herra = new Herramienta();
			echo $herra->getForDroptdownBasculas($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownPrensas':
			$herra = new Herramienta();
			echo $herra->getForDroptdownPrensas($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownReglas':
			$herra = new Herramienta();
			echo $herra->getForDroptdownReglas($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownVernier':
			$herra = new Herramienta();
			echo $herra->getForDroptdownVernier($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownFlexo':
			$herra = new Herramienta();
			echo $herra->getForDroptdownFlexo($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getForDroptdownReglasVerFlex':
			$herra = new Herramienta();
			echo $herra->getForDroptdownReglasVerFlex($_GET['token'],$_GET['rol_usuario_id']);
		break;

	}
?>