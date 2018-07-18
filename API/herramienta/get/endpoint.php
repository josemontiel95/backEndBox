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
		case 'insert':
			$herra = new Herramienta();
			echo $herra->insert($_GET['token'],$_GET['rol_usuario_id'],$_GET['herramienta_tipo_id'],$_GET['fechaDeCompra'],$_GET['condicion']);		
		break;
		case 'upDate':
			$herra = new Herramienta();
			echo $herra->upDate($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta'],$_GET['herramienta_tipo_id'],$_GET['fechaDeCompra'],$_GET['condicion']);
		break;
		case 'getAll':
			$herra = new Herramienta();
			echo $herra->getAll($_GET['token'],$_GET['rol_usuario_id']);
		break;
		case 'getHerramientaByID':
			$herra = new Herramienta();
			echo $herra->getHerramientaByID($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
		case 'deactive':
			$herra_tipo = new Herramienta();
			echo $herra_tipo->deactive($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;

		case 'active':
			$herra_tipo = new Herramienta();
			echo $herra_tipo->active($_GET['token'],$_GET['rol_usuario_id'],$_GET['id_herramienta']);
		break;
	}
?>