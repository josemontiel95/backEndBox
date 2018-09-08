<?php 
	include_once("./../../formatoCampo/formatoCampo.php");
	include_once("./../../formatoCampo/registrosCampo.php");
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
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
				$regisFormato = $this->getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo);
				//echo $infoFormato['tipo'];

				switch ($infoFormato['tipo']) {
					case 'CUBO':
						$pdf = new InformeCubos();	$pdf->CreateNew($infoFormato,$regisFormato);
						break;
					case 'CILINDRO':
						//$pdf = new InformeCilindros();	$pdf->CreateNew($infoFormato,$regisFormato);
						break;

				
				}
			}
			//return json_encode($arr);
		}
	
		function getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
				    	SELECT
							id_ensayoCubo,
							ensayoCubo.fecha AS fechaEnsaye,
							claveEspecimen,
							revObra,
							CASE
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsaye,
							l1,
							l2,
							l1*l2 AS area,
							(carga*var_system.ensayo_def_kN)/var_system.ensayo_def_divisorKn AS kn,
							carga,
							(carga/(l1*l2))/var_system.ensayo_def_MPa AS mpa,
							carga/(l1*l2) AS kg,
							fprima,
							((carga/(l1*l2))/fprima)*100 AS porcentResis
						FROM
							ensayoCubo,
							registrosCampo,
							formatoCampo,
							(
								SELECT
									ensayo_def_kN,
									ensayo_def_MPa,
									ensayo_def_divisorKn
								FROM
									systemstatus
								ORDER BY id_systemstatus DESC LIMIT 1
							)AS var_system
						WHERE
							id_registrosCampo = ensayoCubo.registrosCampo_id AND
							id_formatoCampo = ensayoCubo.formatoCampo_id AND 
							ensayoCubo.formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}
	}
	

?>