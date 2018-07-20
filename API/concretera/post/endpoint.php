
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

	include_once("./../Concretera.php");

	switch ($function){
		case 'insertAdmin':
			$concretera = new Concretera();
			echo $concretera->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['concretera']);		
		break;
		case 'upDateAdmin':
			$concretera = new Concretera();
			echo $concretera->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta'],$_POST['herramienta_tipo_id'],$_POST['fechaDeCompra'],$_POST['placas'],$_POST['condicion']);
		break;
		case 'deactivate':
			$concretera = new Concretera();
			echo $concretera->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta']);
		break;
		case 'activate':
			$concretera = new Concretera();
			echo $concretera->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta']);
		break;
	}

?>

