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
				$registros = new registrosCampo(); $regisFormato = json_decode($registros->getAllRegistrosByID($token,$rol_usuario_id,$id_formatoCampo),true);
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
		/*
		function getOperationsCubo($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$dbS->beginTransaction();
				$var_system = $dbS->qarrayA(
						"
							SELECT
								ensayo_def_pi
							FROM
								systemstatus
							ORDER BY id_systemstatus DESC;
						",array(),"SELECT"
						);




				$s= $dbS->qarrayA("
				    	SELECT
							id_ensayoCubo,
							
							carga,
							falla,
							ensayoCubo.fecha AS fechaEnsaye,
							claveEspecimen,
							revObra,
							l1,
							l2,
							l1*l2 AS area,


							registrosCampo_id,
							fecha,
							diasEnsaye,
							ensayoCubo.formatoCampo_id,
							informeNo,
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
							END AS diasEnsayeFinal
						FROM 
							ensayoCubo,registrosCampo,formatoCampo
						WHERE
							id_formatoCampo = ensayoCubo.formatoCampo_id AND
							id_registrosCampo = ensayoCubo.registrosCampo_id AND
							id_ensayoCubo = 1QQ
				      ",
				      array($id_ensayoCubo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_ensayoCubo'=>$id_ensayoCubo,'error' => 5);
					}
					else{
						return json_encode($s);
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return json_encode($arr);
		}

		function getinfoCubo($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA("
				    	SELECT
							id_ensayoCubo,
							
							carga,
							falla,
							ensayoCubo.fecha AS fechaEnsaye,
							claveEspecimen,
							revObra,
							l1,
							l2,
							l1*l2 AS area,


							registrosCampo_id,
							fecha,
							diasEnsaye,
							ensayoCubo.formatoCampo_id,
							informeNo,
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
							END AS diasEnsayeFinal
						FROM 
							ensayoCubo,registrosCampo,formatoCampo
						WHERE
							id_formatoCampo = ensayoCubo.formatoCampo_id AND
							id_registrosCampo = ensayoCubo.registrosCampo_id AND
							id_ensayoCubo = 1QQ
				      ",
				      array($id_ensayoCubo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_ensayoCubo'=>$id_ensayoCubo,'error' => 5);
					}
					else{
						return json_encode($s);
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return json_encode($arr);

		}*/
	}
	

?>