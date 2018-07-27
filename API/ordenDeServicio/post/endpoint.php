
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

	include_once("./../OrdenDeServicio.php");

	switch ($function){
		case 'insertAdmin':
			$orden = new OrdenDeServicio();
			echo $orden->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['cotizacion_id'],$_POST['obra_id'],$_POST['actividades'],$_POST['condicionesTrabajo'],$_POST['fechaInicio'],$_POST['fechaFin'],$_POST['horaInicio'],$_POST['horaFin'],$_POST['observaciones'],$_POST['lugar'],$_POST['jefa_lab_id'],$_POST['jefe_brigada_id'],$_POST['laboratorio_id']);
		break;
		case 'upDateAdmin':
			$orden = new OrdenDeServicio();
			echo $orden->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeServicio'],$_POST['cotizacion_id'],$_POST['obra_id'],$_POST['actividades'],$_POST['condicionesTrabajo'],$_POST['fechaInicio'],$_POST['fechaFin'],$_POST['horaInicio'],$_POST['horaFin'],$_POST['observaciones'],$_POST['lugar'],$_POST['jefa_lab_id'],$_POST['jefe_brigada_id'],$_POST['laboratorio_id']);
		break;
		case 'deactivate':
			$orden = new OrdenDeServicio();
			echo $orden->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeServicio']);
		break;
		case 'activate':
			$orden = new OrdenDeServicio();
			echo $orden->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_ordenDeServicio']);
		break;
	}

	
