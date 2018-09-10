
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

	include_once("./../Laboratorio.php");

	switch ($function){
		case 'insertAdmin':
			$lab = new Laboratorio();
			echo $lab->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['laboratorio'],$_POST['estado'],$_POST['municipio']);		
		break;
		case 'upDateAdmin':
			$lab = new Laboratorio();
			echo $lab->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_laboratorio'],$_POST['laboratorio'],$_POST['estado'],$_POST['municipio']);
		break;
		case 'deactivate':
			$lab = new Laboratorio();
			echo $lab->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_laboratorio']);
		break;
		case 'activate':
			$lab = new Laboratorio();
			echo $lab->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_laboratorio']);
		break;
	}

?>

