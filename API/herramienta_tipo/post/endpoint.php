
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

	include_once("./../Usuario.php");

	switch ($function){
		case 'insertAdmin':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['tipo']);		
		break;
		case 'upDateAdmin':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta_tipo'],$_POST['tipo']);
		break;
		case 'deactivate':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta_tipo']);
		break;
		case 'activate':
			$herra_tipo = new Herramienta_tipo();
			echo $herra_tipo->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_herramienta_tipo']);
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

