
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

	include_once("./../loteCorreos.php");

	switch ($function){
		case 'agregaFormatos':
			$lote = new loteCorreos();
			echo $lote->agregaFormatos($_POST['token'],$_POST['rol_usuario_id'],$_POST['lote'],$_POST['formatosSeleccionadosCCH'],$_POST['formatosSeleccionadosRev']);		
		break;
		
		case 'upDateLoteDetails':
			$lote = new loteCorreos();
			echo $lote->upDateLoteDetails($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_loteCorreos'],$_POST['factua'],$_POST['observaciones'],$_POST['customMail'],$_POST['customText'],$_POST['adjunto']);		
		break;
		case 'completarLote':
			$lote = new loteCorreos();
			echo $lote->completarLote($_POST['token'],$_POST['rol_usuario_id'],$_POST['lote']);
		break;
		case 'deleteCorreoLote':
			$lote = new loteCorreos();
			echo $lote->deleteCorreoLote($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_correoDeLote']);		
		break;
		case 'upLoadDoc':
			$fileName="";
			switch($_POST['doc']){
				case 1:
					$fileName="pdfPath";
				break;
				case 2:
					$fileName="xmlPath";
				break;
			}
			$imageFileType = strtolower(pathinfo($_FILES["uploadFile"]["name"],PATHINFO_EXTENSION));
			if($imageFileType == "png" || $imageFileType == "jpg" || $imageFileType == "pdf"|| $imageFileType == "xml"){
				$target_dir = "./../../../SystemData/Facturas/".$_POST['id_loteCorreos']."/";
				$dirDatabase = "SystemData/Facturas/".$_POST['id_loteCorreos']."/";
				if (!file_exists($target_dir)) {
				    mkdir($target_dir, 0777, true);
				}
		    	$json = array();
		    	$postData = file_get_contents("php://input");
		    	$input = json_decode($postData);
				$json['_FILES'] = $_FILES;
				
		    	$target_file = $target_dir . $fileName .'.'.$imageFileType;
		    	$target_fileDB = $dirDatabase . $fileName.'.'.$imageFileType;
				if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_file))  {  
					$json['uploadOK'] = 1;
				}else{
					$json['uploadOK'] = 0;
				}
		    	if($json['uploadOK']==1){
					$lote = new loteCorreos();
		    		echo $lote->upLoadDoc($_POST['token'],$_POST['rol_usuario_id'],$_POST['id_loteCorreos'],$fileName,$target_fileDB);
		    	}else{
			    	$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Error al subir la foto','error' => 3);
					echo json_encode($arr);
		    	}	
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Error invalido, solo aceptamos jpg, png o PDF y tu ingresaste un:'.$imageFileType,'error' => 4);
					echo json_encode($arr);
			}
		break;
	}
	
?>
	
