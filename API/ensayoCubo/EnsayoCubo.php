<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class EnsayoCubo{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';
	
	
	
	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'l1';
					break;
				case '2':
					$campo = 'l2';
					break;
				case '3':
					$campo = 'carga';
					break;
				case '4':
					$campo = 'falla';
					break;
				case '5':
					$campo = 'tiempoDeCarga';
					break;
			}

			$dbS->squery("
						UPDATE
							ensayoCubo
						SET
							fecha = CURDATE(),
							1QQ = '1QQ'
						WHERE
							id_ensayoCubo = 1QQ
				",array($campo,$valor,$id_ensayoCubo),"UPDATE- EnsayoCubo :: insertRegistroTecMuestra");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$fechaEnsayo = $dbS->qarrayA(
					"
						SELECT 
							fecha
						FROM
							ensayoCubo
						WHERE
							id_ensayoCubo = 1QQ
					",
					array($id_ensayoCubo),
					"SELECT"
				);
				$arr = array('id_ensayoCubo' => $id_ensayoCubo,'estatus' => '¡Exito en la inserccion de un registro!','fechaEnsayo' => $fechaEnsayo['fecha'],'error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_ensayoCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	
	public function calcularAreaResis($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
				$variables = $dbS->qarrayA(
					"
						SELECT
							l1,
							l2,
							carga,
							tiempoDeCarga
						FROM
							ensayoCubo
						WHERE 
							id_ensayoCubo  = 1QQ
					",array($id_ensayoCubo),
					"SELECT -- EnsayoCubo :: calcularAreaResis : 1"
					);

			if(!$dbS->didQuerydied){
				
				$area = number_format(($variables['l1']*$variables['l2']),2);
				if($area == 0){
					$area = 'Error: Verifique sus datos, el area debe ser distinta de 0';
					$resistencia = 'Error: No se puede realizar una division entre 0';
					$velAplicacionExp = 'Error: No se puede realizar una division entre 0';
					$estatus='Error: No se puede realizar una division entre 0';
					$error = 5;
				} 	
				else{
					$resistencia = number_format($variables['carga']/$area,2);
					if($variables['tiempoDeCarga']!=0){
						$velAplicacionExp = number_format($resistencia / $variables['tiempoDeCarga'],2);
					}else{
						$velAplicacionExp = 'Error: No se puede realizar una division entre 0';
					}
					$error = 0;
					$estatus='Exito';
				}
				if($error == 0){
					$dbS->squery(
						"UPDATE
							ensayoCubo
						SET
							area = '1QQ',
							resistencia = '1QQ',
							velAplicacionExp = '1QQ'
						WHERE
							id_ensayoCubo = 1QQ
						",array($area,$resistencia,$velAplicacionExp,$id_ensayoCubo),
						"UPDATE -- EnsayoCubo :: calcularAreaResis : 2"
					);
				}
				if(!$dbS->didQuerydied){
					$dbS->commitTransaction();
					$arr = array('area' => $area,'resistencia' => $resistencia,'velAplicacionExp' =>$velAplicacionExp, 'error'=> $error,'estatus' => $estatus);
					return json_encode($arr);
				}else{
					$dbS->rollbackTransaction();
					$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 7);
					return json_encode($arr);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 6);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}
	
	public function getRegistrosByID($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			    	SELECT
						id_ensayoCubo,
						ensayoCubo.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						l1,
						l2,
						carga,
						falla,
						registrosCampo_id,
						claveEspecimen,
						ensayoCubo.fecha AS fechaEnsayo,
						diasEnsaye,
						ensayoCubo.formatoCampo_id,
						registrosCampo.fecha AS fechaColado,
						informeNo,
						ensayoCubo.status AS status,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal,
						velAplicacionExp,
						tiempoDeCarga,
						area,
						resistencia
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

	public function getAllRegistrosFromFooterByID($token,$rol_usuario_id,$footerEnsayo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			    	SELECT
						id_ensayoCubo,
						ensayoCubo.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						l1,
						l2,
						carga,
						falla,
						registrosCampo_id,
						claveEspecimen,
						ensayoCubo.fecha AS fechaEnsayo,
						diasEnsaye,
						ensayoCubo.formatoCampo_id,
						registrosCampo.fecha AS fechaColado,
						informeNo,
						ensayoCubo.status AS status,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal
					FROM 
						ensayoCubo,registrosCampo,formatoCampo
					WHERE
						id_formatoCampo = ensayoCubo.formatoCampo_id AND
						id_registrosCampo = ensayoCubo.registrosCampo_id AND
						ensayoCubo.footerEnsayo_id = 1QQ
			      ",
			      array($footerEnsayo_id),
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

	public function completeEnsayo($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
				$dbS->beginTransaction();
				$a = $dbS->qarrayA(
					"	SELECT
							registrosCampo_id,
							footerEnsayo_id
						FROM
							ensayoCubo
						WHERE
							id_ensayoCubo = 1QQ
					",
					array($id_ensayoCubo),
					"SELECT -- EnsayoCubo ::  completeEnsayo : 1"
									 );
				if(!$dbS->didQuerydied){
					$dbS->squery(
						"UPDATE
							footerEnsayo
						SET
							pendingEnsayos = pendingEnsayos -1
						WHERE
							id_footerEnsayo = 1QQ
						",array($a['footerEnsayo_id']),
						"UPDATE -- EnsayoCubo ::  completeEnsayo : 2"
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 40);
						return json_encode($arr);
					}
					$dbS->squery(
						"UPDATE
							registrosCampo
						SET
							statusEnsayo = 1
						WHERE
							id_registrosCampo = 1QQ
						",array($a['registrosCampo_id']),
						"UPDATE -- EnsayoCubo ::  completeEnsayo : 3"
					);
					
					if(!$dbS->didQuerydied){
						$dbS->squery(
							"UPDATE
								ensayoCubo
							SET
								fecha = CURDATE(),
								status = 1
							WHERE
								id_ensayoCubo = 1QQ
							",array($id_ensayoCubo),
							"UPDATE -- EnsayoCubo ::  completeEnsayo : 4"
						);
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('registroCubo' => $id_ensayoCilindro,'estatus' => '¡Ensayo completado!','error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('registroCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCubo, verifica tus datos y vuelve a intentarlo','error' => 5);
							return json_encode($arr);	
						}
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('registroCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 5);
						return json_encode($arr);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('registroCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}		
		}
		return json_encode($arr);
	}
	public function getOldMembers($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$info = $dbS->qarrayA(
				"   SELECT 
						grupo,
						registrosCampo.formatoCampo_id AS formatoCampo_id,
						id_registrosCampo
					FROM
						registrosCampo, ensayoCubo
					WHERE
						id_registrosCampo = registrosCampo_id AND
						id_ensayoCubo = 1QQ
				",
				array($id_ensayoCubo),
				"SELECT -- EnsayoCubo :: getOldMembers : 1"
			);
			if($dbS->didQuerydied || $info=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);
			}
			$arr = $dbS->qAll(
				"   SELECT 
						id_ensayoCubo
					FROM
						registrosCampo, ensayoCubo
					WHERE
						id_registrosCampo = registrosCampo_id AND
						registrosCampo.formatoCampo_id = 1QQ AND
						grupo = '1QQ' AND
						id_ensayoCubo < 1QQ
					ORDER BY id_registrosCampo DESC
				",
				array($info['formatoCampo_id'],$info['grupo'],$id_ensayoCubo),
				"SELECT -- EnsayoCubo :: getOldMembers : 2"
			);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 11);
				return json_encode($arr);
			}else if($arr=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 12);
				return json_encode($arr);
			}

		}
		return json_encode($arr);
	}
}
?>