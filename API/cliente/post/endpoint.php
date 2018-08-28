
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

	include_once("./../Cliente.php");

	switch ($function){
		case 'upDateAdmin':
			$cliente = new Cliente();
			echo $cliente->upDateAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_cliente'],$_POST['rfc'],$_POST['razonSocial'],$_POST['nombre'],$_POST['email'],$_POST['telefono'],$_POST['nombreContacto'],$_POST['telefonoDeContacto'],$_POST['calle'],$_POST['noExt'],$_POST['noInt'],$_POST['col'],$_POST['municipio'],$_POST['estado']);
		break;
		case 'insertAdmin':
			$cliente = new Cliente();
			echo $cliente->insertAdmin($_POST['token'],$_POST['rol_usuario_id'],$_POST['rfc'],$_POST['razonSocial'],$_POST['nombre'],$_POST['email'],$_POST['telefono'],$_POST['nombreContacto'],$_POST['telefonoDeContacto'],$_POST['calle'],$_POST['noExt'],$_POST['noInt'],$_POST['col'],$_POST['municipio'],$_POST['estado']);		
		break;
		case 'deactivate':
			$cliente = new Cliente();
			echo $cliente->deactivate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_cliente']);
		break;
		case 'activate':
			$cliente = new Cliente();
			echo $cliente->activate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_cliente']);
		break;
		case 'upLoadFoto':
			$imageFileType = strtolower(pathinfo($_FILES["uploadFile"]["name"],PATHINFO_EXTENSION));
			if($imageFileType == "png" || $imageFileType == "jpg"){
				$target_dir = "./../../../SystemData/ClienteData/".$_POST['id_cliente']."/";
				$dirDatabase = "SystemData/ClienteData/".$_POST['id_cliente']."/";
				if (!file_exists($target_dir)) {
				    mkdir($target_dir, 0777, true);
				}
		    	$json = array();
		    	$postData = file_get_contents("php://input");
		    	$input = json_decode($postData);
				$json['_FILES'] = $_FILES;
				
		    	$target_file = $target_dir . "foto_perfil.".$imageFileType;
		    	$target_fileDB = $dirDatabase . "foto_perfil.".$imageFileType;
				if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_file))  {  
					$json['uploadOK'] = 1;
				}else{
					$json['uploadOK'] = 0;
				}
		    	if($json['uploadOK']==1){
			    	$cliente = new Cliente();
		    		echo $cliente->upLoadFoto($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_cliente'],$target_fileDB);
		    	}else{
			    	$arr = array('id_cliente' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Error al subir la foto','error' => 6);
					echo json_encode($arr);
		    	}	
			}
			else{
				$arr = array('id_cliente' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Error invalido, solo aceptamos jpg y png y tu ingresaste un:'.$imageFileType,'error' => 7);
					echo json_encode($arr);
			}
		break;
		case 'upDateContrasena':
			$cliente = new Cliente();
			echo $cliente->upDateContrasena($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_cliente'],$_POST['constrasena']);
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

