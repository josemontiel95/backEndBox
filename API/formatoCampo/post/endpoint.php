
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

	include_once("./../formatoCampo.php");

	switch ($function){
		case 'insertJefeBrigada':
			$obra = new formatoCampo();
			echo $obra->insertJefeBrigada($_POST['token'],$_POST['rol_usuario_id'],$_POST['informeNo'],$_POST['ordenDeTrabajo_id'],$_POST['observaciones'],$_POST['tipo'],$_POST['posInicial'],$_POST['posFinal']);		
		break;
		/*
		case 'upDateAdmin':
			$obra = new Obra();
			echo $obra->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_obra'],$_POST['obra'],$_POST['prefijo'],$_POST['fechaDeCreacion'],$_POST['descripcion'],$_POST['localizacion'],$_POST['nombre_residente'],$_POST['telefono_residente'],$_POST['correo_residente']$_POST['cliente_id'],$_POST['concretera'],$_POST['tipo'],$_POST['revenimiento'],$_POST['incertidumbre']);
		break;
		case 'deactivate':
			$obra = new Obra();
			echo $obra->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_obra']);
		break;
		case 'activate':
			$obra = new Obra();
			echo $obra->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_obra']);
		break;
		*/
	}

	
