<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}

	include_once("./../ValidatorTextPDF.php");

	switch ($function) {
		case 'validatedInfo':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedInfo($_GET['token'],$_GET['rol_usuario_id'],$_GET['numCampo']);
			break;
		
	}
?>