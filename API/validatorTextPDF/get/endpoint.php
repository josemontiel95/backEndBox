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
			echo $validator->validatedInfo($_GET['campo'],$_GET['string']);
		break;
		/*
		case 'validatedCamposFinalCubos':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedCamposFinalCubos($_GET['campo'],$_GET['string']);
			break;*/
		case 'validatedCamposCCH':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedCamposCCH($_GET['campo'],$_GET['string']);
			break;
		case 'validatedCamposEnsayoCubo':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedCamposEnsayoCubo($_GET['campo'],$_GET['string']);
			break;

		case 'validatedCamposEnsayoCilindros':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedCamposEnsayoCilindros($_GET['campo'],$_GET['string']);
			break;
		case 'validatedCamposEnsayoVigas':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedCamposEnsayoVigas($_GET['campo'],$_GET['string']);
			break;
		case 'validatedCamposRevenimiento':
			$validator = new ValidatorTextPDF();
			echo $validator->validatedCamposRevenimiento($_GET['campo'],$_GET['string']);
			break;

		

	}
?>