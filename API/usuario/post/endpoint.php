
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
		case 'upLoadFoto':
			$imageFileType = strtolower(pathinfo($_FILES["uploadFile"]["name"],PATHINFO_EXTENSION));
			if($imageFileType == "png" || $imageFileType == "jpg"){
				$target_dir = "./../../../SystemData/UserData/".$_POST['id_usuario']."/";
				$dirDatabase = "SystemData/UserData/".$_POST['id_usuario']."/";
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
			    	$usuario = new Usuario();
		    		echo $usuario->upLoadFoto($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_usuario'],$target_fileDB);
		    	}else{
			    	$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Error al subir la foto','error' => 3);
					echo json_encode($arr);
		    	}	
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Erro invalido, solo aceptamos jpg y png','error' => 4);
					echo json_encode($arr);
			}
		break;
		case 'upDate':
			$usuario = new Usuario();
			echo $usuario->upDate($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_usuario'],$_POST['nombre'],$_POST['apellido'],$_POST['laboratorio_id'],$_POST['nss'],$_POST['email'],$_POST['fechaDeNac'],$_POST['rol_usuario_id_new']);
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

