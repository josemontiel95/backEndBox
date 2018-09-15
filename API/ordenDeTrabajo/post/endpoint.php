
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

	include_once("./../ordenDeTrabajo.php");

	switch ($function){
		case 'insertAdmin':
			$orden = new ordenDeTrabajo();
			echo $orden->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['cotizacion_id'],$_POST['area'],$_POST['obra_id'],$_POST['actividades'],$_POST['condicionesTrabajo'],$_POST['fechaInicio'],$_POST['fechaFin'],$_POST['horaInicio'],$_POST['horaFin'],$_POST['observaciones'],$_POST['lugar'],$_POST['jefe_brigada_id'],$_POST['laboratorio_id']);
		break;
		case 'insertJefeLabo':
			$orden = new ordenDeTrabajo();
			echo $orden->insertJefeLabo($_POST['token'],$_POST['rol_usuario_id'],$_POST['area'],$_POST['obra_id'],$_POST['actividades'],$_POST['condicionesTrabajo'],$_POST['fechaInicio'],$_POST['fechaFin'],$_POST['horaInicio'],$_POST['horaFin'],$_POST['observaciones'],$_POST['lugar'],$_POST['jefe_brigada_id']);
		break;
		case 'upDateAdmin':
			$orden = new ordenDeTrabajo();
			echo $orden->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeTrabajo'],$_POST['area'],$_POST['obra_id'],$_POST['actividades'],$_POST['condicionesTrabajo'],$_POST['fechaInicio'],$_POST['fechaFin'],$_POST['horaInicio'],$_POST['horaFin'],$_POST['observaciones'],$_POST['lugar'],$_POST['jefe_brigada_id'],$_POST['laboratorio_id']);
		break;
		case 'updateJefeLabo':
			$orden = new ordenDeTrabajo();
			echo $orden->updateJefeLabo($_POST['token'],$_POST['rol_usuario_id'],$_POST['area'],$_POST['id_ordenDeTrabajo'],$_POST['obra_id'],$_POST['lugar'],$_POST['actividades'],$_POST['condicionesTrabajo'],$_POST['jefe_brigada_id'],$_POST['fechaInicio'],$_POST['fechaFin'],$_POST['horaInicio'],$_POST['horaFin'],$_POST['observaciones']);
		break;
		case 'deactivate':
			$orden = new ordenDeTrabajo();
			echo $orden->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeTrabajo']);
		break;
		case 'activate':
			$orden = new ordenDeTrabajo();
			echo $orden->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeTrabajo']);
		break;
		case 'upStatusByID':
			$orden = new ordenDeTrabajo();
			echo $orden->upStatusByID($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeTrabajo']);
		break;
		case 'completeOrden':
			$orden = new ordenDeTrabajo();
			echo $orden->completeOrden($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeTrabajo']);
		break;
	}
?>

	
