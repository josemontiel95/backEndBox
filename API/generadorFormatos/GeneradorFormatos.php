<?php 
	include_once("./../../formatoCampo/formatoCampo.php");
	include_once("./../../formatoCampo/registrosCampo.php");
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../usuario/Usuario.php");
	class GeneradorFormatos{
		/*
		function generateCampo($otken,$rol_usuario_id,$tipo,$infoFormato,$regisFormato){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				
			}
			return json_encode($arr);
		}


		function generateRev($otken,$rol_usuario_id,$tipo,$infoFormato,$regisFormato){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				
			}
			return json_encode($arr);
		}
		*/
		function generateInformeCampo($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$formato = new FormatoCampo();	$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
				$registros = new registrosCampo(); $regisFormato = json_decode($registros->getAllRegistrosByID($token,$rol_usuario_id,$id_formatoCampo),true);
				$pdf = new InformeCilindros();	$pdf->CreateNew($infoFormato,$regisFormato);
			}
			return json_encode($arr);
		}
	}

?>