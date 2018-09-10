
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

	include_once("./../Herramienta.php");

	switch ($function){
		case 'insertAdmin':
			$herra = new Herramienta();
			echo $herra->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['herramienta_tipo_id'],$_POST['fechaDeCompra'],$_POST['placas'],$_POST['condicion'],$_POST['observaciones'],$_POST['laboratorio_id']);		
		break;
		case 'upDateAdmin':
			$herra = new Herramienta();
			echo $herra->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta'],$_POST['herramienta_tipo_id'],$_POST['fechaDeCompra'],$_POST['placas'],$_POST['condicion'],$_POST['observaciones'],$_POST['laboratorio_id']);
		break;
		case 'evaluateHerra':
			$herra = new Herramienta();
			echo $herra->evaluateHerra($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta'],$_POST['condicion'],$_POST['observaciones']);
		break;
		case 'deactivate':
			$herra_tipo = new Herramienta();
			echo $herra_tipo->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta']);
		break;

		case 'activate':
			$herra_tipo = new Herramienta();
			echo $herra_tipo->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta']);
		break;
	}

?>

