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

	include_once("./../ValidatorTextPDF.php");

	switch ($function){
		case 'validatedInfo':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedInfo($_POST['campo'],$_POST['string']);
		break;
	}

	
?>