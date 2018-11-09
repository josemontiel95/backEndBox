<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class EnsayoCilindro{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function ping($data){
		return $data;	
	}


	public function initInsert($token,$rol_usuario_id,$registrosCampo_id,$formatoCampo_id,$footerEnsayo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
							ensayoCilindro(registrosCampo_id,formatoCampo_id,footerEnsayo_id)

						VALUES
							(1QQ,1QQ,1QQ)
				",array($registrosCampo_id,$formatoCampo_id,$footerEnsayo_id),"INSERT");
			if(!$dbS->didQuerydied){
				$id=$dbS->lastInsertedID;
				$arr = array('id_registrosCampo' => $id,'estatus' => '¡Exito en la inicializacion','error' => 0);
					return json_encode($arr);


			}else{
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);

	}
	
	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_ensayoCilindro){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'peso';
					break;
				case '2':
					$campo = 'd1';
					break;
				case '3':
					$campo = 'd2';
					break;
				case '4':
					$campo = 'h1';
					break;
				case '5':
					$campo = 'h2';
					break;
				case '6':
					$campo = 'carga';
					break;
				case '7':
					$campo = 'falla';
					break;
				case '9':
					$campo = 'velAplicacionExp';
					break;
				case '10':
					$campo = 'tiempoDeCarga';
					break;

			}

			$dbS->squery("
						UPDATE
							ensayoCilindro
						SET
							fecha = CURDATE(),
							1QQ = '1QQ'
						WHERE
							id_ensayoCilindro = 1QQ
				",array($campo,$valor,$id_ensayoCilindro),
				"UPDATE -- EnsayoCilindro :: insertRegistroTecMuestra : 1");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$fechaEnsayo = $dbS->qarrayA(
					"
						SELECT 
							fecha
						FROM
							ensayoCilindro
						WHERE
							fecha = CURDATE() AND
							id_ensayoCilindro = 1QQ
					",
					array($id_ensayoCilindro),
					"SELECT -- EnsayoCilindro :: insertRegistroTecMuestra : 2"
				);
				$arr = array('id_ensayoCilindro' => $id_ensayoCilindro,'estatus' => '¡Exito en la inserccion de un registro!','fechaEnsayo' => $fechaEnsayo['fecha'],'error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	
	public function calcularAreaResis($token,$rol_usuario_id,$id_ensayoCilindro){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$var_system = $dbS->qarrayA(
				"SELECT
					ensayo_def_pi
				FROM
					systemstatus
				ORDER BY id_systemstatus DESC;
				",array(),"SELECT  -- EnsayoCilindro :: calcularAreaResis : 1"
			);
			if(!$dbS->didQuerydied){
				$variables = $dbS->qarrayA(
					"SELECT
						d1,
						d2,
						carga,
						tiempoDeCarga
					FROM
						ensayoCilindro
					WHERE 
						id_ensayoCilindro  = 1QQ
				",array($id_ensayoCilindro),
				"SELECT -- EnsayoCilindro :: calcularAreaResis : 2"
				);
				if(!$dbS->didQuerydied){
					$dbS->commitTransaction();
					$promedio = ($variables['d1'] + $variables['d2'])/2;
					$area = number_format(((($promedio * $promedio) * $var_system['ensayo_def_pi'])/4),2);
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
							$velAplicacionExp = number_format(($resistencia / $variables['tiempoDeCarga'])*60,2);
						}else{
							$velAplicacionExp = 'Error: No se puede realizar una division entre 0';
						}
						$error = 0;
						$estatus='Exito';
					}
					if($error == 0){
						$dbS->squery(
							"UPDATE
								ensayoCilindro
							SET
								fecha = CURDATE(),
								area = '1QQ',
								resistencia = '1QQ',
								velAplicacionExp = '1QQ'
							WHERE
								id_ensayoCilindro = 1QQ
							",array($area,$resistencia,$velAplicacionExp,$id_ensayoCilindro),
							"UPDATE -- EnsayoCilindro :: calcularAreaResis : 3"
						);
					}
					$arr = array('area' => $area,'velAplicacionExp' => $velAplicacionExp, 'resistencia' => $resistencia, 'error'=> $error,'estatus' => $estatus);
					return json_encode($arr);
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 6);
					return json_encode($arr);
				}	
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las constantes del sistema.','error' => 7);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	public function getRegistrosByID($token,$rol_usuario_id,$id_ensayoCilindro){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA(
				"	SELECT
						id_ensayoCilindro,
						ensayoCilindro.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						peso,
						d1,
						d2,
						h1,
						h2,
						carga,
						falla,
						registrosCampo_id,
						claveEspecimen,
						ensayoCilindro.fecha AS fechaEnsayo,
						diasEnsaye,
						ensayoCilindro.formatoCampo_id,
						registrosCampo.fecha AS fechaColado,
						informeNo, 
						ensayoCilindro.status AS status,
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
						END AS diasEnsayeFinal,
						velAplicacionExp,
						tiempoDeCarga,
						area,
						resistencia
					FROM 
						ensayoCilindro,registrosCampo,formatoCampo
					WHERE
						id_formatoCampo = ensayoCilindro.formatoCampo_id AND
						id_registrosCampo = ensayoCilindro.registrosCampo_id AND
						id_ensayoCilindro = 1QQ
			      ",
			      array($id_ensayoCilindro),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_ensayoCilindro'=>$id_ensayoCilindro,'error' => 5);
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
						id_ensayoCilindro,
						ensayoCilindro.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						peso,
						d1,
						d2,
						h1,
						h2,
						carga,
						falla,
						registrosCampo_id,
						claveEspecimen,
						ensayoCilindro.fecha AS fechaEnsayo,
						diasEnsaye,
						ensayoCilindro.formatoCampo_id,
						registrosCampo.fecha AS fechaColado,
						informeNo, 
						ensayoCilindro.status AS status,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal
					FROM 
						ensayoCilindro,registrosCampo,formatoCampo
					WHERE
						id_formatoCampo = ensayoCilindro.formatoCampo_id AND
						id_registrosCampo = ensayoCilindro.registrosCampo_id AND
						ensayoCilindro.footerEnsayo_id = 1QQ
			      ",
			      array($footerEnsayo_id),
			      "SELECT -- EnsayoCilindro :: getAllRegistrosFromFooterByID : 1"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el footerEnsayo_id'=>$id_ensayoCilindro,'error' => 5);
				}else{
					return json_encode($s);
				}
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getAllRegistrosFromFooterByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}

	public function getOldMembers($token,$rol_usuario_id,$id_ensayoCilindro){
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
						registrosCampo, ensayoCilindro
					WHERE
						id_registrosCampo = registrosCampo_id AND
						id_ensayoCilindro = 1QQ
				",
				array($id_ensayoCilindro),
				"SELECT -- EnsayoCilindro :: getOldMembers : 1"
			);
			if($dbS->didQuerydied || $info=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);
			}
			$arr = $dbS->qAll(
				"   SELECT 
						id_ensayoCilindro
					FROM
						registrosCampo, ensayoCilindro
					WHERE
						id_registrosCampo = registrosCampo_id AND
						registrosCampo.formatoCampo_id = 1QQ AND
						grupo = '1QQ' AND
						id_ensayoCilindro < 1QQ
					ORDER BY id_registrosCampo DESC
				",
				array($info['formatoCampo_id'],$info['grupo'],$id_ensayoCilindro),
				"SELECT -- EnsayoCilindro :: getOldMembers : 2"
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

	public function completeEnsayo($token,$rol_usuario_id,$id_ensayoCilindro){
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
							ensayoCilindro
						WHERE
							id_ensayoCilindro = 1QQ
					",
					array($id_ensayoCilindro),
					"SELECT -- EnsayoCilindro ::  completeEnsayo : 1"
				);
				if(!$dbS->didQuerydied){
					$dbS->squery(
						"UPDATE
							footerEnsayo
						SET
							pendingEnsayos = pendingEnsayos -1,
							ensayosAwaitingApproval = ensayosAwaitingApproval +1,
							notVistoJLForEnsayoApproval = ensayosAwaitingApproval +1
						WHERE
							id_footerEnsayo = 1QQ
						",array($a['footerEnsayo_id']),
						"UPDATE -- EnsayoCilindro ::  completeEnsayo : 2"
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
						"UPDATE-- EnsayoCilindro ::  completeEnsayo : 3"
					);
					if(!$dbS->didQuerydied){
						$dbS->squery(
							"UPDATE
								ensayoCilindro
							SET
								fecha = CURDATE(),
								status = 1
							WHERE
								id_ensayoCilindro = 1QQ
							",array($id_ensayoCilindro),
							"UPDATE -- EnsayoCilindro ::  completeEnsayo : 4"
						);
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('id_ensayoCilindro' => $id_ensayoCilindro,'estatus' => '¡Ensayo completado!','error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCilindro, verifica tus datos y vuelve a intentarlo','error' => 5);
							return json_encode($arr);	
						}
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 5);
						return json_encode($arr);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}		
		}
		return json_encode($arr);
	}

}
?>